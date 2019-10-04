<?php
/**
 * @var array $lines
 * @var array $linesYiiLog
 */

$this->title = 'Creation log';
?>
<h1>Logs</h1>
<ul class="nav nav-tabs">
    <li role="presentation" class="active"><a data-toggle="tab" href="#parse-log">Parse log</a></li>
    <li role="presentation"><a data-toggle="tab" href="#app-log">Yii app log</a></li>
</ul>
<br>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="parse-log">
        <table class="table table-striped">
            <?php foreach ($lines as $line) { ?>
                <tr>
                    <td><?= $line ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div role="tabpanel" class="tab-pane" id="app-log">
        <table class="table table-striped">
            <?php foreach ($linesYiiLog as $line) { ?>
                <tr>
                    <td><?= $line ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>