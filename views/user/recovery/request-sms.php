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
use yii\helpers\Url;
use yii\widgets\MaskedInput;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \Da\User\Form\RecoveryForm $model
 */

$this->title = Yii::t('usuario', 'Recover your password');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
    $('body').on('click', '#send-confirm-code', function () {
        var input = $(this).parents('.input-group').find('input');
        var inputWrapper = input.parents('.form-group')
        $('.valid-feedback').remove()
        if (input.val()!=='') {
            $.post('". Url::to(['/user/recovery/send-confirmation-code'])."', {phone: input.val()}, function(response) {
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

<?= $form->field($model, 'phone', [
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
<?= $form->field($model, 'newPassword')->passwordInput() ?>
<?= $form->field($model, 'confirmCode')->textInput(['autofocus' => true]) ?>
<?= Html::submitButton(Yii::t('usuario', 'Continue'), ['class' => 'btn btn-primary btn-block']) ?><br>

<?php ActiveForm::end(); ?>
