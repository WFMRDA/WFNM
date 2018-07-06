<?php

namespace common\models\user;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "defaultLocation".
 *
 * @property int $user_id
 * @property string $address
 * @property string $place_id
 * @property string $latitude
 * @property string $longitude
 * @property int $updated_at
 * @property int $created_at
 *
 * @property User $user
 */
class DefaultLocation extends \yii\db\ActiveRecord
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
        return 'defaultLocation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'place_id', 'latitude', 'longitude' ], 'required'],
            [['user_id', 'updated_at', 'created_at'], 'integer'],
            [['address'], 'string'],
            [['latitude', 'longitude'], 'number'],
            [['place_id'], 'string', 'max' => 255],
            [['user_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'address' => 'Address',
            'place_id' => 'Place ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
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
