<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="error-page">
    <h2 class="headline text-danger"><?= $exception->statusCode?></h2>

    <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-danger"></i> Оопс! Что-то пошло не так.</h3>

        <div class="alert alert-danger">
            <?= nl2br(Html::encode($message)) ?>
        </div>

    </div>
</div>
