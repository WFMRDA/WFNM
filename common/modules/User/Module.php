<?php

/*
 * This file is part of the ptech project.
 *
 * (c) ptech project <http://github.com/ptech/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace common\modules\User;

use Yii;
use yii\base\Module as BaseModule;
use common\modules\User\models\SysVariables;
use common\modules\User\models\User;
use yii\helpers\ArrayHelper;
use common\modules\User\migrations\Migration;
use common\modules\User\helpers\UserHelpers;


/**
 * This is the main module class for the Yii2-user.
 *
 * @property array $modelMap
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Module extends BaseModule
{
    const VERSION = '0.0.0-dev';

    /** Email is changed right after user enter's new email address. */
    const STRATEGY_INSECURE = 0;

    /** Email is changed after user clicks confirmation link sent to his new email address. */
    const STRATEGY_DEFAULT = 1;

    /** Email is changed after user clicks both confirmation links sent to his old and new email addresses. */
    const STRATEGY_SECURE = 2;

    /** @var int Cost parameter used by the Blowfish hash algorithm. */
    public $cost = 10;

    /** @var array Mailer configuration */
    public $mailer = [];

    /** @var array Model map */
    public $modelMap = [];

    public $mailChimp = [];

    private $sysVariable;

    /**
     * @var string The prefix for user module URL.
     *
     * @See [[GroupUrlRule::prefix]]
     */
    public $urlPrefix = 'user';

    /** @var array The rules to be used in URL management. */
    public $urlRules = [
        '<id:\d+>'                               => 'user/profile/show',
        '<action:(login|logout)>'                => 'user/security/<action>',
        '<action:(register|resend)>'             => 'user/registration/<action>',
        'confirm/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'user/registration/confirm',
        'forgot'                                 => 'user/recovery/request',
        'recover/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'user/recovery/reset',
        'settings/<action:\w+>'                  => 'user/settings/<action>'
    ];
    public function init()
    {
        parent::init();
        // initialize the module with the configuration loaded from config.php
        \Yii::configure($this, require(__DIR__ . '/config.php'));
        $this->sysVariable = (Yii::$app->has('pSysVars'))?Yii::$app->pSysVars->sysVars: null;
    }


    /** @var bool Whether to show flash messages. */
    // public $enableFlashMessages = false;
    public function getEnableFlashMessages()
    {
        return ($this->sysVariable === null)? false :$this->sysVariable->enableFlashMessages;
        /*$var = ($this->sysVariable === null)? false :$this->sysVariable->enableFlashMessages;
        Yii::trace($var,'dev');
        return $var;*/
    }

    /** @var bool Whether to enable registration. */
     // public $enableRegistration = true;
    public function getEnableRegistration()
    {
        return ($this->sysVariable === null)? true :$this->sysVariable->enableRegistration;
    }

    /** @var bool Whether to remove password field from registration form. */
    // public $enableGeneratingPassword = false;
    public function getEnableGeneratingPassword()
    {
        return ($this->sysVariable === null)? false :$this->sysVariable->enableGeneratingPassword;
    }

    /** @var bool Whether user has to confirm his account. */
    // public $enableConfirmation = true;
    public function getEnableConfirmation()
    {
        return ($this->sysVariable === null)? true :$this->sysVariable->enableConfirmation;
    }

    /** @var bool Whether to allow logging in without confirmation. */
    // public $enableUnconfirmedLogin = false;
    public function getEnableUnconfirmedLogin()
    {
        return ($this->sysVariable === null)? false :$this->sysVariable->enableUnconfirmedLogin;
    }

    /** @var bool Whether to enable password recovery. */
    // public $enablePasswordRecovery = true;
    public function getEnablePasswordRecovery()
    {
        return ($this->sysVariable === null)? true :$this->sysVariable->enablePasswordRecovery;
    }

    /** @var int Email changing strategy. */
    // public $emailChangeStrategy = self::STRATEGY_DEFAULT;
    public function getEmailChangeStrategy()
    {
        return ($this->sysVariable === null)? self::STRATEGY_INSECURE :$this->sysVariable->emailChangeStrategy;
    }

    /** @var int The time you want the user will be remembered without asking for credentials. */
    // public $rememberFor = 1209600; // two weeks
    public function getRememberFor()
    {
        return ($this->sysVariable === null)? 1209600 :$this->sysVariable->rememberFor;
    }

    /** @var int The time before a confirmation token becomes invalid. */
    // public $confirmWithin = 86400; // 24 hours
    public function getConfirmWithin()
    {
        return ($this->sysVariable === null)? 86400 :$this->sysVariable->confirmWithin;
    }

    /** @var int The time before a recovery token becomes invalid. */
    // public $recoverWithin = 21600; // 6 hours
    public function getRecoverWithin()
    {
        return ($this->sysVariable === null)? 21600 :$this->sysVariable->recoverWithin;
    }

    /** @var array An array of administrator's usernames. */
    // public $admins = [];
    public function getAdmins()
    {
        return ($this->sysVariable === null)? [] :ArrayHelper::getColumn (User::find()->select('username')->where([">=",'role', UserHelpers::getRoleId('Admin')])->andWhere(['blocked_at' => null])->asArray()->all(),'username');
    }






}
