<?php

namespace app\widgets;


use app\models\Page;
use app\services\siteView\RelatedPosts;

class RelatedPostsWidget extends \yii\bootstrap\Widget
{
    /**
     * @var RelatedPosts
     */
    private $relatedPosts;

    /**
     * RelatedPostsWidget constructor.
     * @param array $config
     * @param RelatedPosts $navigationLinksGetter
     */
    public function __construct($config = [], RelatedPosts $navigationLinksGetter)
    {
        parent::__construct($config);
        $this->relatedPosts = $navigationLinksGetter;
    }

    /**
     * @return string
     */
    public function run(): string
    {
        /** @var Page|null $page */
        $page = \Yii::$app->params['page'] ?? null;
        if ($page === null) {
            return '';
        }

        $pages = $this->relatedPosts->getByPage($page);
        return $this->render('@views/widgets/related-posts', [
            'pages' => $pages
        ]);
    }
}