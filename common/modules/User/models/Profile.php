<?php

namespace common\modules\User\models;


use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "profile".
 *
 * @property int $user_id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $birth_date
 * @property int $gender
 * @property string $alternate_email
 * @property string $website
 * @property string $street
 * @property string $city
 * @property string $state
 * @property int $zip
 * @property string $phone
 * @property string $bio
 * @property int $email_prefs
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{

    const ALL_EMAILS = 100;
    const NO_EMAILS  = 0;
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
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'birth_month', 'birth_day', 'birth_year', 'gender', 'zip', 'email_prefs', 'created_at', 'updated_at'], 'integer'],
            [['birth_date'], 'safe'],
            [['bio'], 'string'],
            [['first_name', 'middle_name', 'last_name', 'alternate_email', 'website', 'street', 'city', 'state', 'phone'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            'birth_date' => 'Birth Date',
            'birth_month' => 'Birth Month',
            'birth_day' => 'Birth Day',
            'birth_year' => 'Birth Year',
            'gender' => 'Gender',
            'alternate_email' => 'Alternate Email',
            'website' => 'Website',
            'street' => 'Street',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
            'phone' => 'Phone',
            'bio' => 'Bio',
            'email_prefs' => 'Email Prefs',
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
