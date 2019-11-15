<?php

namespace app\modules\admin\controllers;

use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\HttpException;

class Controller extends \yii\web\Controller
{
    /**
     * @var bool
     */
    public $enableCsrfValidation = false;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    if (\Yii::$app->user->isGuest) {
                        return $this->redirect(Url::to(['/admin/login/login']));
                    }
                    throw new HttpException(403, 'You are not allowed to access this page');
                }
            ],
        ];
    }
}
