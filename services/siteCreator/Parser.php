<?php

namespace app\services\siteCreator;

use GuzzleHttp\Client;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Exceptions\UnknownChildTypeException;

class Parser
{
    private const CONTENT_MIN_LENGTH = 500;
    public const LOGGER_PREFIX = 'parser';

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
     * @var ImageParser
     */
    private $imageParser;

    /**
     * @var ContentAnalyzer
     */
    private $contentAnalyzer;

    /**
     * SiteListGetter constructor.
     * @param Client $client
     * @param ImageParser $imageParser
     * @param ContentAnalyzer $contentAnalyzer
     */
    public function __construct(
        Client $client,
        ImageParser $imageParser,
        ContentAnalyzer $contentAnalyzer
    )
    {
        $this->client = $client;
        $this->imageParser = $imageParser;
        $this->contentAnalyzer = $contentAnalyzer;
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
            \Yii::error(__METHOD__ . ": Guzzle exception: {$e->getMessage()}", self::LOGGER_PREFIX);
            return null;
        }

        $body = (string)$response->getBody();

        $dom = new Dom();
        $dom->load($body);
        /** @var Dom\HtmlNode $domBody */
        $domBody = $dom->find('body')[0];
        if ($domBody === null) {
            \Yii::error('DOM body is null', self::LOGGER_PREFIX);
            return null;
        }

        try {
            $html = $domBody->innerHtml();
        } catch (UnknownChildTypeException $e) {
            \Yii::error("UnknownChildTypeException: {$e->getMessage()}", self::LOGGER_PREFIX);
            return null;
        }

        /** @var Dom\HtmlNode $domTitle */
        $domTitle = $dom->find('head title')[0];
        $this->title = $domTitle !== null ? $domTitle->text() : '';

        $this->imageParser->parse($domBody, $url);

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

        $html = \strip_tags($html);

        $html = $this->contentAnalyzer->cleanFromLongWords($html);
        if (\strlen(\trim($html)) < self::CONTENT_MIN_LENGTH || !$this->contentAnalyzer->checkContentIsEnglish($html)) {
            return null;
        }

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

    /**
     * @return ImageParser
     */
    public function getImageParser(): ImageParser
    {
        return $this->imageParser;
    }
}