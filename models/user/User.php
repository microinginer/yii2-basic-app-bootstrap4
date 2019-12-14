<?php

namespace app\models\user;


use yii\helpers\ArrayHelper;

/**
 * Class User
 * @package app\models\user
 */
class User extends \Da\User\Model\User
{
    /** @var string Default username regexp */
    public static $usernameRegexp = '/^[-a-zA-Z0-9_\.@\+]+$/';

    public function rules()
    {
        $rules = parent::rules();
        $rules['usernameMatch'] = ['username', 'match', 'pattern' => self::$usernameRegexp];
        return $rules;
    }

    public function getCorrectName()
    {
        return $this->profile->name ?: $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return ArrayHelper::merge(
            parent::scenarios(),
            [
                'register-sms' => ['username', 'password'],
            ]
        );
    }
}
