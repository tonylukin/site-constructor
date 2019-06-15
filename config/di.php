<?php

use GuzzleHttp\Client;
use app\services\googleParser\SiteListGetter;
use app\services\siteCreator\Parser;
use app\services\siteCreator\Creator;
use app\services\siteView\PageFinder;
use app\services\siteView\NavigationLinksGetter;
use app\services\siteView\LatestPosts;

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
        'class' => Creator::class
    ],
    PageFinder::class => [
        'class' => PageFinder::class
    ],
    NavigationLinksGetter::class => [
        'class' => NavigationLinksGetter::class
    ],
    LatestPosts::class => [
        'class' => LatestPosts::class
    ]
];
