<?php
/**
 * @var array $lines
 * @var array $linesYiiLog
 * @var array $linesYiiLogNoLinksFound
 * @var array $createSiteProcesses
 * @var array $pids
 */

$this->title = 'Creation log';
?>
<h1>Logs</h1>
<ul class="nav nav-tabs">
    <li role="presentation" class="active"><a data-toggle="tab" href="#processes">Create site processes</a></li>
    <li role="presentation"><a data-toggle="tab" href="#parse-log">Parse log</a></li>
    <li role="presentation"><a data-toggle="tab" href="#app-log">Yii app log</a></li>
    <li role="presentation"><a data-toggle="tab" href="#app-log-no-links">No links found</a></li>
</ul>
<br>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="processes">
        <form action="" method="post">
            <div class="form-group">
                <button name="startMysql" type="submit" class="btn btn-success" value="1">Start MySQL</button>
            </div>

            <table class="table table-striped">
                <?php foreach ($createSiteProcesses as $i => $line) { ?>
                    <tr>
                        <td><?= $line ?></td>
                        <td>
                            <button name="pidToKill" type="submit" class="btn btn-danger" value="<?= $pids[$i] ?>">Kill <?= $pids[$i] ?></button>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </form>
    </div>
    <div role="tabpanel" class="tab-pane" id="parse-log">
        <table class="table table-striped">
            <?php foreach ($lines as $line) { ?>
                <tr>
                    <td><?= \htmlspecialchars($line) ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div role="tabpanel" class="tab-pane" id="app-log">
        <table class="table table-striped">
            <?php foreach ($linesYiiLog as $line) { ?>
                <tr>
                    <td><?= \htmlspecialchars($line) ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div role="tabpanel" class="tab-pane" id="app-log-no-links">
        <table class="table table-striped">
            <?php foreach ($linesYiiLogNoLinksFound as $line) { ?>
                <tr>
                    <td><?= $line ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>