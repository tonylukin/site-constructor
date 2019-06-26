<?php

namespace app\services\siteCreator;

use app\models\Image;
use app\models\Page;
use app\models\Site;
use app\services\googleParser\SiteListGetter;
use yii\helpers\Inflector;

class Creator
{
    private $queries = [
        'beautiful-buildings.loc' => 'beautiful buildings',
    ];

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
    private $imagesSavedCount = 0;

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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(): void
    {
        // todo add counters for new sites and pages
        foreach ($this->queries as $domain => $query) {
            $urlList = $this->siteListGetter->getSearchList($query);
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                $site = Site::find()->byWordOrDomain($query, $domain)->one();
                if ($site === null) {
                    $site = new Site();
                    $site->search_word = $query;
                    $site->domain = $domain;

                    if (!$site->save()) {
                        throw new \Exception('Error on saving site: ' . \implode('; ', $site->getErrorSummary(true)));
                    }
                    $this->newSitesCount++;
                }

                foreach ($urlList as $url) {
                    $page = Page::find()->bySourceUrl($url)->one();
                    if ($page !== null) {
                        continue;
                    }

                    $this->parser->getImageParser()->setDomain($domain);
                    $content = $this->parser->parseSiteContent($url);
                    if ($content === null) {
                        continue;
                    }
                    $page = new Page();
                    $page->title = $this->parser->getTitle();
                    $page->keywords = $this->parser->getKeywords();
                    $page->description = $this->parser->getDescription();
                    $page->site_id = $site->id;
                    $page->content = $content;
                    $page->source_url = $url;
                    $page->url = Inflector::slug($page->title);

                    if ($page->save()) {
                        $images = $this->parser->getImageParser()->getImages();
                        if (!empty($images)) {
                            $image = new Image();
                            $image->page_id = $page->id;
                            $image->source = $images[0][ImageParser::IMAGE_FILENAME_KEY];
                            $image->original_url = $images[0][ImageParser::IMAGE_URL_KEY];
                            if ($image->save()) {
                                $this->imagesSavedCount++;
                            } else {
                                \Yii::error(\implode('; ', $image->getErrorSummary(true)), 'parser');
                            }
                        }
                        $this->newPagesCount++;
                    } else {
                        \Yii::error(\implode('; ', $page->getErrorSummary(true)), 'parser');
                    }
                }
                $transaction->commit();

            } catch (\Throwable $e) {
                \Yii::error($e->getMessage(), 'parser');
                if ($transaction->isActive) {
                    $transaction->rollBack();
                }
            }
        }
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
}