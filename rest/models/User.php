<?php

namespace rest\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use ptech\pyrocms\models\user\User as BaseUser;

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

}
