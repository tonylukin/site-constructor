<?php

namespace app\services\siteCreator;

use GuzzleHttp\Client;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Exceptions\UnknownChildTypeException;

class Parser
{
    private const CONTENT_MIN_LENGTH = 1000;
    private const DELETE_SELECTOR = 'form, ul, aside, header, footer, script, noscript, nav';
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
        $domContent = $this->findContentElement($dom);
        if ($domContent === null) {
            \Yii::error('DOM content is null', self::LOGGER_PREFIX);
            return null;
        }
        $collection = $domContent->find(self::DELETE_SELECTOR);
        if ($collection->count() > 0) {
            /** @var Dom\HtmlNode $item */
            foreach ($collection as $item) {
                $item->delete();
            }
        }
        // remove links
        $collection = $domContent->find('a');
        if ($collection->count() > 0) {
            foreach ($collection as $item) {
                $item->setAttribute('href', '#');
            }
        }

        try {
            $html = $domContent->innerHtml();

        } catch (UnknownChildTypeException $e) {
            \Yii::error("UnknownChildTypeException: {$e->getMessage()}", self::LOGGER_PREFIX);
            return null;
        }

        /** @var Dom\HtmlNode $domTitle */
        $domTitle = $dom->find('head title')[0];
        $this->title = $domTitle !== null ? $domTitle->text() : '';

        $this->imageParser->parse($domContent, $url);

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

    /**
     * @param Dom $dom
     * @return Dom\HtmlNode|null
     */
    private function findContentElement(\PHPHtmlParser\Dom $dom): ?Dom\HtmlNode
    {
        $content = $dom->find('article')[0];
        if ($content !== null) {
            return $content;
        }

        $content = $dom->find('#content')[0];
        if ($content !== null) {
            return $content;
        }

        $content = $dom->find('.content')[0];
        if ($content !== null) {
            return $content;
        }

        $content = $dom->find('main')[0];
        if ($content !== null) {
            return $content;
        }

        return $dom->find('body')[0];
    }
}