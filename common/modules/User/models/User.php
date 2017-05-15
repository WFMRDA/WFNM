<?php
namespace common\modules\User\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\helpers\YiiHelpers;
use yii\helpers\ArrayHelper;
use common\models\user\forms\SignupForm;
/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property int $status
 * @property int $role
 * @property string $auth_key
 * @property string $access_token
 * @property string $password_hash
 * @property string $confirmation_token
 * @property int $confirmation_sent_at
 * @property int $confirmed_at
 * @property string $recovery_token
 * @property int $recovery_sent_at
 * @property int $blocked_at
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Profile $profile
 * @property Session[] $sessions
 */
class User extends ActiveRecord implements IdentityInterface
{
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';
    const SCENARIO_RESET_PASSWORD = 'reset_password';
    const SCENARIO_CONFIRM = 'confirm';
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $userProfile;
    public $userSocialAccount;
    public $rememberMe = true;
    public $password;
    public $resetTime = 86400;
    public $enableConfirmation = true;
    public static $usernameRegexp = '/^[-a-zA-Z0-9_\.@]+$/';
    protected $_enableGeneratingPassword = false;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }
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
    public function rules()
    {
        return [
            [['username', 'email', 'auth_key', 'access_token', 'confirmation_token'], 'required'],
            [['status', 'role', 'confirmation_sent_at', 'confirmed_at', 'recovery_sent_at', 'blocked_at', 'created_at', 'updated_at'], 'integer'],
            [['username', 'email', 'password_hash'], 'string', 'max' => 255],
            [['auth_key', 'access_token', 'confirmation_token', 'recovery_token'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['auth_key'], 'unique'],
            [['access_token'], 'unique'],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['auth_key'], 'unique'],
            [['access_token'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            ['rememberMe', 'boolean'],
            [['password','userProfile','userSocialAccount','rememberMe'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'status' => 'Status',
            'role' => 'Role',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
            'password_hash' => 'Password Hash',
            'confirmation_token' => 'Confirmation Token',
            'confirmation_sent_at' => 'Confirmation Sent At',
            'confirmed_at' => 'Confirmed At',
            'recovery_token' => 'Recovery Token',
            'recovery_sent_at' => 'Recovery Sent At',
            'blocked_at' => 'Blocked At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function login($user, $timeout = 315360000){
        if($user->confirmed_at == null){
            Yii::$app->session->setFlash('error', 'This Account has not been confirmed. Please check your email for an confirmation email.');
            return false;
        }
        return Yii::$app->user->login($user, $timeout);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSessions()
    {
        return $this->hasMany(Session::className(), ['user_id' => 'id']);
    }

    /**
      * @return \yii\db\ActiveQuery
      */
    public function getSocialAccounts()
    {
        return $this->hasMany(SocialAccounts::className(), ['user_id' => 'id']);
    }

    public function getFullName(){
        return $this->profile->first_name . ' '. $this->profile->last_name;
    }
    public function getMailer(){
        return \Yii::createObject([
            'class'=>Mailer::className(),
            'sender'=>Yii::$app->params['adminEmail'],
        ]);
    }
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::find()
            ->andWhere(
                [
                    'or',
                    ['username' => $username],
                    ['email' => $username]
                ]
            )
            ->andWhere(['status' => self::STATUS_ACTIVE])
            ->one();
    }


    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordRecoveryToken($uid,$token)
    {
        $model =  static::findOne([
            'recovery_token' => $token,
            'status' => self::STATUS_ACTIVE,
            'auth_key' => $uid
        ]);

        if($model == null){
            Yii::$app->session->setFlash('error', 'Token has expired or already been used. If you need to reset your password please create another recovery token.');
            return null;
        }
        if(static::isPasswordResetTokenValid($model)){
            Yii::$app->session->setFlash('error', 'Recovery Token has expired, Please resubmit Password Reset.');
            return null;
        }
        return $model;
    }

    public static function findByConfirmationToken($uid,$token)
    {
        return static::findOne([
            'confirmation_token' => $token,
            'status' => self::STATUS_ACTIVE,
            'auth_key' => $uid
        ]);
        return $model;
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($model)
    {
        $timestamp = $model->recovery_sent_at;
        if (empty($timestamp)) {
            return false;
        }
        return ($timestamp + $model->resetTime < time());
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function hashPassword()
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $var  = Yii::$app->security->generateRandomString();
        $var_exists = $this->find()->where(['auth_key' => $var])->exists();
        if($var_exists){
            //Regenerate Random String until Unique One Is Found.
            while ($this->find()->where(['auth_key' => $var])->exists()){
                $var = Yii::$app->security->generateRandomString();
            }
        }
        $this->auth_key = $var;
    }

    /**
     * Generates API authentication key
     */
    public function generateAccessToken()
    {
        $var  = Yii::$app->security->generateRandomString();
        $var_exists = $this->find()->where(['access_token' => $var])->exists();
        if($var_exists){
            //Regenerate Random String until Unique One Is Found.
            while ($this->find()->where(['access_token' => $var])->exists()){
                $var = Yii::$app->security->generateRandomString();
            }
        }
        $this->access_token = $var;
    }

    /**
     * Generates API authentication key
     */
    public function generateConfirmationToken()
    {
        $var  = Yii::$app->security->generateRandomString();
        $var_exists = $this->find()->where(['confirmation_token' => $var])->exists();
        if($var_exists){
            //Regenerate Random String until Unique One Is Found.
            while ($this->find()->where(['confirmation_token' => $var])->exists()){
                $var = Yii::$app->security->generateRandomString();
            }
        }
        $this->confirmation_token = $var;
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $var  = Yii::$app->security->generateRandomString();
        $var_exists = $this->find()->where(['recovery_token' => $var])->exists();
        if($var_exists){
            //Regenerate Random String until Unique One Is Found.
            while ($this->find()->where(['recovery_token' => $var])->exists()){
                $var = Yii::$app->security->generateRandomString();
            }
        }
        $this->recovery_token = $var;
        $this->recovery_sent_at = time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->recovery_token = null;
        $this->recovery_sent_at = null;
    }

    /**
     * Generates new username based on email address, or creates new username
     * like "emailuser1".
     */
    public function generateUsername($email)
    {
        // try to use name part of email
        $username = explode('@', $email)[0];

        $username_exists = $this->find()->where(['username' => $username])->exists();
        if(!$username_exists){
            return $username;
        }
        $i=0;
        // generate username like "user1", "user2", etc...
        while ($this->find()->where(['username' => $username])->exists()){
            $username = $username . ++$i;
        }
        return $username;
    }

    public function setEnableConfirmation($bool = true){
        $this->enableConfirmation = $bool;
    }


    protected function setEnableGeneratingPassword($bool = false){
        $this->_enableGeneratingPassword = $bool;
    }

    public function getEnableGeneratingPassword(){
        return $this->_enableGeneratingPassword;
    }

    public function confirmAccount(){
        $this->confirmed_at = time();
        return $this->update();
    }
    /**
     * This method is used to register new user account. If Module::enableConfirmation is set true, this method
     * will generate new confirmation token and use mailer to send it to the user.
     *
     * @return bool
     */

    /*

        Logic::
        Create New User. Email Address is the only thing that's required.
        If No Password is passed to the model then we generate a temporary one for them.
        If no username is passed to the model we generate a username based on the email.

    */

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function register()
    {
        if (!$this->validate()) {
            return null;
        }
        if (!$this->createAccount()) {
            return false;
        }
        Yii::$app->session->setFlash('info','Your account has been created and a message with further instructions has been sent to your email');
        return $this;
    }

    /**
     * @param \ptech\eauth\ServiceBase $service
     * @return User
     * @throws ErrorException
     */

     /*
     Service Fields
     'id'
     'firstName'
     'lastName'
     'url'
     'image'
     'email'
     'token'
     */
     public static function logInByEAuth($service) {
        if (!$service->getIsAuthenticated()) {
            throw new ErrorException('EAuth user should be authenticated before creating identity.');
        }
        $clientId = $service->getServiceName().'-'.$service->getId();
        $data = [
            'url' =>  $service->getAttribute('url'),
            'image' => $service->getAttribute('image')
        ];
        //See If User Already Exists
        $query = SocialAccounts::findOne(['client_id' => $clientId]);
        if(($accounts = SocialAccounts::findOne(['client_id' => $clientId])) != null){
            //Social Account Found. Log In
            $accounts->updateAttributes([
                'data' => json_encode($data),
                'token' => json_encode($service->getAttribute('token')),
                'secret' => null,
            ]);
            $user = $accounts->user;
        }else if(($user  = self::findByUsername($service->getAttribute('email'))) != null) {
            //Email Found. Connect Accounts
            $accounts = Yii::createObject([
                'class'=> SocialAccounts::className(),
                'user_id' => $user->id,
                'provider' => $service->getServiceName(),
                'client_id' => $clientId,
                'data' => json_encode($data),
                'token' => json_encode($service->getAttribute('token')),
                'secret' => null,
            ]);
            if(!$accounts->save()){
                Yii::trace($accounts->errors,'dev');
            };

        }else{
            //No Accounts Found Anywhere
            //Create New User
            $user = Yii::createObject([
                'class'=> User::className(),
                'email' => $service->getAttribute('email'),
                'userProfile'=>[
                    'first_name' => $service->getAttribute('firstName'),
                    'last_name' => $service->getAttribute('lastName'),
                ],
                'userSocialAccount' => [
                    'provider' => $service->getServiceName(),
                    'client_id' => $clientId,
                    'data' => json_encode($data),
                    'token' => $service->getAttribute('token'),
                    'secret' => null,
                ]
            ]);
            $user->enableConfirmation = false;
            $user->createAccount();
        }
        return self::login($user);
     }

    public function createAccount(){
        // return $user->save() ? $user : null;

        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        $transaction = $this->getDb()->beginTransaction();
        try {
            if($this->password  == null){
                $this->setEnableGeneratingPassword(true);
                $this->password = Yii::$app->security->generateRandomString(8);
            }
            $this->username = ($this->username == null) ? $this->generateUsername($this->email) : $this->username;
            $this->generateConfirmationToken();
            // $this->hashPassword();
            $this->generateAuthKey();
            $this->generateAccessToken();
            if (!$this->save()) {
                $transaction->rollBack();
                return false;
            }
            $this->confirmation_sent_at = time();
            // Yii::trace($this->enableConfirmation,'dev');
            if ($this->enableConfirmation) {
                if(!$this->mailer->sendConfirmationMessage($this) || !$this->update()){
                    $transaction->rollBack();
                    return false;
                }
            }else{
                if($this->getEnableGeneratingPassword()){
                    $this->generatePasswordResetToken();
                }
                $this->confirmed_at = time();
                if(!$this->mailer->sendWelcomeMessage($this) || !$this->update()){
                    $transaction->rollBack();
                    return false;
                }
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            // Yii::trace('in catch','dev');
            $transaction->rollBack();
            \Yii::trace($e->getMessage(),'dev');
            throw $e;
        }

    }

    public function resendConfirmation(){
        if ($this->getIsNewRecord() == true) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on non-existing user');
        }
        $transaction = $this->getDb()->beginTransaction();
        try {
            $this->confirmation_sent_at = time();
            if(!$this->mailer->sendConfirmationMessage($this) || !$this->update()){
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::trace($e->getMessage(),'dev');
            throw $e;
        }

    }

    public function resetPassword(){
        if ($this->getIsNewRecord() == true) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on non-existing user');
        }

        $transaction = $this->getDb()->beginTransaction();
        try {
            $this->generatePasswordResetToken();
            if(!$this->mailer->sendPasswordResetMessage($this) || !$this->update()){
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return $this;
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::trace($e->getMessage(),'dev');
            throw $e;
        }
    }


    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if ($insert) {

        }
        if (!empty($this->password)) {
            $this->hashPassword();
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $_profile = \Yii::createObject([
                'class' => Profile::className(),
                'first_name' => ArrayHelper::getValue($this->userProfile,'first_name'),
                'middle_name' => ArrayHelper::getValue($this->userProfile,'middle_name'),
                'last_name' => ArrayHelper::getValue($this->userProfile,'last_name'),
                'birth_date' =>ArrayHelper::getValue($this->userProfile,'birth_date'),
                'birth_month' =>ArrayHelper::getValue($this->userProfile,'birth_month'),
                'birth_day' =>ArrayHelper::getValue($this->userProfile,'birth_day'),
                'birth_year' =>ArrayHelper::getValue($this->userProfile,'birth_year'),
                'gender' => ArrayHelper::getValue($this->userProfile,'gender'),
                'alternate_email' => ArrayHelper::getValue($this->userProfile,'alternate_email'),
                'website' => ArrayHelper::getValue($this->userProfile,'website'),
                'street' => ArrayHelper::getValue($this->userProfile,'street'),
                'city' => ArrayHelper::getValue($this->userProfile,'city'),
                'state' => ArrayHelper::getValue($this->userProfile,'state'),
                'zip' => ArrayHelper::getValue($this->userProfile,'zip'),
                'phone' => ArrayHelper::getValue($this->userProfile,'phone'),
            ]);
            $_profile->link('user', $this);

            if($this->userSocialAccount !== null){
                $_socialAccount = \Yii::createObject([
                    'class'                 => SocialAccounts::className(),
                    'provider'              => ArrayHelper::getValue($this->userSocialAccount,'provider'),
                    'client_id'             => ArrayHelper::getValue($this->userSocialAccount,'client_id'),
                    'data'                  => ArrayHelper::getValue($this->userSocialAccount,'data'),
                ]);
                $_socialAccount->link('user', $this);
            }
        }
    }

}
