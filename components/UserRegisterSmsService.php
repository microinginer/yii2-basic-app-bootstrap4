<?php


namespace app\components;


use Da\User\Contracts\ServiceInterface;
use Da\User\Event\UserEvent;
use Da\User\Helper\SecurityHelper;
use Da\User\Model\User;
use Da\User\Traits\MailAwareTrait;
use Da\User\Traits\ModuleAwareTrait;
use Exception;
use Yii;
use yii\base\InvalidCallException;

/**
 * Class UserRegisterSmsService
 * @package app\components
 */
class UserRegisterSmsService implements ServiceInterface
{
    use ModuleAwareTrait;
    use MailAwareTrait;

    protected $model;
    protected $securityHelper;
    protected $mailService;

    public function __construct(User $model, SecurityHelper $securityHelper)
    {
        $this->model = $model;
        $this->securityHelper = $securityHelper;
    }

    public function run()
    {
        $model = $this->model;

        if ($model->getIsNewRecord() === false) {
            throw new InvalidCallException('Cannot register user from an existing one.');
        }

        $transaction = $model::getDb()->beginTransaction();

        try {
            $model->confirmed_at = $this->getModule()->enableEmailConfirmation ? null : time();
            $model->email = null;

            $model->password = $this->getModule()->generatePasswords
                ? $this->securityHelper->generatePassword(8)
                : $model->password;

            $event = $this->make(UserEvent::class, [$model]);
            $model->trigger(UserEvent::EVENT_BEFORE_REGISTER, $event);

            if (!$model->save()) {
                $transaction->rollBack();
                return false;
            }


            $model->trigger(UserEvent::EVENT_AFTER_REGISTER, $event);

            $transaction->commit();

            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage(), 'usuario');

            return false;
        }
    }
}
