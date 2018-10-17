<?php

namespace common\models\devices;

use Yii;
use common\models\user\User;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
/**

/**
 * This is the model class for table "deviceLocations".
 *
 * @property string $device_id
 * @property string $latitude
 * @property string $longitude
 * @property int $created_at
 * @property int $updated_at
 *
 * @property DeviceList $device
 */
class DeviceLocations extends \yii\db\ActiveRecord
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
        return 'deviceLocations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_id', 'latitude', 'longitude'], 'required'],
            [['latitude', 'longitude'], 'number'],
            [['created_at', 'updated_at'], 'integer'],
            [['device_id'], 'string', 'max' => 255],
            [['device_id'], 'unique'],
            [['device_id'], 'exist', 'skipOnError' => true, 'targetClass' => DeviceList::className(), 'targetAttribute' => ['device_id' => 'device_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'device_id' => 'Device ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevice()
    {
        return $this->hasOne(DeviceList::className(), ['device_id' => 'device_id']);
    }
}
