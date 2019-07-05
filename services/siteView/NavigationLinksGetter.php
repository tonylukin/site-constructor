<?php

namespace app\services\siteView;

class NavigationLinksGetter
{
    private const CACHE_DURATION = 3600 * 24 * 7; // 7 days

    /**
     * @param int $count
     * @return array
     * @throws \yii\db\Exception
     */
    public function get(int $count = 10): array
    {
        $sql = <<<SQL
SELECT `p`.`title`, `p`.`url` FROM `page` `p`
INNER JOIN `site` `s` ON s.id = p.site_id
WHERE s.domain = :domain AND p.publish_date <= NOW()
LIMIT {$count}
SQL;
        $query = \Yii::$app
            ->db
            ->createCommand($sql, [
                ':domain' => \Yii::$app->request->hostName
            ])
        ;
        if (!YII_DEBUG) {
            $query->cache(self::CACHE_DURATION);
        }
        return $query->queryAll();
    }
}