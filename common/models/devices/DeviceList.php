<?php

namespace common\models\devices;

use Yii;
use common\models\user\User;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
/**

/**
 * This is the model class for table "deviceList".
 *
 * @property string $device_id
 * @property int $user_id
 * @property string $token
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 * @property DeviceLocations $deviceLocations
 */
class DeviceList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deviceList';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_id', 'user_id', 'token'], 'required'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['device_id', 'token'], 'string', 'max' => 255],
            [['device_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'device_id' => 'Device ID',
            'user_id' => 'User ID',
            'token' => 'Token',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceLocations()
    {
        return $this->hasOne(DeviceLocations::className(), ['device_id' => 'device_id']);
    }
}
