<?php

/*
 * This file is part of the ptech project.
 *
 * (c) ptech project <http://github.com/ptech/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace common\models\user;

use common\models\user\User;
use Yii;
use yii\base\Model;
use common\models\helpers\YiiHelpers;
use common\models\helpers\Html2Text;
use yii\web\View;

/*

$template = EmailTemplates::find()->where(['default'=> TbmHelpers::getEmailMessagePrefsId('Reset Password')])->asArray()->one();
$messages = new \common\models\messages\Messages;
$newUser = \common\models\User::findUsersEmailApprovedUser($this->id);
//$users,Message id,from email, from name,
$messages->sendOne($newUser,$template['subject'],$template['id'],$template['from_email'],$template['from_name'],[
    'NEWUSERPASSWORD' => $this->password,
]);


*/

/**
 * Mailer.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Mailer extends Model
{
    /** @var string */
    public $viewPath = '@common/mail';

    /** @var string|array Default: `Yii::$app->params['adminEmail']` OR `no-reply@example.com` */
    public $sender;

    /** @var string */
    protected $welcomeSubject;

    /** @var string */
    protected $confirmationSubject;

    /** @var string */
    protected $reconfirmationSubject;

    /** @var string */
    protected $recoverySubject;

    /**
     * @return string
     */
    public function getWelcomeSubject()
    {
        if ($this->welcomeSubject == null) {
            $this->setWelcomeSubject('Welcome to '.Yii::$app->name);
        }

        return $this->welcomeSubject;
    }

    /**
     * @param string $welcomeSubject
     */
    public function setWelcomeSubject($welcomeSubject)
    {
        $this->welcomeSubject = $welcomeSubject;
    }

    /**
     * @return string
     */
    public function getConfirmationSubject()
    {
        if ($this->confirmationSubject == null) {
            $this->setConfirmationSubject('Confirm account on '.Yii::$app->name);
        }

        return $this->confirmationSubject;
    }

    /**
     * @param string $confirmationSubject
     */
    public function setConfirmationSubject($confirmationSubject)
    {
        $this->confirmationSubject = $confirmationSubject;
    }

    /**
     * @return string
     */
    public function getReconfirmationSubject()
    {
        if ($this->reconfirmationSubject == null) {
            $this->setReconfirmationSubject('Confirm email change on '. Yii::$app->name);
        }

        return $this->reconfirmationSubject;
    }

    /**
     * @param string $reconfirmationSubject
     */
    public function setReconfirmationSubject($reconfirmationSubject)
    {
        $this->reconfirmationSubject = $reconfirmationSubject;
    }

    /**
     * @return string
     */
    public function getRecoverySubject()
    {
        if ($this->recoverySubject == null) {
            $this->setRecoverySubject('Complete password reset on '.Yii::$app->name);
        }

        return $this->recoverySubject;
    }

    /**
     * @param string $recoverySubject
     */
    public function setRecoverySubject($recoverySubject)
    {
        $this->recoverySubject = $recoverySubject;
    }

    /** @inheritdoc */
    public function init()
    {
        parent::init();
    }

    /**
     * Sends an email to a user after registration.
     *
     * @param User  $user
     * @param Token $token
     * @param bool  $showPassword
     *
     * @return bool
     */
    public function sendWelcomeMessage(User $user)
    {
        return $this->sendMessage($user->email,
            $this->getWelcomeSubject(),
            'welcome',
            ['user' => $user]
        );
    }

    /**
     * Sends an email to a user with confirmation link.
     *
     * @param User  $user
     * @param Token $token
     *
     * @return bool
     */
    public function sendConfirmationMessage(User $user)
    {
        return $this->sendMessage($user->email,
            $this->getConfirmationSubject(),
            'confirmation',
            ['user' => $user]
        );
    }

    /**
     * Sends an email to a user with reconfirmation link.
     *
     * @param User  $user
     * @param Token $token
     *
     * @return bool
     */
    public function sendReconfirmationMessage(User $user, Token $token)
    {
        if ($token->type == Token::TYPE_CONFIRM_NEW_EMAIL) {
            $email = $user->unconfirmed_email;
        } else {
            $email = $user->email;
        }

        $template = $this->getTemplate('Reconfirmation Message');
        $html = (empty($template['html']))?'reconfirmation':$template;
        return $this->sendMessage($email,
            $this->getReconfirmationSubject(),
            $html,
            ['user' => $user, 'token' => $token]
        );
    }

    /**
     * Sends an email to a user with credentials and confirmation link.
     *
     * @param  User  $user
     * @param  Token $token
     * @return bool
     */
    public function sendPasswordResetMessage(User $user)
    {
        return $this->sendMessage($user->email,
            'Password Reset',
            'recovery',
            ['user' => $user]
        );
    }

    /**
     * Sends an email to a user with recovery link.
     *
     * @param User  $user
     * @param Token $token
     *
     * @return bool
     */
    public function sendRecoveryMessage(User $user, Token $token)
    {

        $template = $this->getTemplate('Password Recovery');
        $html = (empty($template['html']))?'recovery':$template;
        return $this->sendMessage($user->email,
            $this->getRecoverySubject(),
            $html,
            ['user' => $user, 'token' => $token]
        );
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $view
     * @param array  $params
     *
     * @return bool
     */
    protected function sendMessage($to, $subject, $view, $params = [])
    {
        /** @var \yii\mail\BaseMailer $mailer */
        $mailer = Yii::$app->mailer;
        $mailer->viewPath = $this->viewPath;
        $mailer->getView()->theme = Yii::$app->view->theme;

        if ($this->sender === null) {
            $this->sender = isset(Yii::$app->params['adminEmail']) ? Yii::$app->params['adminEmail'] : 'no-reply@example.com';
        }

        $html = \Yii::$app->view->render($this->viewPath.'/'.$view,$params);
        // Yii::trace($html,'mail');
        // return true;
        $html2text = new Html2Text($html);
        $textHtml = $html2text->getText();
        return Yii::$app->mailer->compose()
            ->setFrom($this->sender)
            ->setTo($to)
            ->setSubject($subject)
            ->setTextBody($textHtml)
            ->setHtmlBody($html)
            ->send();

    }

}
