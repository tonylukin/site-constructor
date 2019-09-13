<?php

use app\services\googleParser\SiteListGetter;
use app\services\siteCreator\ContentAnalyzer;
use app\services\siteCreator\CreatingProcessManager;
use app\services\siteCreator\Creator;
use app\services\siteCreator\CreatorConfig;
use app\services\siteCreator\ImageParser;
use app\services\siteCreator\Parser;
use app\services\siteView\LatestPosts;
use app\services\siteView\NavigationLinksGetter;
use app\services\siteView\PageFinder;
use app\services\siteView\SiteMapGenerator;
use GuzzleHttp\Client;

return [
    Client::class => [
        'class' => Client::class,
    ],
    SiteListGetter::class => [
        'class' => SiteListGetter::class,
    ],
    Parser::class => [
        'class' => Parser::class,
    ],
    Creator::class => [
        'class' => Creator::class,
    ],
    PageFinder::class => [
        'class' => PageFinder::class,
    ],
    NavigationLinksGetter::class => [
        'class' => NavigationLinksGetter::class,
    ],
    LatestPosts::class => [
        'class' => LatestPosts::class,
    ],
    ImageParser::class => [
        'class' => ImageParser::class,
    ],
    ContentAnalyzer::class => [
        'class' => ContentAnalyzer::class,
    ],
    CreatorConfig::class => [
        'class' => CreatorConfig::class,
    ],
    CreatingProcessManager::class => [
        'class' => CreatingProcessManager::class,
    ],
    SiteMapGenerator::class => [
        'class' => SiteMapGenerator::class,
    ],
];
