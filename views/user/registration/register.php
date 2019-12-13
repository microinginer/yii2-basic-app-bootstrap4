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
 * @var \Da\User\Form\RegistrationForm $model
 * @var \Da\User\Model\User $user
 * @var \Da\User\Module $module
 */

$this->title = Yii::t('usuario', 'Sign up');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(
    [
        'id' => $model->formName(),
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
    ]
); ?>

<?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

<?= $form->field($model, 'username') ?>

<?php if ($module->generatePasswords === false): ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
<?php endif ?>

<?php if ($module->enableGdprCompliance): ?>
    <?= $form->field($model, 'gdpr_consent')->checkbox(['value' => 1]) ?>
<?php endif ?>

<?= Html::submitButton(Yii::t('usuario', 'Sign up'), ['class' => 'btn btn-success btn-block']) ?>

<?php ActiveForm::end(); ?>
<hr>

<p class="mb-2 text-center">
    <?= Html::a(Yii::t('usuario', 'Already registered? Sign in!'), ['/user/security/login']) ?>
</p>
