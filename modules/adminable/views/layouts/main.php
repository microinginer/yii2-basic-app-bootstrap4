<?php

/* @var $this View */

/* @var $content string */

use app\models\user\User;
use app\modules\adminable\assets\AdminableAsset;
use app\modules\adminable\assets\AdminLteAsset;
use app\modules\adminable\widgets\MenuWidget;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\web\View;

$adminLte = AdminLteAsset::register($this);
AdminableAsset::register($this);

/** @var User $user */
$user = Yii::$app->getUser()->getIdentity();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition sidebar-mini ">
<?php $this->beginBody() ?>
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark navbar-gray-dark">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
            <!--li class="nav-item d-none d-sm-inline-block">
                <a href="index3.html" class="nav-link">Home</a>
            </li-->
        </ul>

        <form class="form-inline ml-3">
            <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="<?= $user->profile->getAvatarUrl()?>"
                         class="user-image img-circle elevation-2" alt="User Image">
                    <span class="d-none d-md-inline"><?= $user->getCorrectName()?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <!-- User image -->
                    <li class="user-header bg-dark">
                        <img src="<?= $user->profile->getAvatarUrl()?>" class="img-circle elevation-2"
                             alt="User Image">

                        <p>
                            <?= $user->getCorrectName()?>
                            <small><?= Yii::$app->getFormatter()->asDatetime($user->confirmed_at,'php:d.m.Y H:i')?></small>
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <a href="#" class="btn btn-default btn-flat">Мой профиль</a>
                        <a href="<?= Url::to(['/user/logout'])?>" data-method="post" class="btn btn-default btn-flat float-right">Выйти</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="<?= Url::to(['/']) ?>" class="brand-link">
            <img src="<?= $adminLte->baseUrl ?>/dist/img/AdminLTELogo.png" alt="AdminLTE Logo"
                 class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light"><?= Yii::$app->name ?></span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="<?= $user->profile->getAvatarUrl()?>" class="img-circle elevation-2"
                         alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block"><?= $user->getCorrectName()?></a>
                </div>
            </div>
            <nav class="mt-2">
                <?= MenuWidget::widget([
                    'encodeLabels' => false,
                    'activateParents' => true,
                    'options' => ['class' => 'nav nav-pills nav-sidebar flex-column', 'data' => ['widget' => 'treeview', 'accordion' => 'false'], 'role' => 'menu'],
                    'items' => [
                        [
                            'label' => Html::tag('i', null, ['class' => 'nav-icon fas fa-tachometer-alt']) . ' ' . Html::tag('p', 'Панель управления'),
                            'url' => ['/adminable/default/index'],
                        ],
                        [
                            'label' => Html::tag('i', null, ['class' => 'nav-icon fas fa-users']) . ' ' . Html::tag('p', 'Пользователи <i class="right fas fa-angle-left"></i>'),
                            'url' => '#',
                            'options' => ['class' => 'nav-item has-treeview'],
                            'dropdownOptions' => ['class' => 'nav nav-treeview'],
                            'items' => [
                                [
                                    'label' => Html::tag('i', null, ['class' => 'far fa-circle nav-icon']) . ' ' . Html::tag('p', 'Пользователей'),
                                    'url' => ['/user/admin/index'],
                                ],
                                [
                                    'label' => Html::tag('i', null, ['class' => 'far fa-circle nav-icon']) . ' ' . Html::tag('p', 'Роли'),
                                    'url' => ['/user/role/index'],
                                ],
                                [
                                    'label' => Html::tag('i', null, ['class' => 'far fa-circle nav-icon']) . ' ' . Html::tag('p', 'Права'),
                                    'url' => ['/user/permission/index'],
                                ],
                            ],
                        ],
                    ],
                ]); ?>
            </nav>
        </div>
    </aside>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"><?= $this->title ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <?= Breadcrumbs::widget([
                            'options' => ['class' => 'breadcrumb float-sm-right'],
                            'homeLink' => ['label' => Yii::$app->name, 'url' => ['/adminable']],
                            'links' => (!empty($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [])
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <?= $content ?>
            </div>
        </div>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; <?= date('Y')?> <a href="http://adminlte.io">AdminLTE.io</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 3.0.1
        </div>
    </footer>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
