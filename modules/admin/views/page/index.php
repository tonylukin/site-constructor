<?php

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $pagesFilterForm \app\modules\admin\models\PagesFilterForm
 * @var array $sitesOptions
 */

$this->title = 'Pages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Page', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $pagesFilterForm,
        'columns' => [
            'id',
            'title',
//            'keywords',
//            'description',
//            'content:ntext',
            //'url:url',
            'source_url:url',
            [
                'class' => \yii\grid\DataColumn::class,
                'header' => 'Site',
                'attribute' => 'site_id',
                'content' => function (\app\models\Page $model, $key, $index, $column) {
                    return Html::a($model->site->domain, $model->site->domain, ['target' => '_blank']);
                },
                'filter' => $sitesOptions,
                'value' => $pagesFilterForm->site_id,
            ],
            'created_at:date',
            'publish_date:date',

            ['class' => ActionColumn::class],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
