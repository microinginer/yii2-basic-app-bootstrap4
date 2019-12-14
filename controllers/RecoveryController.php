<?php


namespace app\controllers;


use app\models\user\RecoverySmsModel;
use app\models\user\SendConfirmationCodeModel;
use app\models\user\UserSmsConfirm;
use Da\User\Event\FormEvent;
use Da\User\Factory\MailFactory;
use Da\User\Form\RecoveryForm;
use Da\User\Service\PasswordRecoveryService;
use Da\User\Validator\AjaxRequestModelValidator;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class RecoveryController
 * @package app\controllers
 */
class RecoveryController extends \Da\User\Controller\RecoveryController
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['request', 'reset', 'request-sms','send-confirmation-code'],
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'send-confirmation-code' => ['POST'],
                ],
            ],
            'content' => [
                'class' => ContentNegotiator::class,
                'only' => ['send-confirmation-code'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * Displays / handles user password recovery request.
     *
     * @return string
     *
     * @throws InvalidConfigException
     * @throws InvalidParamException
     * @throws NotFoundHttpException
     */
    public function actionRequestSms()
    {
        if (!$this->module->allowPasswordRecovery) {
            throw new NotFoundHttpException();
        }

        /** @var RecoverySmsModel $form */
        $form = $this->make(RecoverySmsModel::class, []);

        $event = $this->make(FormEvent::class, [$form]);

        $this->make(AjaxRequestModelValidator::class, [$form])->validate();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $this->trigger(FormEvent::EVENT_BEFORE_REQUEST, $event);


            if ($form->handle()) {
                $this->trigger(FormEvent::EVENT_AFTER_REQUEST, $event);

                Yii::$app
                    ->getSession()
                    ->setFlash('success', 'Пароль успешно изменён');

                return $this->redirect(['/user/login']);
            }

        }

        return $this->render('request-sms', ['model' => $form]);
    }


    public function actionSendConfirmationCode()
    {
        if (!$this->module->allowPasswordRecovery) {
            throw new NotFoundHttpException();
        }

        $model = new SendConfirmationCodeModel([
            'code' => (string)mt_rand(100001, 999999),
            'type' => UserSmsConfirm::TYPE_FORGOT,
        ]);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->handle()) {
            return [
                'success' => true,
                'message' => 'Код подтверждения успешно отправлен',
            ];
        }

        return [
            'success' => false,
            'errors' => $model->getErrors(),
        ];
    }
}
