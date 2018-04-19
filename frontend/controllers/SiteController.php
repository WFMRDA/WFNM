<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\user\forms\LoginForm;
use common\models\user\forms\PasswordResetRequestForm;
use common\models\user\forms\ResetPasswordForm;
use common\models\user\forms\SignupForm;
use common\models\user\forms\ContactForm;
use common\models\user\forms\UpdatePasswordForm;
use common\models\user\forms\AccountConfirmationRequestForm;
use yii\helpers\Url;
use common\models\helpers\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\user\User;
use frontend\models\FireSearchForm;
use frontend\models\MapLegendForm;
use common\modules\User\models\SocialAccounts;
use common\models\messages\Messages;
use common\models\helpers\WfnmHelpers;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            /*'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],*/
            'access_app' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [

                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'eauth' => [
				// required to disable csrf validation on OpenID requests
				'class' => \nodge\eauth\openid\ControllerBehavior::className(),
				'only' => ['login'],
			],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionTestFunct(){
        \common\widgets\NotificationsWidget::widget([
            'dataProvider' => Yii::$app->appSystemData->userMessages,
        ]);
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'map-main';
        $searchModel = new FireSearchForm();
        $layersModel = new MapLegendForm();
        $layersModel->setValues();
        return $this->render('index',[
            'layersModel'=>$layersModel,
            'searchModel'=>$searchModel,
        ]);
    }

    public function actionFireSearch(){

    }


    public function actionNotification($id){
        $model = $this->getNotification($id);
        $mapData = Yii::createObject(Yii::$app->params['mapData']);
        $query = $mapData->getFireInfo($model->irwinID);

            // Yii::trace(array_keys($query),'dev');
        return $this->render('notification',[
            'model'=>$model,
            'record'=>$query,
        ]);
    }


    /**
     * Finds the Messages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Messages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function getNotification($id)
    {
        if (($model = Messages::find()->andWhere(['and',['user_id'=>Yii::$app->user->identity->id],['id'=>$id]])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $serviceName = Yii::$app->getRequest()->getQueryParam('service');
		if (isset($serviceName)) {
			/** @var $eauth \nodge\eauth\ServiceBase */
			$eauth = Yii::$app->get('eauth')->getIdentity($serviceName);
			$eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
			$eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('site/login'));

			try {
				if ($eauth->authenticate()) {
                    //User Authenticated.
                    // Yii::trace($eauth->getAttributes(),'dev');
					User::logInByEAuth($eauth);
					// special redirect with closing popup window
					$eauth->redirect();
				} else {
					// close popup window and redirect to cancelUrl
					$eauth->cancel();
				}
			} catch (\nodge\eauth\ErrorException $e) {
				// save error to show it later
				Yii::$app->getSession()->setFlash('error', 'EAuthException: '.$e->getMessage());
				// close popup window and redirect to cancelUrl
                // $eauth->cancel();
				$eauth->redirect($eauth->getCancelUrl());
			}
		}else{
            // Not a EAuth Return
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->goBack();
            } else {
                return $this->render('login', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionConnect(){
        $serviceName = Yii::$app->getRequest()->getQueryParam('service');
		if (isset($serviceName)) {
			/** @var $eauth \nodge\eauth\ServiceBase */
			$eauth = Yii::$app->get('eauth')->getIdentity($serviceName);
			$eauth->setRedirectUrl(['/user/settings/networks']);
			$eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('/user/settings/networks'));
			try {
				if ($eauth->authenticate()) {
                    $model = new SocialAccounts;
                    if(!$model->addNew($eauth)){
                        throw new InvalidParamException('This Account Has Already Been Registered To Another User');
                    };
					// special redirect with closing popup window
					$eauth->redirect();
				} else {
					// close popup window and redirect to cancelUrl
					$eauth->cancel();
				}
			} catch (\nodge\eauth\ErrorException $e) {
				// save error to show it later
				Yii::$app->getSession()->setFlash('error', 'EAuthException: '.$e->getMessage());
				// close popup window and redirect to cancelUrl
                // $eauth->cancel();
				$eauth->redirect($eauth->getCancelUrl());
			} catch  (InvalidParamException $e){
                // save error to show it later
				Yii::$app->getSession()->setFlash('error',$e->getMessage());
				// close popup window and redirect to cancelUrl
                // $eauth->cancel();
				$eauth->redirect($eauth->getCancelUrl());
            }
        }
    }

    public function actionResendConfirmation(){

        $model = new AccountConfirmationRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->reset()) {
                return $this->redirect(['site/login']);
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend confirmation link for the provided email address.');
            }
        }
        return $this->render('requestAccountConfirmation', [
            'model' => $model,
        ]);


    }

    public function actionConfirm($uid,$utk){
        $user = User::findByConfirmationToken($uid,$utk);
        if($user !== null && $user->confirmAccount() && User::login($user)){
            Yii::$app->session->setFlash('success', 'Account Confirmed. Enjoy!');
        }else{
            Yii::$app->session->setFlash('error', 'Account Confirmation Failed.');
        }
        return $this->goHome();
    }

    public function actionUpdatePassword($uid,$token){
        $user = User::findByPasswordRecoveryToken($uid,$token);
        if($user == null){
            return $this->redirect(['site/login']);
        }
        $model = new UpdatePasswordForm($user);
        if (Yii::$app->request->isPost &&  $model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');
            User::login($user);
            return $this->goHome();
        }
        return $this->render('update-password', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm([]);
        // $model->fillProfile = true;
        if ($model->load(Yii::$app->request->post()) && ($user = $model->register())) {
            return $this->redirect(['site/login']);
        }
        if($model->fillProfile){
            $listData['months'] = DatePicker::getMonths();
            $listData['years'] = DatePicker::getYears();
            $listData['days'] = DatePicker::getDays();
            $listData['genders'] = WfnmHelpers::getGenderList();
            $listData['states'] = ArrayHelper::map(WfnmHelpers::get_states_list(),'abbr','name');
        }else{
            $listData = [];
        }
        return $this->render('signup', [
            'model' => $model,
            'listData' => $listData,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->reset()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->redirect(['site/login']);
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->redirect(['site/login']);
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
