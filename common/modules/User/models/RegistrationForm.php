<?php

namespace common\modules\User\models;

use common\modules\User\traits\ModuleTrait;
use Yii;
use yii\base\Model;
use kartik\password\StrengthValidator;
use common\modules\User\models\User;
use common\modules\User\models\Profile;
use yii\helpers\ArrayHelper;
/**
 * Registration form collects user input on registration process, validates it and creates new User model.
 *
 * @author Reginald Goolsby <rjgoolsby@pyrotechsolutions.com>
 */
class RegistrationForm extends Model
{
    use ModuleTrait;

    public $username;
    public $email;
    public $password;
    public $forceConfirm = true;
    public $fillProfile = false;
    public $first_name;
    public $last_name;
    public $middle_name;
    public $gender;
    public $alternate_email;
    public $street;
    public $city;
    public $state;
    public $phone;
    public $zip;
    public $birth_date;
    public $birth_month;
    public $birth_day;
    public $birth_year;
    public $bio;
    public $website;
    public $verifyCode;

    /* @inheritdoc
     */
    public function rules()
    {
        $baseRulset = [
            ['username', 'match', 'pattern' => User::$usernameRegexp],
            ['username', 'trim'],
            ['username','required'],
            ['username', 'unique', 'targetClass' => User::className(), 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email','allowName'=>true],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => 'This email address has already been taken.'],

            ['password', 'required', 'skipOnEmpty' => $this->module->enableGeneratingPassword],
            [['password'], StrengthValidator::className(), 'preset'=>StrengthValidator::SIMPLE, 'userAttribute'=>'username' ],//MEDIUM
            // [['password','email','username','userProfile'],'safe'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
            
        ];
        if($this->fillProfile){
            $ruleset = ArrayHelper::merge($baseRulset,[
                ['alternate_email', 'trim'],
                ['alternate_email', 'email','allowName'=>true],
                ['alternate_email', 'string', 'max' => 255],
                // ['alternate_email', 'unique', 'targetClass' => Profile::className(), 'message' => 'This email address has already been taken.'],
                ['website','url'],
                [['first_name', 'last_name','street', 'city', 'state', 'phone','zip','gender','birth_month','birth_year','birth_day'], 'required'],
                [['gender', 'zip'], 'integer'],
                [['bio'], 'string'],
                [['first_name', 'middle_name', 'last_name', 'alternate_email', 'website', 'street', 'city', 'state', 'phone','birth_month','birth_year','birth_day','birth_date'], 'string', 'max' => 255],
            ]);
        }else{
            $ruleset = $baseRulset;
        }
        return $ruleset;
    }

    /**
     * @inheritdoc
     */
        public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'register-form';
    }

    /**
     * Registers a new user account. If registration was successful it will set flash message.
     *
     * @return bool
     */
    public function register()
    {
        if ($this->validate()) {
            $userInit['class'] = User::className();
            if($this->fillProfile){
                $userInit['userProfile']=[
                    'first_name' => ArrayHelper::getValue($this->attributes,'first_name'),
                    'middle_name' => ArrayHelper::getValue($this->attributes,'middle_name'),
                    'last_name' => ArrayHelper::getValue($this->attributes,'last_name'),
                    'birth_date' =>ArrayHelper::getValue($this->attributes,'birth_date'),
                    'birth_month' =>ArrayHelper::getValue($this->attributes,'birth_month'),
                    'birth_day' =>ArrayHelper::getValue($this->attributes,'birth_day'),
                    'birth_year' =>ArrayHelper::getValue($this->attributes,'birth_year'),
                    'gender' => ArrayHelper::getValue($this->attributes,'gender'),
                    'alternate_email' => ArrayHelper::getValue($this->attributes,'alternate_email'),
                    'website' => ArrayHelper::getValue($this->attributes,'website'),
                    'street' => ArrayHelper::getValue($this->attributes,'street'),
                    'city' => ArrayHelper::getValue($this->attributes,'city'),
                    'state' => ArrayHelper::getValue($this->attributes,'state'),
                    'zip' => ArrayHelper::getValue($this->attributes,'zip'),
                    'phone' => ArrayHelper::getValue($this->attributes,'phone'),
                ];
            }
            /** @var User $user */
            $user = Yii::createObject($userInit);
            // $user->setScenario(User::SCENARIO_REGISTER);
            $user->setAttributes($this->attributes);

            if ($user->createAccount()) {
                Yii::$app->session->setFlash('info','Your account has been created and a message with further instructions has been sent to your email');
                $success = true;
            }else{
                Yii::$app->session->setFlash('danger','Your account has been created and a message with further instructions has been sent to your email');
                $success = false;
            }
        }else{
            $success = false;
        }
        return $success;
    }
}
