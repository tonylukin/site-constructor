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
        $query = Page::find()
            ->with('images', 'site', 'pageLinks', 'pageLinks.refPage', 'pageLinks.refPage.site')
            ->published()
            ->active()
            ->byHost()
        ;
        if ($url === '') {
            $query->orderBy(['page.publish_date' => SORT_DESC]);
        } else {
            $query->andWhere(['page.url' => $url]);
        }
//        if (!YII_DEBUG) {
//            $query->cache(self::CACHE_DURATION);
//        }
        return $query->one();
    }
}