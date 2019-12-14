<?php

namespace app\models\user;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_sms_confirm".
 *
 * @property int $id
 * @property int $type
 * @property string $created_at
 * @property string $code
 * @property string $phone
 * @property string|null $user_agent
 * @property string|null $ip
 * @property string|null $message
 */
class UserSmsConfirm extends ActiveRecord
{
    const TYPE_REGISTER = 10;
    const TYPE_FORGOT = 20;
    const TYPE_DELETE = 30;

    public static $messageTemplate = 'Код подтверждения: {{code}}';

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => (new \DateTime())->format('Y-m-d H:i:s'),
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_sms_confirm';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'phone'], 'required'],
            [['created_at'], 'safe'],
            [['code'], 'string', 'max' => 10],
            [['phone', 'user_agent', 'message'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 15],
            [['type'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'code' => 'Code',
            'phone' => 'Phone',
            'user_agent' => 'User Agent',
            'ip' => 'Ip',
            'message' => 'Message',
        ];
    }
}
