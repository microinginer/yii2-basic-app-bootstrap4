<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use Da\User\Widget\ConnectWidget;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \Da\User\Form\LoginForm $model
 * @var \Da\User\Module $module
 */

$this->title = Yii::t('usuario', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
    'validateOnType' => false,
    'validateOnChange' => false,
]) ?>

<?= $form->field(
    $model,
    'login',
    ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']]
) ?>

<?= $form
    ->field(
        $model,
        'password',
        ['inputOptions' => ['class' => 'form-control', 'tabindex' => '2']]
    )
    ->passwordInput()
    ->label(
        Yii::t('usuario', 'Password')
        . ($module->allowPasswordRecovery ?
            ' (' . Html::a(
                Yii::t('usuario', 'Forgot password?'),
                ['/user/recovery/request'],
                ['tabindex' => '5']
            )
            . ')' : '')
    ) ?>
<div class="row">
    <div class="col-8">
        <div class="icheck-primary">
            <?= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '4']) ?>
        </div>
    </div>
    <div class="col-4">
        <?= Html::submitButton(
            Yii::t('usuario', 'Sign in'),
            ['class' => 'btn btn-primary btn-block', 'tabindex' => '3']
        ) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
<hr>
<?php if ($module->enableEmailConfirmation): ?>
    <p class="mb-1">
        <?= Html::a(
            Yii::t('usuario', 'Didn\'t receive confirmation message?'),
            ['/user/registration/resend']
        ) ?>
    </p>
<?php endif ?>
<?php if ($module->enableRegistration): ?>
    <p class="mb-2">
        <?= Html::a(Yii::t('usuario', 'Don\'t have an account? Sign up!'), ['/user/registration/register']) ?>
    </p>
<?php endif ?>
<?= ConnectWidget::widget(
    [
        'baseAuthUrl' => ['/user/security/auth'],
    ]
) ?>
