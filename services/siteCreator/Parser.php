<?php

namespace app\services\siteCreator;

use GuzzleHttp\Client;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Exceptions\UnknownChildTypeException;

class Parser
{
    /**
     * @var Client
     */
    private $client;

    /**
     * SiteListGetter constructor.
     * @param Client $client
     */
    public function __construct(
        Client $client
    )
    {
        $this->client = $client;
    }

    /**
     * @param string $url
     * @return string|null
     */
    public function parseSiteContent(string $url): ?string
    {
        try {
            $response = $this->client->request('GET', $url);
        } catch (\Throwable $e) {
            \Yii::error(__METHOD__ . ": Guzzle exception: {$e->getMessage()}");
            return null;
        }

        $body = (string)$response->getBody();

        $dom = new Dom();
        $dom->load($body);
        /** @var Dom\HtmlNode $domBody */
        $domBody = $dom->find('body')[0];
        if ($domBody === null) {
            \Yii::error('DOM body is null');
            return null;
        }

        try {
            $html = $domBody->innerHtml();
        } catch (UnknownChildTypeException $e) {
            \Yii::error("UnknownChildTypeException: {$e->getMessage()}");
            return null;
        }

        $html = \strip_tags($html);
        return $html;
    }
}