<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use Da\User\Model\User;
use yii\bootstrap4\Nav;
use yii\helpers\Html;
use yii\widgets\Menu;

/** @var User $user */
$user = Yii::$app->user->identity;
$module = Yii::$app->getModule('user');
$networksVisible = count(Yii::$app->authClientCollection->clients) > 0;

?>

<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title">
            <?= Html::img(
                $user->profile->getAvatarUrl(24),
                [
                    'class' => 'img-rounded',
                    'alt' => $user->username,
                ]
            ) ?>
            <?= $user->username ?>
        </h3>
    </div>
    <div class="card-body">
        <?= Nav::widget(
            [
                'options' => [
                    'class' => 'nav flex-column nav-pills',
                ],
                'items' => [
                    ['label' => Yii::t('usuario', 'Profile'), 'url' => ['/user/settings/profile']],
                    ['label' => Yii::t('usuario', 'Account'), 'url' => ['/user/settings/account']],
                    ['label' => Yii::t('usuario', 'Privacy'),
                        'url' => ['/user/settings/privacy'],
                        'visible' => $module->enableGdprCompliance
                    ],
                    [
                        'label' => Yii::t('usuario', 'Networks'),
                        'url' => ['/user/settings/networks'],
                        'visible' => $networksVisible,
                    ],
                ],
            ]
        ) ?>
    </div>
</div>
