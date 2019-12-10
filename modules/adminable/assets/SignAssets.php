<?php

namespace app\modules\adminable\assets;

use yii\web\AssetBundle;

/**
 * Class SignAssets
 * @package app\modules\adminable\assets
 */
class SignAssets extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@vendor/almasaeed2010';

    /**
     * @var array
     */
    public $css = [
        'adminlte/plugins/iCheck/all.css',
    ];

    /**
     * @var array
     */
    public $js = [
        'adminlte/plugins/iCheck/icheck.min.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'app\modules\adminable\assets\AdminLteAsset',
    ];
}
