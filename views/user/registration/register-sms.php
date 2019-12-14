<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use Da\User\Form\RegistrationForm;
use Da\User\Model\User;
use Da\User\Module;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use yii\widgets\MaskedInput;

/**
 * @var yii\web\View $this
 * @var RegistrationForm $model
 * @var User $user
 * @var Module $module
 */

$this->title = Yii::t('usuario', 'Sign up');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
    $('body').on('click', '#send-confirm-code', function () {
        var input = $(this).parents('.input-group').find('input');
        var inputWrapper = input.parents('.form-group')
        $('.valid-feedback').remove()
        if (input.val()!=='') {
            $.post('". Url::to(['/user/registration/send-confirmation-code'])."', {phone: input.val()}, function(response) {
                if (response.success === true) {
                    console.log(response.message)
                    input.removeClass('is-invalid').addClass('is-valid')
                    
                    $('.input-group-append').after('<div class=\'valid-feedback\'>'+response.message+'</div>')
                    $('.invalid-feedback').text('')
                } else {
                    console.log(response.errors)
                    input.removeClass('is-valid').addClass('is-invalid')
                    $('.invalid-feedback').text(response.errors.phone[0])
                }
            })
        } else {
            input.removeClass('is-valid').addClass('is-invalid')
        }
    })
")
?>

<?php $form = ActiveForm::begin(
    [
        'id' => $model->formName(),
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
    ]
); ?>

<?= $form->field($model, 'username', [
    'template' => '{label}
<div class="input-group mb-3">
  {input}
  <div class="input-group-append">
       <button class="btn btn-default" id="send-confirm-code" type="button">Отправить код</button>
  </div>
  {error}
</div>',
])->widget(MaskedInput::class, [
    'mask' => '+\9\9\8(\99)999-99-99',
    'options' => [
        'class' => 'form-control',
    ]]) ?>


<?php if ($module->generatePasswords === false): ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
<?php endif ?>
<?= $form->field($model, 'confirmCode')->textInput(['autofocus' => true]) ?>
<?php if ($module->enableGdprCompliance): ?>
    <?= $form->field($model, 'gdpr_consent',[
            'options' => ['class' => 'icheck-primary']
    ])->checkbox(['value' => 1]) ?>
<?php endif ?>

<?= Html::submitButton(Yii::t('usuario', 'Sign up'), ['class' => 'btn btn-success btn-block']) ?>

<?php ActiveForm::end(); ?>
<hr>

<p class="mb-2 text-center">
    <?= Html::a(Yii::t('usuario', 'Already registered? Sign in!'), ['/user/security/login']) ?>
</p>

