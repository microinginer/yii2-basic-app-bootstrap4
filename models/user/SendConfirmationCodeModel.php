<?php


namespace app\models\user;


use Exception;
use Yii;
use yii\base\Model;

/**
 * Class SendConfirmationCodeModel
 * @package app\models\user
 */
class SendConfirmationCodeModel extends Model
{
    public $phone;
    public $code;
    public $type;

    /** @inheritDoc */
    public function rules()
    {
        return [
            [['phone', 'code', 'type'], 'required'],
            [['phone'], 'validateLastSend'],
            [['phone'], 'match', 'pattern' => RegistrationSmsModel::$usernameRegexp]
        ];
    }

    public function validateLastSend($attribute, $params, $validator)
    {
        $lastSend = UserSmsConfirm::findOne(['phone' => $this->phone]);
        if ($lastSend && $lastSend->created_at > date('Y-m-d H:i:s', strtotime('-1 minute'))) {
            $this->addError($attribute, 'C одного IP-адреса можно отправлять SMS не чаще, чем раз в 1 минуту');
        }
    }

    public function handle()
    {
        if (false === $this->validate()) {
            return false;
        }

        try {
            $model = new UserSmsConfirm();

            $model->phone = $this->phone;
            $model->code = $this->code;
            $model->message = str_replace('{{code}}', $this->code, UserSmsConfirm::$messageTemplate);
            $model->user_agent = Yii::$app->getRequest()->getUserAgent();
            $model->ip = Yii::$app->getRequest()->getUserIP();
            $model->type = $this->type;

            if (!$model->save()) {
                $this->addErrors($model->getErrors());
                throw new Exception('Sms confirm not saved');
            }

            $this->sendSmsCode();

            return true;
        } catch (Exception $exception) {
            $this->addError('phone', $exception->getMessage());
        }

        return false;
    }

    private function sendSmsCode()
    {
        // sms send helper
    }

    public function formName()
    {
        return '';
    }
}
