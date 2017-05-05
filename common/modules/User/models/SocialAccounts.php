<?php

namespace common\modules\User\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "social_accounts".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $provider
 * @property integer $client_id
 * @property string $data
 * @property string $token
 * @property string $secret
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class SocialAccounts extends \yii\db\ActiveRecord
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
        return 'social_accounts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'provider', 'client_id'], 'required'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['token', 'secret','data'], 'string'],
            ['client_id','unique','message'=>'This Account has already been registered to another User'],
            [['provider', 'client_id'], 'string', 'max' => 255],
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
            'provider' => 'Provider',
            'client_id' => 'Client ID',
            'data' => 'Data',
            'token' => 'Token',
            'secret' => 'Secret',
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

    public function addNew($service){

            Yii::trace($service->getId(),'dev');
        $clientId = $service->getServiceName().'-'.$service->getId();
        $data = [
            'url' =>  $service->getAttribute('url'),
            'image' => $service->getAttribute('image')
        ];
        $accounts = Yii::createObject([
            'class'=> SocialAccounts::className(),
            'user_id'=> Yii::$app->user->identity->id,
            'provider' => $service->getServiceName(),
            'client_id' => $clientId,
            'data' => json_encode($data),
            'token' => json_encode($service->getAttribute('token')),
            'secret' => null,
        ]);
        return $accounts->save();
    }
}
