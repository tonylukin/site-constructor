<?php

namespace app\modules\admin\controllers;

use app\services\siteCreator\CreatorConfig;

class CreationQueueController extends Controller
{
    /**
     * @var CreatorConfig
     */
    private $creatorConfig;

    /**
     * CreationQueueController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param CreatorConfig $creatorConfig
     */
    public function __construct(
        $id,
        $module,
        $config = [],
        CreatorConfig $creatorConfig
    )
    {
        $this->creatorConfig = $creatorConfig;

        parent::__construct($id, $module, $config);
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        if (\Yii::$app->request->isPost) {
            $configs = \Yii::$app->request->post()['config'];
            \file_put_contents($this->creatorConfig->getFilePath(), $configs);
        }

        return $this->render('index', [
            'creatorConfig' => $this->creatorConfig,
        ]);
    }

    /**
     * @return string
     */
    public function actionCreationLogView(): string
    {
        \exec('tail -100 /var/log/create-site.log', $lines);

        return $this->render('creation-log-view', [
            'lines' => $lines,
        ]);
    }
}
