<?php

/*
 * This file is part of the ptech project.
 *
 * (c) ptech project <http://github.com/ptech/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace common\modules\User\controllers;

use common\modules\User\events\ConnectEvent;
use common\modules\User\events\FormEvent;
use common\modules\User\events\UserEvent;
use common\modules\User\Finder;
use common\modules\User\models\RegistrationForm;
use common\modules\User\models\ResendForm;
use common\modules\User\models\User;
use common\modules\User\traits\AjaxValidationTrait;
use common\modules\User\traits\EventTrait;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

use common\modules\User\helpers\DatePicker;
use common\modules\User\helpers\UserHelpers;
use yii\helpers\ArrayHelper;

/**
 * RegistrationController is responsible for all registration process, which includes registration of a new account,
 * resending confirmation tokens, email confirmation and registration via social networks.
 *
 * @property \common\modules\User\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RegistrationController extends Controller
{
    use AjaxValidationTrait;
    /**
     * @param string           $id
     * @param \yii\base\Module $module
     * @param Finder           $finder
     * @param array            $config
     */
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['register', 'connect','captcha'], 'roles' => ['?']],
                    ['allow' => true, 'actions' => ['confirm', 'resend','captcha'], 'roles' => ['?', '@']],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => 'test',
            ],
        ];
    }
    /**
     * Displays the registration page.
     * After successful registration if enableConfirmation is enabled shows info message otherwise redirects to home page.
     *
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionRegister()
    {
        if (!$this->module->enableRegistration) {
            throw new NotFoundHttpException();
        }

        /** @var RegistrationForm $model */
        $model = Yii::createObject(RegistrationForm::className());
        // $model->fillProfile = true;

        // $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && ($user = $model->register())) {
            return $this->redirect(Url::to('/user/security/login'));
        }

        if($model->fillProfile){
            $listData['months'] = DatePicker::getMonths();
            $listData['years'] = DatePicker::getYears();
            $listData['days'] = DatePicker::getDays();
            $listData['genders'] = UserHelpers::getGenderList();
            $listData['states'] = ArrayHelper::map(UserHelpers::get_states_list(),'abbr','name');
        }else{
            $listData = [];
        }

        return $this->render('register', [
            'model'  => $model,
            'module' => $this->module,
            'listData' => $listData,
        ]);
    }

    /**
     * Confirms user's account. If confirmation was successful logs the user and shows success message. Otherwise
     * shows error message.
     *
     * @param int    $id
     * @param string $code
     *
     * @return string
     * @throws \yii\web\HttpException
     */

    public function actionConfirm($uid,$utk){
        $user = User::findByConfirmationToken($uid,$utk);

        if($user !== null && $user->confirmAccount() && User::login($user)){
            Yii::$app->session->setFlash('success', 'Account Confirmed. Enjoy!');
        }else{
            Yii::$app->session->setFlash('error', 'Account Confirmation Failed.');
        }

        return $this->goHome();
    }

    /**
     * Displays page where user can request new confirmation token. If resending was successful, displays message.
     *
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionResend()
    {
        if ($this->module->enableConfirmation == false) {
            throw new NotFoundHttpException();
        }

        /** @var ResendForm $model */
        $model = Yii::createObject(ResendForm::className());

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->reset()) {
            return $this->redirect(Url::to('/user/security/login'));
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend confirmation link for the provided email address.');
            }
        }
        return $this->render('resend', [
            'model' => $model,
        ]);
    }

}
