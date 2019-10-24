<?php

namespace app\commands;

use app\models\PageLink;

class CreateLinksController extends Controller
{
    /**
     * @param int $linkPerSite
     * @throws \yii\db\Exception
     */
    public function actionIndex(int $linkPerSite = 3): void
    {
        // define existing links
        $sql = <<<SQL
SELECT s.id AS siteId, GROUP_CONCAT(DISTINCT p.id) AS pageIds, COUNT(*) AS count
FROM page_link pl
INNER JOIN page p ON p.id = pl.page_id
INNER JOIN site s ON s.id = p.site_id
WHERE pl.ref_page_id IS NOT NULL AND s.active = 1 AND p.active = 1
GROUP BY s.id
SQL;
        $data = \Yii::$app->db->createCommand($sql)->queryAll();

        // make exclude conditions for existing links
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
        if (\count($excludedPageIds) > 0) {
            $excludedPageIds = \array_merge(...$excludedPageIds);
        }

        $excludedSql = ['s.active = 1 AND p.active = 1'];
        if (\count($excludedSiteIds) !== 0) {
            $excludedSql[] = 's.id NOT IN (' . \implode(',', $excludedSiteIds) . ')';
        }
        if (\count($excludedPageIds) !== 0) {
            $excludedSql[] = 'p.id NOT IN (' . \implode(',', $excludedPageIds) . ')';
        }
        $excludedSql = \count($excludedSql) === 0 ? '' : (' WHERE ' . \implode(' AND ', $excludedSql));

        // define which pages can be recipients
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
        $data = \Yii::$app->db->createCommand($sql)->queryAll();
        $linksData = [];
        foreach ($data as $row) {
            $linksCount = $linksFreeSpacesForSiteId[$row['siteId']] ?? $linkPerSite;
            $pageIds = \array_slice(\explode(',', $row['pageIds']), 0, $linksCount);
            $linksData[$row['siteId']] = $pageIds;
        }

        // define link text
        $sql = <<<SQL
SELECT id AS site_id, search_word FROM	site
UNION ALL
SELECT site_id, search_word FROM site_search_word_log
SQL;
        $data = \Yii::$app->db->createCommand($sql)->queryAll();
        $linkTexts = [];
        foreach ($data as $row) {
            if (!\array_key_exists($row['site_id'], $linkTexts)) {
                $linkTexts[$row['site_id']] = [null];
            }
            $linkTexts[$row['site_id']][] = $row['search_word'];
        }

        // create links
        foreach ($linksData as $siteId => $pageIds) {
            // define referral pages
            $limit = \count($pageIds);
            $sql = <<<SQL
SELECT p.id FROM page p
LEFT JOIN page_link pl ON pl.ref_page_id = p.id
WHERE p.site_id != :siteId AND pl.ref_page_id IS NULL
ORDER BY RAND()
LIMIT {$limit}
SQL;
            $refPageIds = \Yii::$app->db->createCommand($sql, ['siteId' => $siteId])->queryColumn();

            foreach ($pageIds as $i => $pageId) {
                if (!\array_key_exists($i, $refPageIds)) {
                    continue;
                }

                $pageLink = new PageLink();
                $pageLink->page_id = $pageId;
                $pageLink->ref_page_id = $refPageIds[$i];
                $pageLink->text = $linkTexts[$siteId][\array_rand($linkTexts[$siteId])];
                if (!$pageLink->save()) {
                    $this->stdout('Errors: ' . \print_r($pageLink->errors, true));
                }
            }
        }
    }
}
