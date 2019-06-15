<?php

namespace app\widgets;

use app\services\siteView\LatestPosts;

class LatestPostsWidget extends \yii\bootstrap\Widget
{
    /**
     * @var LatestPosts
     */
    private $latestPosts;

    /**
     * LatestPosts constructor.
     * @param array $config
     * @param LatestPosts $navigationLinksGetter
     */
    public function __construct($config = [], LatestPosts $navigationLinksGetter)
    {
        parent::__construct($config);
        $this->latestPosts = $navigationLinksGetter;
    }

    /**
     * @return string
     */
    public function run(): string
    {
        $pages = $this->latestPosts->get(\Yii::$app->params['page'] ?? null);
        return $this->render('@views/widgets/latest-posts', [
            'pages' => $pages
        ]);
    }
}