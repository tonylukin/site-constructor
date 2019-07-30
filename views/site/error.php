<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */

/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1 class="entry-title"><?= Html::encode($this->title) ?></h1>

    <div class="entry-content">
        <div class="alert alert-danger">
            <?= nl2br(Html::encode($message)) ?>
        </div>

        The above error occurred while the Web server was processing your request.
        <br>
        Please contact us if you think this is a server error. Thank you.
    </div>

</div>
