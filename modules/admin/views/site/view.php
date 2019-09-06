<?php

use app\models\Site;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Site */

$this->title = $model->domain;
$this->params['breadcrumbs'][] = ['label' => 'Sites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="site-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'search_word',
            'domain',
            'body_class',
            'slug',
            'created_at',
            [
                'attribute' => 'Search words',
                'format' => 'html',
                'value' => function (Site $model) {
                    return \implode(
                        '<br>',
                        ArrayHelper::getColumn($model->siteSearchWordLogs, 'search_word')
                    );
                },
            ],
        ],
    ]) ?>

</div>
