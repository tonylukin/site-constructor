<?php

namespace app\services\siteView;

use app\models\Page;

class PageFinder
{
    /**
     * @param string $url
     * @return Page|null
     */
    public function findByUrl(string $url): ?Page
    {
        $page = Page::find()->byHost();
        if ($url === '') {
            $page->orderBy(['id' => SORT_ASC]);
        } else {
            $page->andWhere(['page.url' => $url]);
        }
        return $page->one();
    }
}