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
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $keywords;

    /**
     * @var string
     */
    private $description;

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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function parseSiteContent(string $url): ?string
    {
        try {
            $response = $this->client->request('GET', $url);
        } catch (\Throwable $e) {
            \Yii::error(__METHOD__ . ": Guzzle exception: {$e->getMessage()}", 'parser');
            return null;
        }

        $body = (string)$response->getBody();

        $dom = new Dom();
        $dom->load($body);
        /** @var Dom\HtmlNode $domBody */
        $domBody = $dom->find('body')[0];
        if ($domBody === null) {
            \Yii::error('DOM body is null', 'parser');
            return null;
        }
        /** @var Dom\HtmlNode $domTitle */
        $domTitle = $dom->find('head title')[0];
        $this->title = $domTitle->text();

        $img = $domBody->find('img');
        $img = $img[0] ?? null;
        if ($img !== null) {
            /** @var Dom\HtmlNode $img */
            $imageUrl = $img->getAttribute('src');
            // todo save to db
        }

        /** @var Dom\HtmlNode $domDescription */
        $domDescription = $dom->find('meta[name="description"]')[0];
        if ($domDescription !== null) {
            $this->description = \substr($domDescription->getAttribute('content'), 0, 255);
        }

        /** @var Dom\HtmlNode $domKeywords */
        $domKeywords = $dom->find('meta[name="keywords"]')[0];
        if ($domKeywords !== null) {
            $this->keywords = \substr($domKeywords->getAttribute('content'), 0, 255);
        }

        try {
            $html = $domBody->innerHtml();
        } catch (UnknownChildTypeException $e) {
            \Yii::error("UnknownChildTypeException: {$e->getMessage()}", 'parser');
            return null;
        }

        $html = \strip_tags($html);
        return \trim($html);
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
}