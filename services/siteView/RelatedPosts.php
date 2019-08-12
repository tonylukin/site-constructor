<?php

namespace app\services\siteView;

use app\models\Page;

class RelatedPosts
{
    private const CACHE_DURATION = 3600 * 24 * 7; // 7 days
    private const POST_COUNT = 20;

    /**
     * @param int $id
     * @return array
     */
    private function getRelatedIds(int $id): array
    {
        $ids = [];
        $step = 3;
        $relatedId = $id;
        while (($relatedId -= $step) > 0) {
            $ids[] = $relatedId;
        }

        $relatedId = $id;
        while (($relatedId += $step) < 1000) {
            $ids[] = $relatedId;
        }

        return $ids;
    }

    /**
     * @param Page $page
     * @return Page[]
     */
    public function getByPage(Page $page): array
    {
        $query = Page::find()
            ->with('images')
            ->published()
            ->byHost()
            ->andWhere(['page.id' => $this->getRelatedIds((int) $page->id)])
            ->orderBy(['page.publish_date' => SORT_DESC])
            ->limit(self::POST_COUNT)
        ;
//        if (!YII_DEBUG) {
//            $query->cache(self::CACHE_DURATION);
//        }
        return $query->all();
    }
}