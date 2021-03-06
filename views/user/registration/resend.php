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
 * @var \Da\User\Form\ResendForm $model
 */

$this->title = Yii::t('usuario', 'Request new confirmation message');
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

<?= Html::submitButton(Yii::t('usuario', 'Continue'), ['class' => 'btn btn-primary btn-block']) ?><br>

<?php ActiveForm::end(); ?>
<p class="mb-2 text-center">
    <?= Html::a(Yii::t('usuario', 'Already registered? Sign in!'), ['/user/security/login']) ?>
</p>
