<?php

namespace app\modules\adminable;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\web\ErrorHandler;

/**
 * adminable module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\adminable\controllers';
    public $layout = 'main';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

    }

    /**
     * @inheritDoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application) {
            Yii::$app->errorHandler->errorAction='/adminable/default/error';
        }
    }
}
