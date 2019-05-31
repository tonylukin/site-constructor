<?php

use GuzzleHttp\Client;
use app\services\googleParser\SiteListGetter;
use app\services\siteCreator\Parser;
use app\services\siteCreator\Creator;

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
];
