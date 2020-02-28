<?php

namespace app\modules\admin\controllers;

use app\models\Site;
use app\services\googleParser\SearchWordsGenerator;
use app\services\siteCreator\CreatorConfig;

class GenerateWordsController extends Controller
{
    /**
     * @var SearchWordsGenerator
     */
    private $searchWordsGenerator;
    /**
     * @var CreatorConfig
     */
    private $creatorConfig;

    /**
     * GenerateWordsController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param SearchWordsGenerator $searchWordsGenerator
     * @param CreatorConfig $creatorConfig
     */
    public function __construct(
        $id,
        $module,
        $config = [],
        SearchWordsGenerator $searchWordsGenerator,
        CreatorConfig $creatorConfig
    )
    {
        $this->searchWordsGenerator = $searchWordsGenerator;
        $this->creatorConfig = $creatorConfig;

        parent::__construct($id, $module, $config);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionIndex(): string
    {
        $result = [];
        $sites = Site::find()->indexBy('id')->all();

        if (\Yii::$app->request->isPost) {
            if (\Yii::$app->request->post('addToQueue') !== null) {
                $words = \Yii::$app->request->post('words');
                if ($words) {
                    $configs = "\n" . $words;
                    \file_put_contents($this->creatorConfig->getFilePath(), $configs, FILE_APPEND);
                }
            } else {
                $domains = \Yii::$app->request->post('site');
                $queries = \Yii::$app->request->post('query');
                $counts = \Yii::$app->request->post('count');
                foreach ($domains as $i => $siteId) {
                    $words = $this->searchWordsGenerator->generate($queries[$i], $counts[$i] ?: 10);
                    $result[$sites[$siteId]->domain] = $words;
                }
            }
        }

        return $this->render('index', [
            'sites' => $sites,
            'wordsBySite' => $result,
        ]);
    }
}
