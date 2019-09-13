<?php

namespace app\controllers;

use app\services\siteView\NavigationLinksGetter;
use app\services\siteView\PageFinder;
use app\services\siteView\SiteMapGenerator;
use yii\base\Module;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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
     * @var SiteMapGenerator
     */
    private $siteMapGenerator;

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
        NavigationLinksGetter $navigationLinksGetter,
        SiteMapGenerator $siteMapGenerator
    )
    {
        $this->pageFinder = $pageFinder;
        $this->navigationLinksGetter = $navigationLinksGetter;
        $this->siteMapGenerator = $siteMapGenerator;
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

    /**
     * @return string
     */
    public function actionSitemap(): string
    {
        \Yii::$app->response->format = Response::FORMAT_XML;
        return \file_get_contents($this->siteMapGenerator->getFileName(\Yii::$app->request->hostName));
    }
}
