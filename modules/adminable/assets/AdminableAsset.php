<?php

namespace app\modules\adminable\assets;


use app\assets\AdminLteAsset;
use yii\web\AssetBundle;

/**
 * Class AdminableAsset
 * @package app\modules\adminable\assets
 */
class AdminableAsset extends AssetBundle
{
    public $depends = [
        AdminLteAsset::class,
    ];
}
