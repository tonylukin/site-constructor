<?php

namespace app\services\siteCreator;

use app\helpers\TextSplitter;
use app\models\Image;
use app\models\Page;
use app\models\Site;
use app\models\SiteSearchWordLog;
use app\services\googleParser\SiteListGetter;
use app\services\Translations\TranslationInterface;
use yii\helpers\Inflector;
use yii\web\TooManyRequestsHttpException;

class Creator
{
    private const MAX_PAGES_PER_EXEC = 100;
    private const MAX_CONTENT_LENGTH = 100000;
    private const CONTENT_SOURCE_LANGUAGE = 'en';

    private SiteListGetter $siteListGetter;
    private Parser $parser;
    private int $newSitesCount = 0;
    private int $newPagesCount = 0;
    private int $existingPagesCount = 0;
    private int $imagesSavedCount = 0;
    private ?string $domain = null;
    private int $pageCount = 0;
    private bool $pageCountLimitReached = false;
    private ContentGenerator $contentGenerator;
    private TranslationInterface $translation;

    public function __construct(
        SiteListGetter $siteListGetter,
        Parser $parser,
        ContentGenerator $contentGenerator,
        TranslationInterface $translation
    )
    {
        $this->siteListGetter = $siteListGetter;
        $this->parser = $parser;
        $this->contentGenerator = $contentGenerator;
        $this->translation = $translation;
    }

    /**
     * @param string $domain
     * @param string $query
     * @return bool Whether site creation fully completed
     * @throws TooManyRequestsHttpException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(string $domain, string $query): bool
    {
        \Yii::warning('Start method: ' . __METHOD__, Parser::LOGGER_PREFIX);
        $this->domain = $domain;
        $fullUrlList = $this->siteListGetter->getSearchList($query);

        if (empty($fullUrlList)) {
            \Yii::warning('No URLs were found', Parser::LOGGER_PREFIX);
            return false;
        }
        $positionStart = \Yii::$app->cache->get($this->getPositionCacheKey()) ?: 0;
        \Yii::warning("Starting from position: {$positionStart}", Parser::LOGGER_PREFIX);
        $urlList = \array_splice($fullUrlList, $positionStart);
        \Yii::warning('Url list count: ' . \count($urlList), Parser::LOGGER_PREFIX);

        $site = Site::find()->byWordOrDomain($query, $this->domain)->one();
        if ($site === null) {
            $site = new Site();
            $site->search_word = $query;
            $site->domain = $this->domain;
            $site->setSlug();
            $site->setBodyClass();

            if (!$site->save()) {
                throw new \Exception('Error on saving site: ' . \implode('; ', $site->getErrorSummary(true)));
            }
            $this->newSitesCount++;
        } elseif ($site->search_word !== $query) {
            $siteSearchWordLog = SiteSearchWordLog::findOne(['search_word' => $query]);
            if ($siteSearchWordLog === null) {
                $siteSearchWordLog = new SiteSearchWordLog();
                $siteSearchWordLog->search_word = $query;
                $siteSearchWordLog->site_id = $site->id;

                if (!$siteSearchWordLog->save()) {
                    throw new \Exception('Error on saving site search word log: ' . \implode('; ', $siteSearchWordLog->getErrorSummary(true)));
                }
            }
        }

        $pages = Page::find()->bySourceUrls($urlList)->indexBy('source_url')->all();
        $i = 0;
        foreach ($urlList as $i => $url) {
            if ($this->pageCount >= self::MAX_PAGES_PER_EXEC) {
                $this->pageCountLimitReached = true;
                \Yii::warning('Max page per exec reached: ' . self::MAX_PAGES_PER_EXEC, Parser::LOGGER_PREFIX);
                return false;
            }

            if (!\Yii::$app->cache->set($this->getPositionCacheKey(), $positionStart + $i + 1)) {
                \Yii::warning("Could not save position: {$i}", Parser::LOGGER_PREFIX);
            }
            \Yii::warning("Processing url: '{$url}' in set: #{$i}", Parser::LOGGER_PREFIX);

            $page = $pages[$url] ?? null;
            if ($page === null) {
                $page = new Page();
            } else {
                $this->existingPagesCount++;
                continue;
            }
            $isNewRecord = $page->isNewRecord;

            $this->parser->getImageParser()->setDomain($this->domain);
            \Yii::warning("Sending request to '{$url}'", Parser::LOGGER_PREFIX);
            try {
                $content = $this->parser->parseSiteContent($url);
                if ($content === null) {
                    \Yii::warning("Skip site '{$url}'", Parser::LOGGER_PREFIX);
                    continue;
                }

                $content = \mb_substr($content, 0, self::MAX_CONTENT_LENGTH);
                $page->title = $this->parser->getTitle();
                $page->keywords = $this->parser->getKeywords();
                $page->description = $this->parser->getDescription();
                $translatedTexts = null;

                if ($site->target_language !== null) {
                    $contentSplitted = TextSplitter::chunkBySize($content, $this->translation->maxTextLength());
                    $translatedTexts = $this->translation->translate($contentSplitted, self::CONTENT_SOURCE_LANGUAGE, $site->target_language);
                    if ($translatedTexts !== null) {
                        $content = implode(' ', $translatedTexts);
                    }

                    $translation = $this->translation->translate([$page->title], self::CONTENT_SOURCE_LANGUAGE, $site->target_language);
                    if ($translation !== null) {
                        $page->title = $translation[0];
                    }
                    $translation = $this->translation->translate([$page->keywords], self::CONTENT_SOURCE_LANGUAGE, $site->target_language);
                    if ($translation !== null) {
                        $page->keywords = $translation[0];
                    }
                    $translation = $this->translation->translate([$page->description], self::CONTENT_SOURCE_LANGUAGE, $site->target_language);
                    if ($translation !== null) {
                        $page->description = $translation[0];
                    }
                }

                $page->site_id = $site->id;
                $page->content = $content;
                if ($translatedTexts === null) {
                    $page->seo_content = $this->contentGenerator->generateForPage($page);
                }
                $page->source_url = $url;
                $page->url = Inflector::slug($page->title) . ($translatedTexts === null ? '' : "_{$site->target_language}");
                $page->setPageIndex($i);

            } catch (\Throwable $e) {
                \Yii::error("Parse content error [{$url}]: {$e->getMessage()}", Parser::LOGGER_PREFIX);
                continue;
            }

            $transaction = \Yii::$app->db->beginTransaction();
            if ($transaction === null) {
                \Yii::error('Transaction is null', Parser::LOGGER_PREFIX);
                continue;
            }
            try {
                if ($page->save()) {
                    $images = $this->parser->getImageParser()->getImages();
                    if (!empty($images)) {
                        $imageFilename = $images[0][ImageParser::IMAGE_FILENAME_KEY];
                        $imageUrl = $images[0][ImageParser::IMAGE_URL_KEY];
                        $image = Image::findOne([
                            'source' => $imageFilename,
                            'original_url' => $imageUrl,
                        ]);

                        if ($image === null) {
                            $image = new Image();
                            $image->page_id = $page->id;
                            $image->source = $imageFilename;
                            $image->original_url = \substr($imageUrl, 0, 255);
                            if ($image->save()) {
                                $this->imagesSavedCount++;
                            } else {
                                \Yii::error('Could not save image: ' . \implode('; ', $image->getErrorSummary(true)), ImageParser::LOGGER_PREFIX);
                            }
                        }
                    }
                    if ($isNewRecord) {
                        $this->newPagesCount++;
                    }
                } else {
                    \Yii::error(\implode('; ', $page->getErrorSummary(true)), Parser::LOGGER_PREFIX);
                }

                $transaction->commit();

            } catch (\Throwable $e) {
                \Yii::error($e->getMessage(), Parser::LOGGER_PREFIX);
                if ($transaction->isActive) {
                    $transaction->rollBack();
                }
            }
            $this->pageCount++;
        }

        \Yii::$app->cache->delete($this->getPositionCacheKey());
        return $positionStart + $i + 1 >= \count($fullUrlList);
    }

    /**
     * @return int
     */
    public function getNewSitesCount(): int
    {
        return $this->newSitesCount;
    }

    /**
     * @return int
     */
    public function getNewPagesCount(): int
    {
        return $this->newPagesCount;
    }

    /**
     * @return int
     */
    public function getImagesSavedCount(): int
    {
        return $this->imagesSavedCount;
    }

    /**
     * @return int
     */
    public function getExistingPagesCount(): int
    {
        return $this->existingPagesCount;
    }

    /**
     * @return bool
     */
    public function isPageCountLimitReached(): bool
    {
        return $this->pageCountLimitReached;
    }

    /**
     * @return string
     */
    private function getPositionCacheKey(): string
    {
        return __METHOD__ . '::' . $this->domain;
    }
}