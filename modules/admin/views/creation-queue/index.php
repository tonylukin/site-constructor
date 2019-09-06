<?php
/**
 * @var CreatorConfig $creatorConfig
 */

use app\services\siteCreator\CreatorConfig;
use yii\helpers\Html;

$this->title = 'Creation Queue';
?>

<h1><?= $this->title ?></h1>

<table class="table table-bordered">
    <tr>
        <th>Domain</th>
        <th>Search query</th>
    </tr>
    <?php foreach ($creatorConfig->getConfigs() as $config) { ?>
        <tr>
            <td><?= $config[CreatorConfig::DOMAIN] ?></td>
            <td><?= $config[CreatorConfig::SEARCH_QUERY] ?></td>
        </tr>
    <?php } ?>
</table>

<?= Html::beginForm() ?>
<p>
    <?= Html::textarea('config', \file_get_contents($creatorConfig->getFilePath()), [
        'class' => 'form-control',
        'rows' => '15',
    ]) ?>
</p>
<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
<?= Html::endForm() ?>
