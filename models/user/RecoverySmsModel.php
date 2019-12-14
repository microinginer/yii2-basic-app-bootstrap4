<?php


namespace app\models\user;


use app\helpers\StringHelper;
use Exception;
use yii\base\Model;

/**
 * Class RecoverySmsModel
 * @package app\models\user
 */
class RecoverySmsModel extends Model
{
    public $phone;
    public $newPassword;
    public $confirmCode;

    /** @var User */
    private $user;

    /** @inheritDoc */
    public function rules()
    {
        return [
            [['phone', 'newPassword', 'confirmCode'], 'required'],
            [['phone'], 'match', 'pattern' => RegistrationSmsModel::$usernameRegexp],
            [['confirmCode'], 'validateConfirmCode'],
            [['phone'], 'getIsExistsPhone'],
        ];
    }

    public function getIsExistsPhone($attribute, $params, $validator)
    {
        $phone = StringHelper::phoneClear($this->phone);
        $this->user = User::findOne(['username' => $phone]);

        if (!$this->user) {
            $this->addError($attribute, 'Пользователь не найден с таким номером');
        }
    }

    public function validateConfirmCode($attribute, $params, $validator)
    {
        $lastSendCode = UserSmsConfirm::find()
            ->select(['code'])
            ->where(['phone' => $this->phone, 'type' => UserSmsConfirm::TYPE_FORGOT])
            ->orderBy(['id' => SORT_DESC])
            ->asArray()
            ->scalar();

        if (!$lastSendCode) {
            $this->addError($attribute, 'Необходимо запросить код подтверждения');
        }

        if (!in_array($this->confirmCode, [$lastSendCode])) {
            $this->addError($attribute, 'Введен неверный код активации');
        }
    }

    /** @inheritDoc */
    public function attributeLabels()
    {
        return [
            'phone' => 'Номер телефона',
            'newPassword' => 'Новый пароль',
            'confirmCode' => 'Код подтверждения',
        ];
    }

    /** @inheritDoc */
    public function handle()
    {
        try {

            $this->user->password = $this->newPassword;
            $this->user->save();

            return true;
        } catch (Exception $exception) {
            $this->addError('phone', $exception->getMessage());
        }

        return false;
    }
}
