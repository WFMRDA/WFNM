<?php
namespace common\models\user\forms;

use Yii;
use yii\base\Model;
use common\models\user\User;
use yii\helpers\ArrayHelper;
use kartik\password\StrengthValidator;
/**
 * Signup form
 */
class SignupForm extends Model
{
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


    /**
     * @inheritdoc
     */
    public function rules()
    {
        $baseRulset = [
            ['username', 'trim'],
            ['username','required'],
            ['username', 'unique', 'targetClass' => '\common\models\user\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email','allowName'=>true],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\user\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            [['password'], StrengthValidator::className(), 'preset'=>StrengthValidator::MEDIUM, 'userAttribute'=>'username' ],
            [['password','email','username','userProfile'],'safe'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
            
        ];
        if($this->fillProfile){
            $ruleset = ArrayHelper::merge($baseRulset,[

                ['alternate_email', 'trim'],
                ['alternate_email', 'required'],
                ['alternate_email', 'email','allowName'=>true],
                ['alternate_email', 'string', 'max' => 255],
                ['alternate_email', 'unique', 'targetClass' => '\common\models\user\User', 'message' => 'This email address has already been taken.'],
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
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function register()
    {
        if (!$this->validate()) {
            return null;
        }
        /*
        'SignupForm' => [
            'username' => 'Rjgoolsby',
            'email' => 'Rjgoolsby1@gmail.com',
            'password' => 'paladin62',
            'first_name' => 'Reginald Goolsby',
            'middle_name' => 'Jerome',
            'last_name' => 'Goolsby',
            'birth_month' => '1',
            'birth_day' => '1',
            'birth_year' => '1985',
            'birthdate' => '1985-01-01',
            'gender' => '10',
            'alternate_email' => '',
            'website' => '',
            'street' => '204 Goldenrod Lane',
            'city' => 'Moore',
            'state' => 'SC',
            'zip' => '29369',
            'phone' => '706-319-2114',
        ],*/
        $user = Yii::createObject([
            'class'=> User::className(),
            'userProfile'=>[
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
            ],
        ]);
        // $user->setScenario(User::SCENARIO_REGISTER);
        $user->setAttributes($this->attributes);
        $user->setEnableConfirmation($this->forceConfirm);

        // Yii::trace(Yii::$app->request->post(),'dev');
        // Yii::trace($user->attributes,'dev');
        // throw new \Exception('stop here');
        if ($user->createAccount()) {
            Yii::$app->session->setFlash('info','Your account has been created and a message with further instructions has been sent to your email');
            return true;
        }else{
            Yii::$app->session->setFlash('info','Your account has been created and a message with further instructions has been sent to your email');
            return false;
        }
    }


}
