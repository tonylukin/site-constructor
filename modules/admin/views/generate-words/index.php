<?php
/**
 * @var \app\models\Site[] $sites
 * @var array $wordsBySite
 */

use yii\helpers\Html;

$this->title = 'Generate words';
$sites = \yii\helpers\ArrayHelper::map($sites, 'id', 'domain');
?>

<h1><?= $this->title ?></h1>

<?= Html::beginForm() ?>
<div class="form-group">

    <div class="form-group">
        <div class="row">
            <div class="col-sm-3">
                <?= Html::dropDownList('site[]', null, $sites, ['class' => 'form-control']) ?>
            </div>

            <div class="col-sm-3">
                <?= Html::input('text', 'query[]', null, ['class' => 'form-control', 'placeholder' => 'Query']) ?>
            </div>

            <div class="col-sm-3">
                <?= Html::input('text', 'count[]', null, ['class' => 'form-control', 'placeholder' => 'Count']) ?>
            </div>
        </div>
    </div>

    <?= Html::submitButton('Generate', ['class' => 'btn btn-primary']) ?>
</div>

<?php
$wordsBySiteOutput = [];
$count = 0;
foreach ($wordsBySite as $domain => $queryWords) {
    foreach ($queryWords as $queryWord) {
        $wordsBySiteOutput[] = "{$domain}, {$queryWord}";
        $count++;
    }
}
?>
<div class="alert alert-success">Generated <?= $count ?> items total</div>
<?php if (!empty($wordsBySiteOutput)) { ?>
    <div class="form-group">
        <?= Html::submitButton('Add all to queue', [
            'class' => 'btn btn-success',
            'name' => 'addToQueue',
        ]) ?>
    </div>
<?php } ?>
<div class="form-group">
    <?= Html::textarea('words', \implode("\n", $wordsBySiteOutput), ['class' => 'form-control', 'rows' => 30]) ?>
</div>
<?= Html::endForm() ?>