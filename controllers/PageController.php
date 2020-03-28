<?php

namespace app\controllers;

use app\services\siteView\NavigationLinksGetter;
use app\services\siteView\PageFinder;
use app\services\siteView\SiteMapGenerator;
use app\services\Statistic\VisitCounter;
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
     * @var VisitCounter
     */
    private $visitCounter;

    /**
     * PageController constructor.
     * @param string $id
     * @param Module $module
     * @param array $config
     * @param PageFinder $pageFinder
     * @param NavigationLinksGetter $navigationLinksGetter
     * @param SiteMapGenerator $siteMapGenerator
     * @param VisitCounter $visitCounter
     */
    public function __construct(
        string $id,
        Module $module,
        array $config = [],
        PageFinder $pageFinder,
        NavigationLinksGetter $navigationLinksGetter,
        SiteMapGenerator $siteMapGenerator,
        VisitCounter $visitCounter
    )
    {
        $this->pageFinder = $pageFinder;
        $this->navigationLinksGetter = $navigationLinksGetter;
        $this->siteMapGenerator = $siteMapGenerator;
        $this->visitCounter = $visitCounter;
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
            throw new NotFoundHttpException("Page '{$url}' not found for host: " . \Yii::$app->request->hostName);
        }
        \Yii::$app->params['page'] = $page;
        $this->visitCounter->hit(\Yii::$app->request->hostName, $url === '' ? '/' : $url, \Yii::$app->request->userIP);

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
        \Yii::$app->response->format = Response::FORMAT_RAW;
        \header('Content-Type: text/xml');
        return \file_get_contents($this->siteMapGenerator->getFileName(\Yii::$app->request->hostName));
    }
}
