<?php

namespace app\services\siteCreator;

use app\models\Image;
use app\models\Page;
use app\models\Site;
use app\services\googleParser\SiteListGetter;
use yii\helpers\Inflector;

class Creator
{
    private const MAX_PAGES_PER_EXEC = 50;

    /**
     * @var SiteListGetter
     */
    private $siteListGetter;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var int
     */
    private $newSitesCount = 0;

    /**
     * @var int
     */
    private $newPagesCount = 0;

    /**
     * @var int
     */
    private $updatedPagesCount = 0;

    /**
     * @var int
     */
    private $imagesSavedCount = 0;

    /**
     * @var string
     */
    private $domain;

    /**
     * Creator constructor.
     * @param SiteListGetter $siteListGetter
     * @param Parser $parser
     */
    public function __construct(
        SiteListGetter $siteListGetter,
        Parser $parser
    )
    {
        $this->siteListGetter = $siteListGetter;
        $this->parser = $parser;
    }

    /**
     * @param string $domain
     * @param string $query
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(string $domain, string $query): void
    {
        $this->domain = $domain;
        $urlList = $this->siteListGetter->getSearchList($query);
        if (empty($urlList)) {
            \Yii::warning('No URLs were found', Parser::LOGGER_PREFIX);
            return;
        }
        $urlList = \array_splice($urlList, \Yii::$app->cache->get($this->getPositionCacheKey()) ?: 0);

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
        }

        $pages = Page::find()->bySourceUrls($urlList)->indexBy('source_url')->all();
        $pageCount = 0;
        foreach ($urlList as $i => $url) {
            if ($pageCount >= self::MAX_PAGES_PER_EXEC) {
                \Yii::info('Max page per exec reached: ' . self::MAX_PAGES_PER_EXEC, Parser::LOGGER_PREFIX);
                return;
            }

            if (!\Yii::$app->cache->set($this->getPositionCacheKey(), $i)) {
                \Yii::warning("Could not save position: {$i}", Parser::LOGGER_PREFIX);
            }
            \Yii::info("Processing url: '{$url}' in set: #{$i}", Parser::LOGGER_PREFIX);

            $page = $pages[$url] ?? null;
            if ($page === null) {
                $page = new Page();
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

                $page->title = $this->parser->getTitle();
                $page->keywords = $this->parser->getKeywords();
                $page->description = $this->parser->getDescription();
                $page->site_id = $site->id;
                $page->content = $content;
                $page->source_url = $url;
                $page->url = Inflector::slug($page->title);
                $page->setPageIndex($i);

            } catch (\Throwable $e) {
                \Yii::error("Parse content error [{$url}]: {$e->getMessage()}", Parser::LOGGER_PREFIX);
                continue;
            }

            $transaction = \Yii::$app->db->beginTransaction();
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
                    } else {
                        $this->updatedPagesCount++;
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
            $pageCount++;
        }

        \Yii::$app->cache->delete($this->getPositionCacheKey());
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
    public function getUpdatedPagesCount(): int
    {
        return $this->updatedPagesCount;
    }

    /**
     * @return string
     */
    private function getPositionCacheKey(): string
    {
        return __METHOD__ . '::' . $this->domain;
    }
}