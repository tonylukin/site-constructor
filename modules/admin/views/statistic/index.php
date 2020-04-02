<?php

use app\models\Site;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/** @var \app\modules\admin\models\StatisticFilterForm $statisticFilterForm */

$this->title = 'Statistic';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?php $options = Site::getOptions() ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $statisticFilterForm,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => \yii\grid\DataColumn::class,
                'header' => 'Host',
                'attribute' => 'host',
                'content' => function (\app\models\Statistic $model, $key, $index, $column) {
                    return Html::a($model->host, $model->host, ['target' => '_blank']);
                },
                'filter' => \array_combine($options, $options),
                'value' => $statisticFilterForm->host,
            ],
            'url',
            'ip',
            [
                'class' => \yii\grid\DataColumn::class,
                'header' => 'Info',
                'attribute' => 'additional_info',
                'content' => function (\app\models\Statistic $model, $key, $index, $column) {
                    if ($model->additional_info) {
                        return \implode('<br>', $model->additional_info);
                    }

                    return null;
                },
            ],
            'created_at:date',

            [
                'class' => ActionColumn::class,
                'template' => '{view}',
            ],
        ],
    ]) ?>

    <?php Pjax::end(); ?>

</div>