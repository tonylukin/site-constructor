<?php

namespace app\services\siteCreator;

use app\services\googleParser\SiteListGetter;

class Creator
{
    private $queries = [
        'beautiful buildings'
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
        foreach ($this->queries as $query) {
            $urlList = $this->siteListGetter->getSearchList($query);
            foreach ($urlList as $url) {
                $content = $this->parser->parseSiteContent($url);
                if ($content === null) {
                    continue;
                }
                // todo create db records for content parsed
            }
        }
    }
}