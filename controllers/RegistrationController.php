<?php


namespace app\controllers;


use app\components\UserRegisterSmsService;
use app\models\user\RegistrationSmsModel;
use app\models\user\SendConfirmationCodeModel;
use app\models\user\UserSmsConfirm;
use Da\User\Event\FormEvent;
use Da\User\Factory\MailFactory;
use Da\User\Form\RegistrationForm;
use Da\User\Model\User;
use Da\User\Service\UserConfirmationService;
use Da\User\Service\UserRegisterService;
use Da\User\Validator\AjaxRequestModelValidator;
use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class RegistrationController extends \Da\User\Controller\RegistrationController
{
    public $enableSmsRegistration = true;

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
                        'actions' => ['register', 'connect', 'register-sms'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['confirm', 'resend', 'send-confirmation-code'],
                        'roles' => ['?', '@'],
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
     * {@inheritdoc}
     */
    public function actionRegisterSms()
    {
        if (!$this->enableSmsRegistration) {
            throw new NotFoundHttpException();
        }
        /** @var RegistrationSmsModel $form */
        $form = $this->make(RegistrationSmsModel::class);
        /** @var FormEvent $event */
        $event = $this->make(FormEvent::class, [$form]);

        $this->make(AjaxRequestModelValidator::class, [$form])->validate();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $form->clearUsername();

            $this->trigger(FormEvent::EVENT_BEFORE_REGISTER, $event);

            /** @var User $user */

            // Create a temporay $user so we can get the attributes, then get
            // the intersection between the $form fields  and the $user fields.
            $user = $this->make(User::class, []);
            $fields = array_intersect_key($form->attributes, $user->attributes);

            // Becomes password_hash
            $fields['password'] = $form['password'];

            $user = $this->make(User::class, [], $fields);

            $user->setScenario('register-sms');

            if ($this->make(UserRegisterSmsService::class, [$user])->run()) {
                $userConfirmationService = $this->make(UserConfirmationService::class, [$user]);
                $userConfirmationService->run();

                Yii::$app->session->setFlash('info', Yii::t('usuario', 'Your account has been created'));
                $user->getIsConfirmed();
                $this->trigger(FormEvent::EVENT_AFTER_REGISTER, $event);

                Yii::$app
                    ->getUser()
                    ->login($user);

                return $this->render(
                    '/shared/message',
                    [
                        'title' => Yii::t('usuario', 'Your account has been created'),
                        'module' => $this->module,
                    ]
                );
            }

            $form->addErrors($user->getErrors());

            Yii::$app->session->setFlash('danger', Yii::t('usuario', 'User could not be registered.'));
        }

        return $this->render('register-sms', ['model' => $form, 'module' => $this->module]);
    }

    public function actionSendConfirmationCode()
    {
        if (!$this->enableSmsRegistration) {
            throw new NotFoundHttpException();
        }

        $model = new SendConfirmationCodeModel([
            'code' => (string)mt_rand(100001, 999999),
            'type' => UserSmsConfirm::TYPE_REGISTER,
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
