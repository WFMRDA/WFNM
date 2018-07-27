<?php

namespace common\dataMigration\app;

use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property int $user_id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $birth_date
 * @property int $birth_month
 * @property int $birth_day
 * @property int $birth_year
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
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('migrate');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'birth_month', 'birth_day', 'birth_year', 'gender', 'zip', 'email_prefs', 'created_at', 'updated_at'], 'integer'],
            [['birth_date'], 'safe'],
            [['bio'], 'string'],
            [['first_name', 'middle_name', 'last_name', 'alternate_email', 'website', 'street', 'city', 'state', 'phone'], 'string', 'max' => 255],
            [['user_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
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
}
