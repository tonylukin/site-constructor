<?php

namespace app\services\googleParser;

use app\services\siteCreator\Parser;
use GuzzleHttp\Client;

class SiteListGetter
{
    private const REQUEST_DELAY = 10;
    private const GOOGLE_URL = 'https://www.google.co.uk/search?q={query}&num={number}&start={start}';

    private const SITE_BLACK_LIST = [
        'facebook.com',
        'vk.com',
        'tripadvisor.ru',
        'tripadvisor.com',
        'youtube.com',
        'studfiles.net',
        'amazon.co.uk'
    ];

    /**
     * @var int
     */
    private $searchResultNumber = 100;

    /**
     * @var int
     */
    private $maxResultPage = 10;

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
     * @param string $queryWords
     * @param int $currentPage
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSearchList(string $queryWords, int $currentPage = 1): array
    {
        $siteUrlsList = \Yii::$app->cache->get($this->getResultsCacheKey($queryWords));
        if ($siteUrlsList !== false) {
            \Yii::warning("Get url list from cache for '{$queryWords}'", Parser::LOGGER_PREFIX);
            return $siteUrlsList;
        }

        $googleUrl = \str_replace(
            ['{query}', '{number}', '{start}'],
            [\urlencode($queryWords), $this->searchResultNumber, $this->searchResultNumber * ($currentPage - 1)],
            self::GOOGLE_URL
        );
        try {
            $response = $this->client->request('GET', $googleUrl);

        } catch (\Throwable $e) {
            \Yii::error(__METHOD__ . ": Guzzle exception: {$e->getMessage()}", Parser::LOGGER_PREFIX);
            return [];
        }

        $body = (string)$response->getBody();
        $urlPattern = '/<div class=\"kCrYT\">([\s\S]*?)\&amp\;/i';
        \preg_match_all($urlPattern, $body, $urlResults);

        if (empty($urlResults)) {
            \Yii::error("No links found for '{$googleUrl}'", Parser::LOGGER_PREFIX);
            return [];
        }

        $siteUrlsList = [];
        foreach ($urlResults[0] as $urlResult) {
            if (!\preg_match('/\/url\?q=(.*?)\&amp\;/', $urlResult, $matches)) {
                continue;
            }

            $url = $matches[1];
            if (!$this->siteNotInTheBlackList($url)) {
                continue;
            }
            $siteUrlsList[] = $url;
        }

        $siteUrlsList = \array_unique($siteUrlsList);
        \Yii::warning('Url list contains ' . \count($siteUrlsList) . ' items', Parser::LOGGER_PREFIX);

        // empty page
        if (\count($siteUrlsList) === 1) {
            $this->maxResultPage = $currentPage;
            \Yii::error("Max pages for '{$queryWords}' is {$currentPage}");
            return [];
        }

        if ($currentPage >= $this->maxResultPage) {
            \Yii::warning("Max page reached: {$this->maxResultPage}", Parser::LOGGER_PREFIX);
            return $siteUrlsList;
        }

        \sleep(self::REQUEST_DELAY);
        $siteUrlsList = \array_merge(
            $siteUrlsList,
            $this->getSearchList($queryWords, $currentPage + 1)
        );

        if ($currentPage === 1 && !empty($siteUrlsList)) {
            if (\Yii::$app->cache->set($this->getResultsCacheKey($queryWords), $siteUrlsList, 3600 * 24 * 7)) {
                \Yii::warning('Total url count ' . \count($siteUrlsList) . ' stored in cache', Parser::LOGGER_PREFIX);
            } else {
                \Yii::warning('Could not store result array', Parser::LOGGER_PREFIX);
            }
        }
        return $siteUrlsList;
    }

    /**
     * @param int $searchResultNumber
     * @return SiteListGetter
     */
    public function setSearchResultNumber(int $searchResultNumber): SiteListGetter
    {
        $this->searchResultNumber = $searchResultNumber;
        return $this;
    }

    /**
     * @param int $maxResultPage
     * @return SiteListGetter
     */
    public function setMaxResultPage(int $maxResultPage): SiteListGetter
    {
        $this->maxResultPage = $maxResultPage;
        return $this;
    }

    /**
     * @param string $queryWords
     * @return string
     */
    private function getResultsCacheKey(string $queryWords): string
    {
        return __METHOD__ . '::' . $queryWords;
    }

    /**
     * @param string $siteUrl
     * @return bool
     */
    private function siteNotInTheBlackList(string $siteUrl): bool
    {
        foreach (self::SITE_BLACK_LIST as $value) {
            if (\strpos($siteUrl, $value) !== false) {
                return false;
            }
        }

        return true;
    }
}