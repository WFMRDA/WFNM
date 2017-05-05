<?php

/*
 * This file is part of the ptech project.
 *
 * (c) ptech project <http://github.com/ptech/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace common\modules\User\models;

use common\modules\User\Mailer;
use common\modules\User\traits\ModuleTrait;
use Yii;
use yii\base\Model;

/**
 * ResendForm gets user email address and validates if user has already confirmed his account. If so, it shows error
 * message, otherwise it generates and sends new confirmation token to user.
 *
 * @property User $user
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class ResendForm extends Model
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
            ['email', 'email','allowName'=>true],
            ['email', 'exist',
                'targetClass' => User::className(),
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
            ],
            [
                'email',
                function () {
                    if ($this->user != null && $this->user->getIsConfirmed()) {
                        $this->addError('email', 'This account has already been confirmed. Please Log In');
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
                Yii::$app->session->setFlash('success', 'A new confirmation link has been sent. Check your email for further instructions.');
            }
        }else{
            Yii::$app->session->setFlash('success', 'This account has already been confirmed. Please Log In');
        }
        return $user;
    }

    /**
     * @return User
     */
    protected $_user;
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->email);
        }

        return $this->_user;
    }
}
