<?php

namespace app\controllers;

use app\services\siteView\NavigationLinksGetter;
use app\services\siteView\PageFinder;
use yii\base\Module;
use yii\web\NotFoundHttpException;

class PageController extends Controller
{
    /**
     * @var PageFinder
     */
    private $pageFinder;

    /**
     * @var NavigationLinksGetter
     */
    private $navigationLinksGetter;

    /**
     * PageController constructor.
     * @param string $id
     * @param Module $module
     * @param array $config
     * @param PageFinder $pageFinder
     * @param NavigationLinksGetter $navigationLinksGetter
     */
    public function __construct(
        string $id,
        Module $module,
        array $config = [],
        PageFinder $pageFinder,
        NavigationLinksGetter $navigationLinksGetter
    )
    {
        $this->pageFinder = $pageFinder;
        $this->navigationLinksGetter = $navigationLinksGetter;
        parent::__construct($id, $module, $config);
    }

    /**
     * @param string $url
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex(string $url): string
    {
        $page = $this->pageFinder->findByUrl($url);
        if ($page === null) {
            throw new NotFoundHttpException("Page '{$url}' not found");
        }
        \Yii::$app->params['page'] = $page;

        return $this->render('index', [
            'page' => $page,
            'navigationLinksGetter' => $this->navigationLinksGetter
        ]);
    }
}
