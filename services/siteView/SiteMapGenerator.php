<?php

namespace app\services\siteView;

use app\models\Site;

class SiteMapGenerator
{
    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function generate(): array
    {
        $sites = Site::find()->all();
        $generatedSitemaps = [];

        foreach ($sites as $site) {
            // todo add limit/offset
            $sql = <<<SQL
SELECT CONCAT('http://{$site->domain}/', `p`.`url`) AS `loc`, `p`.`publish_date` AS `lastmod`
FROM `page` `p`
WHERE `p`.`site_id` = :siteId AND `p`.`publish_date` <= NOW()
SQL;
            $pageData = \Yii::$app
                ->db
                ->createCommand($sql, [
                    ':siteId' => $site->id
                ])
                ->queryAll()
            ;
            if ($this->saveSiteMap($pageData, $site->domain)) {
                $generatedSitemaps[] = $this->getFileName($site->domain);
            } else {
                \Yii::error("Could not create sitemap for {$site->domain}");
            }
        }

        return $generatedSitemaps;
    }

    /**
     * @param string $hostName
     * @return string
     */
    public function getFileName(string $hostName): string
    {
        return $this->getFilePath() . DIRECTORY_SEPARATOR . "{$hostName}.xml";
    }

    /**
     * @return string
     */
    private function getFilePath(): string
    {
        return \implode(DIRECTORY_SEPARATOR, [
            \Yii::$app->runtimePath,
            'sitemaps',
        ]);
    }

    /**
     * @param array $pageData
     * @param string $hostName
     * @return bool
     */
    private function saveSiteMap(array $pageData, string $hostName): bool
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>');

        foreach ($pageData as $row) {
            $url = $xml->addChild('url');
            $url->addChild('loc', $row['loc']);
            $url->addChild('lastmod', $row['lastmod']);
        }
        $filePath = $this->getFilePath();
        if (!\file_exists($filePath) && !\mkdir($filePath) && !\is_dir($filePath)) {
            throw new \RuntimeException(\sprintf('Directory "%s" was not created', $filePath));
        }
        return (bool)$xml->saveXML($this->getFileName($hostName));
    }
}
