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

use common\modules\User\models\Profile;
use common\modules\User\models\SettingsForm;
use common\modules\User\helpers\DatePicker;
use common\modules\User\helpers\UserHelpers;
use yii\helpers\ArrayHelper;
use common\modules\User\Module;
use common\modules\User\traits\AjaxValidationTrait;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use common\modules\User\models\SocialAccounts;
use yii\base\InvalidParamException;
/**
 * SettingsController manages updating user settings (e.g. profile, email and password).
 *
 * @property \common\modules\User\Module $module
 *
 * @author Reginald Goolsby <rjgoolsby@pyrotechsolutions.com>
 */
class SettingsController extends Controller
{
    use AjaxValidationTrait;
    /** @inheritdoc */
    public $defaultAction = 'profile';

    /**
     * @param string           $id
     * @param \yii\base\Module $module
     * @param Finder           $finder
     * @param array            $config
     */
    public function __construct($id, $module,$config = [])
    {
        parent::__construct($id, $module, $config);
    }

    /** @inheritdoc */
/*    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'disconnect' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['profile', 'account', 'confirm', 'networks', 'disconnect','connect'],
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }*/

    /**
     * Shows profile settings form.
     *
     * @return string|\yii\web\Response
     */
    public function actionProfile()
    {
        $model = $this->findProfileById(Yii::$app->user->identity->getId());

        if ($model == null) {
            $model = Yii::createObject(Profile::className());
            $model->link('user', Yii::$app->user->identity);
        }

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success',  'Your profile has been updated');
            return $this->refresh();
        }

        $listData['months'] = DatePicker::getMonths();
        $listData['years'] = DatePicker::getYears();
        $listData['days'] = DatePicker::getDays();
        $listData['genders'] = UserHelpers::getGenderList();
        $listData['states'] = ArrayHelper::map(UserHelpers::get_states_list(),'abbr','name');

        return $this->render('profile', [
            'model' => $model,
            'listData' => $listData,
        ]);
    }

    /**
     * Displays page where user can update account settings (username, email or password).
     *
     * @return string|\yii\web\Response
     */
    public function actionAccount()
    {
        /** @var SettingsForm $model */
        $model = Yii::createObject(SettingsForm::className());

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Your account details have been updated');
            return $this->refresh();
        }

        return $this->render('account', [
            'model' => $model,
        ]);
    }

    /**
     * Attempts changing user's email address.
     *
     * @param int    $id
     * @param string $code
     *
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionConfirm($id, $code)
    {
        $user = $this->finder->findUserById($id);

        if ($user === null || $this->module->emailChangeStrategy == Module::STRATEGY_INSECURE) {
            throw new NotFoundHttpException();
        }

        $user->attemptEmailChange($code);

        return $this->redirect(['account']);
    }

    /**
     * Displays list of connected network accounts.
     *
     * @return string
     */
    public function actionNetworks()
    {
        return $this->render('networks', [
            'user' => Yii::$app->user->identity,
        ]);
    }

    /**
     * Disconnects a network account from user.
     *
     * @param int $id
     *
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionDisconnect($id)
    {
        $account = $this->getUserSocialAccount($id);

        if ($account === null) {
            throw new NotFoundHttpException();
        }

        $account->delete();

        return $this->redirect(['networks']);
    }


    protected function getUserSocialAccount($id){
        return SocialAccounts::find()->where(['and',['user_id'=>Yii::$app->user->identity->id,'id'=>$id]])->one();
    }

    protected function findProfileById()
    {

        if (($model = Profile::findOne(['user_id'=>Yii::$app->user->getId()])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
