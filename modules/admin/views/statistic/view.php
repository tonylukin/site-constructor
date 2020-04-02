<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Statistic */

$this->title = $model->host . ': #' . $model->id;
$this->params['breadcrumbs'][] = [
    'label' => 'Statistic',
    'url' => ['index'],
];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="page-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'host',
            'url',
            'ip',
            [
                'attribute' => 'additional_info',
                'value' => function (\app\models\Statistic $model, $widget) {
                    if ($model->additional_info) {
                        return \implode('<br>', $model->additional_info);
                    }

                    return null;
                },
            ],
            'created_at:date',
        ],
    ]) ?>

</div>
