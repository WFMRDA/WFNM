<?php
namespace common\modules\User\models;


use common\modules\User\traits\ModuleTrait;
use Yii;
use yii\base\Model;
use common\modules\User\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    use ModuleTrait;
    public $email;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::classname(),
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
            ],
            [
                'email',
                function ($attribute) {
                    if ($this->user !== null && $this->module->enableConfirmation && !$this->user->getIsConfirmed()) {
                        $this->addError($attribute, 'You need to confirm your email address');
                    }
                }
            ],
        ];
    }

    public function reset()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user === null) {
            return false;
        }
        // $user->setScenario('register');
        $user->setAttributes($this->attributes);
        return $user->resetPassword();
        // Yii::$app->session->setFlash('info','Your account has been created and a message with further instructions has been sent to your email');
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected $_user;
    protected function getUser()
    {

        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->email);
        }

        return $this->_user;
    }
}
