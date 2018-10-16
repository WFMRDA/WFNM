<?php

namespace rest\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use ptech\pyrocms\models\user\User as BaseUser;
use ptech\pyrocms\models\helpers\Permissions;
use ptech\pyrocms\models\user\SocialAccounts;
use yii\base\InvalidParamException;

class User extends BaseUser
// class User extends ActiveRecord implements IdentityInterface
{
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    public $user;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()->where(['access_token' => $token])->active()->one();
    }

    public static function findByEmail($email)
    {
        return static::find()->where(['email' => $email])->active()->one();
    }

    public static function restLogin($username, $password){
        // Yii::trace($username,$password,'dev');
        // username, password are mandatory fields
        if(empty($username) || empty($password)){
            return null;
        }

        // get user using requested email
       /* $user = User::findOne([
            'username' => $username,
        ]);
*/
        $user = User::find()->andWhere(['username' => $username])->active()->one();

        // if no record matching the requested user
        if(empty($user)){
            return null;
        }

        // hashed password from user record
        $user_password_hash = $user->password_hash;

        // validate password
        $isPass = Yii::$app->security->validatePassword($password, $user_password_hash);
        // if password validation fails
        if(!$isPass){
            return null;
        }

        // if user validates (both user_email, user_password are valid)
        return $user;
    }

    public function logInByEAuth($serviceProvider,$clientId,$email,$data,$token,$userInfo = []) {
        //See If User Already Exists
        if(($accounts = SocialAccounts::findOne(['client_id' => $clientId])) != null){
            //Social Account Found. Log In
            //Don't overwrite data with Firebase Yet because we don't have all the information
           
            Yii::trace('social found','dev');
             $accounts->updateAttributes([
                'data' => $data,
                'token' => $token,
                'secret' => null,
            ]);
            $user = $accounts->user;
        }else if(($user  = Yii::createObject(User::className())::findByUsername($email)) != null) {
            //Email Found. Connect Accounts
            Yii::trace('email found','dev');
            $accounts = Yii::createObject([
                'class'=> SocialAccounts::className(),
                'user_id' => $user->id,
                'provider' => $serviceProvider,
                'client_id' => $clientId,
                'data' => $data,
                'token' => $token,
                'secret' => null,
            ]);
            if(!$accounts->save()){
                Yii::trace($accounts->errors,'dev');
            };

        }else{
            //No Accounts Found Anywhere
            //Create New User
            Yii::trace('create new user','dev');
            $user = Yii::createObject([
                'class'=> User::className(),
                'email' => $email,
                'userProfile'=>[
                    'first_name' => $userInfo['firstName'],
                    'last_name' => $userInfo['lastName'],
                ],
                'userSocialAccount' => [
                    'provider' => $serviceProvider,
                    'client_id' => $clientId,
                    'data' => $data,
                    'token' => $token,
                    'secret' => null,
                ]
            ]);
            $user->enableConfirmation = false;
            $user->createAccount();
        }
        return $user;
    }

    public function connect(){
        //Check to see if this account has been assigned to anyone else
        $query = Yii::createObject(User::className())::find()
            ->andWhere(
                [
                    'and',
                    ['<>','id', Yii::$app->user->identity->id],
                    ['email' => $this->service->getAttribute('email')]
                ]
            )
            ->one();
        if($query != null) {
            //This social media account has an email address associated with an already established account that's not this users
            throw new InvalidParamException('This social media account has an email address associated with an already established account that\'s not this users');
        }
        $accounts = Yii::createObject([
            'class'=> SocialAccounts::className(),
            'user_id'=> Yii::$app->user->identity->id,
            'provider' => $this->service->getServiceName(),
            'client_id' => $this->clientId,
            'data' => $this->data,
            'token' => $this->token,
            'secret' => null,
        ]);
        return $accounts->save();
    }

}
