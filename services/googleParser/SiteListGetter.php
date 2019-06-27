<?php

namespace app\services\googleParser;

use GuzzleHttp\Client;

class SiteListGetter
{
    private const GOOGLE_URL = 'https://www.google.co.uk/search?q={query}&num={number}&start={start}';

    private const SITE_BLACK_LIST = [
        'facebook.com',
        'vk.com',
        'tripadvisor.ru',
        'tripadvisor.com',
        'youtube.com',
        'studfiles.net',
    ];

    /**
     * @var int
     */
    private $searchResultNumber = 100;

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
     * @param int $startPage
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSearchList(string $queryWords, int $startPage = 1): array
    {
        $googleUrl = \str_replace(
            ['{query}', '{number}', '{start}'],
            [\urlencode($queryWords), $this->searchResultNumber, $this->searchResultNumber * ($startPage - 1)],
            self::GOOGLE_URL
        );
        try {
            $response = $this->client->request('GET', $googleUrl);
        } catch (\Throwable $e) {
            \Yii::error(__METHOD__ . ": Guzzle exception: {$e->getMessage()}");
            return [];
        }

        $body = (string)$response->getBody();
        $urlPattern = '/<div class=\"jfp3ef\">([\s\S]*?)\&amp\;/i';
        \preg_match_all($urlPattern, $body, $urlResults);

        if (empty($urlResults)) {
            \Yii::error("No links found for '{$googleUrl}'");
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

        return \array_merge(
            $siteUrlsList,
            $startPage === 10 ? [] : $this->getSearchList($queryWords, $startPage + 1)
        );
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