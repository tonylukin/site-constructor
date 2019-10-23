<?php

namespace app\services\siteView;

class NavigationLinksGetter
{
    /**
     * @param int $count
     * @return array
     * @throws \yii\db\Exception
     */
    public function get(int $count = 10): array
    {
        $sql = <<<SQL
SELECT `p`.`title`, `p`.`url` FROM `page` `p`
INNER JOIN `site` `s` ON `s`.`id` = `p`.`site_id`
WHERE `s`.`domain` = :domain AND `p`.`publish_date` <= NOW() AND `p`.`active` = 1
ORDER BY `p`.`publish_date` DESC
LIMIT {$count}
SQL;
        $query = \Yii::$app
            ->db
            ->createCommand($sql, [
                ':domain' => \Yii::$app->request->hostName
            ])
        ;
        return $query->queryAll();
    }
}