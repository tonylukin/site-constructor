<?php

namespace app\commands;

use app\models\Site;

class CreateLinksController extends Controller
{
    /**
     * @param int $linkPerSite
     * @throws \yii\db\Exception
     */
    public function actionIndex(int $linkPerSite = 3): void
    {
        $sql = <<<SQL
SELECT s.id AS siteId, GROUP_CONCAT(DISTINCT p.id) AS pageIds, COUNT(*) AS count
FROM page_link pl
INNER JOIN page p ON p.id = pl.page_id
INNER JOIN site s ON s.id = p.site_id
WHERE pl.ref_page_id IS NOT NULL
GROUP BY s.id
SQL;
        $data = \Yii::$app->db->createCommand($sql)->queryAll();

        $excludedSiteIds = [];
        $excludedPageIds = [];
        $linksFreeSpacesForSiteId = [];
        foreach ($data as $row) {
            if ($row['count'] >= $linkPerSite) {
                $excludedSiteIds[] = $row['siteId'];
                continue;
            }

            $excludedPageIds[] = \explode(',', $row['pageIds']);
            $linksFreeSpacesForSiteId[$row['siteId']] = $linkPerSite - $row['count'];
        }
        $excludedPageIds = \array_merge(...$excludedPageIds);

        $excludedSql = [];
        $excludedSql[] = \count($excludedSiteIds) === 0 ? '' : (' s.id NOT IN (' . \implode($excludedSiteIds) . ') ');
        $excludedSql[] = \count($excludedPageIds) === 0 ? '' : (' p.id NOT IN (' . \implode($excludedPageIds) . ') ');
        $excludedSql = \count($excludedSql) === 0 ? '' : (' WHERE ' . \implode('AND', $excludedSql));

        $sql = <<<SQL
SELECT temp.siteId, GROUP_CONCAT(pageId) AS pageIds FROM
(SELECT s.id AS siteId, p.id AS pageId
FROM page p
INNER JOIN site s ON s.id = p.site_id
{$excludedSql}
ORDER BY RAND()
LIMIT 1000) AS temp
GROUP BY temp.siteId
SQL;

    }
}
