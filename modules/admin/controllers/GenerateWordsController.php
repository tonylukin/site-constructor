<?php

namespace app\modules\admin\controllers;

use app\models\Site;
use app\services\googleParser\SearchWordsGenerator;

class GenerateWordsController extends Controller
{
    /**
     * @var SearchWordsGenerator
     */
    private $searchWordsGenerator;

    /**
     * GenerateWordsController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param SearchWordsGenerator $searchWordsGenerator
     */
    public function __construct($id, $module, $config = [], SearchWordsGenerator $searchWordsGenerator)
    {
        $this->searchWordsGenerator = $searchWordsGenerator;

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
            $domains = \Yii::$app->request->post('site');
            $queries = \Yii::$app->request->post('query');
            $counts = \Yii::$app->request->post('count');
            foreach ($domains as $i => $siteId) {
                $words = $this->searchWordsGenerator->generate($queries[$i], $counts[$i] ?: 10);
                $result[$sites[$siteId]->domain] = $words;
            }
        }

        return $this->render('index', [
            'sites' => $sites,
            'wordsBySite' => $result,
        ]);
    }
}
