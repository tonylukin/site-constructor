<?php

namespace app\modules\admin;

class Module extends \yii\base\Module
{
    public $layout = '@app/modules/admin/views/layouts/default';

    public function init(): void
    {
        parent::init();
        // initialize the module with the configuration loaded from config.php
        \Yii::configure($this, require __DIR__ . '/config.php');
    }
}
