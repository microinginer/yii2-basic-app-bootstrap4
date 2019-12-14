<?php

namespace app\models\user;


use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * Class User
 * @package app\models\user
 */
class User extends \Da\User\Model\User
{
    /** @var string Default username regexp */
    public static $usernameRegexp = '/^[-a-zA-Z0-9_\.@\+]+$/';

    /** @inheritDoc */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => (new \DateTime())->format('Y-m-d H:i:s'),
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

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
