<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;
use yii\bootstrap\BootstrapAsset;

class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/admin/main.js',
    ];
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
    ];
}
