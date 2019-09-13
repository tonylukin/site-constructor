<?php
/**
 * @var array $lines
 */

$this->title = 'Creation log';
?>

<table class="table table-striped">
    <?php foreach ($lines as $line) { ?>
        <tr>
            <td><?= $line ?></td>
        </tr>
    <?php } ?>
</table>