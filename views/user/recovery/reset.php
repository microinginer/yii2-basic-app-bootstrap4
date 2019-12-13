<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \Da\User\Form\RecoveryForm $model
 */

$this->title = Yii::t('usuario', 'Reset your password');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(
    [
        'id' => $model->formName(),
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
    ]
); ?>

<?= $form->field($model, 'password')->passwordInput() ?>

<?= Html::submitButton(Yii::t('usuario', 'Finish'), ['class' => 'btn btn-success btn-block']) ?><br>

<?php ActiveForm::end(); ?>
