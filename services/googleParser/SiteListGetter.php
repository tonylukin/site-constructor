<?php

namespace app\services\googleParser;

use GuzzleHttp\Client;

class SiteListGetter
{
    private const GOOGLE_URL = 'https://www.google.co.uk/search?q={query}&num=100';

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
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSearchList(string $queryWords): array
    {
        $googleUrl = \str_replace('{query}', \urlencode($queryWords), self::GOOGLE_URL);
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
            if (\strpos($url, 'youtube.com') !== false) {
                continue;
            }
            $siteUrlsList[] = $url;
        }

        $siteUrlsList = \array_unique($siteUrlsList);
        return $siteUrlsList;
    }
}