<?php

namespace common\models\myLocations;

use Yii;
use common\models\user\User;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "myLocations".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $address
 * @property string $place_id
 * @property string $latitude
 * @property string $longitude
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class MyLocations extends \yii\db\ActiveRecord
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
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myLocations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id','latitude', 'longitude'], 'required'],
            // normalize "phone" input
            ['place_id', 'filter', 'filter' => function ($value) {
                $key = number_format($this->latitude, 2).','.number_format($this->longitude, 2);
                return md5($key);
            }],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['address'], 'string'],
            [['latitude', 'longitude'], 'number'],
            [['place_id'], 'string', 'max' => 255],
            [['user_id', 'place_id'], 'unique', 'targetAttribute' => ['user_id','place_id'], 'message' => 'This user already is monitoring this location..'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'address' => 'Address',
            'place_id' => 'Place ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
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

}
