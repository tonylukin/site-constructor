<?php

use yii\grid\ActionColumn;
use app\models\Site;
use yii\grid\Column;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sites';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php //echo Html::a('Create Site', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'search_word',
            [
                'class' => \yii\grid\DataColumn::class,
                'attribute' => 'domain',
                'format' => function (string $value) {
                    return "<a href='//{$value}' target='_blank'>{$value}</a>";
                },
            ],
            'target_language',
//            'body_class',
//            'slug',
            [
                'class' => Column::class,
                'header' => 'Page count',
                'content' => function (Site $model, $key, $index, $column) {
                    return $model->getPages()->published()->count()
                        . Html::tag('b', ' [' . $model->getPages()->count() . ']');
                },
            ],
            [
                'attribute' => 'Search words',
                'format' => 'html',
                'value' => static function (Site $model) {
                    return Html::tag(
                        'div',
                        \implode(
                            '<br>',
                            ArrayHelper::getColumn($model->siteSearchWordLogs, 'search_word')
                        ),
                        ['class' => 'js-spoiler', 'data' => ['shown-rows' => '3']]
                    );
                },
            ],
            'created_at:date',

            ['class' => ActionColumn::class, 'template' => '{view} {update}'],
        ],
        'rowOptions' => function(\app\models\Site $model, $key, $index, $grid) {
            return $model->getPages()->published()->count() !== $model->getPages()->count() ? [] : ['class' => 'danger'];
        },
    ]) ?>

    <?php Pjax::end(); ?>

</div>
