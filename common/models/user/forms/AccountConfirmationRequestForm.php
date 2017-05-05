<?php
namespace common\models\user\forms;

use Yii;
use yii\base\Model;
use common\models\user\User;

/**
 * Password reset request form
 */
class AccountConfirmationRequestForm extends Model
{
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
                'targetClass' => '\common\models\user\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
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

        if (!$user) {
            return false;
        }

        if (!$this->validate()) {
            return false;
        }
        //Check to see if account as already been confirmed.
        if($user->confirmed_at == null){
            // $user->setScenario('register');
            $user->setAttributes($this->attributes);
            if (!$user->resendConfirmation()) {
                return false;
            }else{
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
            }
        }else{
            Yii::$app->session->setFlash('success', 'This account has already been confirmed. Please Log In');
        }
        return $user;
    }
}
