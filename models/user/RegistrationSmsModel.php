<?php


namespace app\models\user;


use app\helpers\StringHelper;
use Da\User\Form\RegistrationForm;

/**
 * Class RegistrationSmsModel
 * @package app\models\user
 */
class RegistrationSmsModel extends RegistrationForm
{
    /** @var string Default username regexp */
    public static $usernameRegexp = '/^((\+998)+\([0-9]{2}\)+(([0-9]){3})+(\-([0-9]){2})+(\-([0-9]){2}))$/';

    public $confirmCode;

    /** @inheritDoc */
    public function rules()
    {
        $rules = parent::rules();

        unset($rules['emailRequired']);

        $rules['usernamePattern'] = ['username', 'match', 'pattern' => self::$usernameRegexp];
        $rules['confirmCodeRequired'] = ['confirmCode', 'required'];
        $rules['confirmCodeValidate'] = ['confirmCode', 'validateConfirmCode'];

        return $rules;
    }

    public function validateConfirmCode($attribute, $params, $validator)
    {
        $lastSendCode = UserSmsConfirm::find()
            ->select(['code'])
            ->where(['phone' => $this->username, 'type' => UserSmsConfirm::TYPE_REGISTER])
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
        $labels = parent::attributeLabels();

        $labels['username'] = 'Номер телефона';
        $labels['confirmCode'] = 'Код подтверждения';

        return $labels;
    }

    public function clearUsername()
    {
        $this->username = StringHelper::phoneClear($this->username);
    }
}
