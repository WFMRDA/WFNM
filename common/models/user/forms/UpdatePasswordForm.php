<?php
namespace common\models\user\forms;

use Yii;
use yii\base\Model;
use common\models\user\User;
use kartik\password\StrengthValidator;

/**
 * Update Password form
 */
class UpdatePasswordForm extends Model
{
    public $password;
    public $verifyCode;
    public $email;
    public $username;

    /**
     * @var \common\models\user\User
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct(User $user, $config = [])
    {
        $this->_user = $user;
        $this->username = $this->_user->username;
        $this->email = $this->_user->email;

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            [['password'], StrengthValidator::className(), 'preset'=>StrengthValidator::MEDIUM, 'userAttribute'=>'username' ],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
            [['password','captcha','username','email'],'safe'],

        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $this->_user->password = $this->password;
        $this->_user->confirmed_at = time();
        $this->_user->removePasswordResetToken();
        return $this->_user->update();
    }

}
