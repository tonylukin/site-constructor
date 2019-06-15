<?php

namespace app\services\siteView;

use app\models\Page;

class PageFinder
{
    private const CACHE_DURATION = 3600 * 24 * 7; // 7 days

    /**
     * @param string $url
     * @return Page|null
     */
    public function findByUrl(string $url): ?Page
    {
        $query = Page::find()->with('site')->byHost();
        if ($url === '') {
            $query->orderBy(['id' => SORT_ASC]);
        } else {
            $query->andWhere(['page.url' => $url]);
        }
        if (!YII_DEBUG) {
            $query->cache(self::CACHE_DURATION);
        }
        return $query->one();
    }
}