<?php

namespace app\services\siteView;

use app\models\Page;

class LatestPosts
{
    private const CACHE_DURATION = 3600 * 24 * 7; // 7 days
    private const POST_COUNT = 20;

    /**
     * @param Page|null $page
     * @return Page[]
     */
    public function get(?Page $page = null): array
    {
        $query = Page::find()
            ->with('images')
            ->published()
            ->orderBy(['page.publish_date' => SORT_DESC])
            ->byHost()
            ->limit(self::POST_COUNT)
        ;
        if ($page !== null) {
            $query->andWhere('page.id != :id', [':id' => $page->id]);
        }
//        if (!YII_DEBUG) {
//            $query->cache(self::CACHE_DURATION);
//        }
        return $query->all();
    }
}