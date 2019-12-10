<?php
/** @var $this yii\web\View */

$this->title = 'Панель управления';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="adminable-default-index">
    <h1><?= $this->context->action->uniqueId ?></h1>
    <p>
        This is the view content for action "<?= $this->context->action->id ?>".
        The action belongs to the controller "<?= get_class($this->context) ?>"
        in the "<?= $this->context->module->id ?>" module.
    </p>
</div>
