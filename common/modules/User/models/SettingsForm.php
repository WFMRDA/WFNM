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

use common\modules\User\helpers\Password;
use common\modules\User\Module;
use common\modules\User\traits\ModuleTrait;
use Yii;
use yii\base\Model;
use kartik\password\StrengthValidator;
use common\modules\User\models\User;
use common\modules\User\models\Profile;

/**
 * SettingsForm gets user's username, email and password and changes them.
 *
 * @property User $user
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class SettingsForm extends Model
{
    use ModuleTrait;

    /** @var string */
    public $email;

    /** @var string */
    public $username;

    /** @var string */
    public $new_password;

    /** @var string */
    public $current_password;

    /** @var User */
    private $user;

    public function getMailer(){
        return \Yii::createObject([
            'class'=> \common\modules\User\models\Mailer::className(),
            'sender'=> Yii::$app->params['adminEmail'],
        ]);
    }

    public function init(){
        $this->user = User::findOne(Yii::$app->user->identity->id);
        $this->setAttributes($this->user->attributes);
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            ['username', 'match', 'pattern' => User::$usernameRegexp],
            ['username', 'trim'],
            ['username','required'],
            // ['username', 'unique', 'targetClass' => User::className(), 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email','allowName'=>true],
            ['email', 'string', 'max' => 255],
            // ['email', 'unique', 'targetClass' => User::className(), 'message' => 'This email address has already been taken.'],
            [['email', 'username'], 'unique', 'when' => function ($model, $attribute) {
                return $this->user->$attribute != $model->$attribute;
            }, 'targetClass' => User::className()],

            ['current_password', 'required'],
            ['current_password', function ($attr) {
                if (!Password::validate($this->$attr, $this->user->password_hash)) {
                    $this->addError($attr, 'Current password is not valid');
                }
            }],


            [['new_password'], StrengthValidator::className(), 'preset'=>StrengthValidator::SIMPLE, 'userAttribute'=>'username' ],//MEDIUM
        ];
    }


    /** @inheritdoc */
    public function formName()
    {
        return 'settings-form';
    }

    /**
     * Saves new account settings.
     *
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            // $this->user->scenario = 'settings';
            $this->user->username = $this->username;
            if($this->new_password != null){
                $this->user->password = $this->new_password;
            }
            if ($this->email != $this->user->email) {
                switch ($this->module->emailChangeStrategy) {
                    case Module::STRATEGY_INSECURE:
                        $this->insecureEmailChange();
                        break;
                    case Module::STRATEGY_DEFAULT:
                        $this->defaultEmailChange();
                        break;
                    case Module::STRATEGY_SECURE:
                        $this->secureEmailChange();
                        break;
                    default:
                        throw new \OutOfBoundsException('Invalid email changing strategy');
                }
            }

            if($this->user->save()){
                $success = true;
                Yii::$app->session->setFlash('success',  'Your settings has been changed');
            }else{
                $success = false;
                Yii::$app->session->setFlash('danger',  'Your settings changed failed');
            }
        }else{
            $success = false;
        }

        return $success;
    }

    /**
     * Changes user's email address to given without any confirmation.
     */
    protected function insecureEmailChange()
    {
        $this->user->email = $this->email;
        Yii::$app->session->setFlash('success',  'Your email address has been changed');
    }

    /**
     * Sends a confirmation message to user's email address with link to confirm changing of email.
     */
    protected function defaultEmailChange()
    {
        $this->user->unconfirmed_email = $this->email;
        /** @var Token $token */
        $token = Yii::createObject([
            'class'   => Token::className(),
            'user_id' => $this->user->id,
            'type'    => Token::TYPE_CONFIRM_NEW_EMAIL,
        ]);
        $token->save(false);
        $this->mailer->sendReconfirmationMessage($this->user, $token);
        Yii::$app->session->setFlash('info',  'A confirmation message has been sent to your new email address');
    }

    /**
     * Sends a confirmation message to both old and new email addresses with link to confirm changing of email.
     *
     * @throws \yii\base\InvalidConfigException
     */
    protected function secureEmailChange()
    {
        $this->defaultEmailChange();
        /** @var Token $token */
        $token = Yii::createObject([
            'class'   => Token::className(),
            'user_id' => $this->user->id,
            'type'    => Token::TYPE_CONFIRM_OLD_EMAIL,
        ]);
        $token->save(false);
        $this->mailer->sendReconfirmationMessage($this->user, $token);

        // unset flags if they exist
        $this->user->flags &= ~User::NEW_EMAIL_CONFIRMED;
        $this->user->flags &= ~User::OLD_EMAIL_CONFIRMED;
        $this->user->save(false);

        Yii::$app->session->setFlash('info','We have sent confirmation links to both old and new email addresses. You must click both links to complete your request');
    }
}
