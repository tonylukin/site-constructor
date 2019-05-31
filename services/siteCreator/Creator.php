<?php

namespace app\services\siteCreator;

use app\models\Page;
use app\models\Site;
use app\services\googleParser\SiteListGetter;

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

    public function create(): void
    {
        foreach ($this->queries as $domain => $query) {
            $urlList = $this->siteListGetter->getSearchList($query);
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                $site = new Site();
                $site->search_word = $query;
                $site->domain = $domain;
                if (!$site->save()) {
                    throw new \Exception('Error on saving site: ' . \implode('; ', $site->getErrorSummary(true)));
                }

                foreach ($urlList as $url) {
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
                    if (!$page->save()) {
                        \Yii::error(\implode('; ', $page->getErrorSummary(true)), 'parser');
                    }
                    break; // todo delete
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
}