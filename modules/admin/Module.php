<?php

namespace app\modules\admin;

use yii\web\HttpException;

class Module extends \yii\base\Module
{
    public $layout = '@app/modules/admin/views/layouts/default';

    public function init()
    {
        if (\Yii::$app->request->hostName !== 'a.wowtoknow.com') {
            throw new HttpException(401, 'Get the fuck out here');
        }
        parent::init();
    }
}
