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

use common\modules\User\models\RecoveryForm;
use common\modules\User\traits\AjaxValidationTrait;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use common\modules\User\models\User;
use common\modules\User\models\UpdatePasswordForm;
use common\modules\User\models\PasswordResetRequestForm;


class RecoveryController extends Controller
{
    use AjaxValidationTrait;

    /**
     * @param string           $id
     * @param \yii\base\Module $module
     * @param array            $config
     */
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    /** @inheritdoc */
 /*   public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['request', 'reset'], 'roles' => ['?']],
                ],
            ],
        ];
    }*/

    /**
     * Shows page where user can request password recovery.
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRequest()
    {        
        if (!$this->module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }

        /** @var RecoveryForm $model */
        $model = Yii::createObject([
            'class'    => PasswordResetRequestForm::className(),
        ]);

        $this->performAjaxValidation($model);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->reset()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->redirect(Url::to('/user/login'));
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('request', [
            'model' => $model,
        ]);
    }
    /**
     * Displays page where user can reset password.
     *
     * @param int    $id
     * @param string $code
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */

    
    public function actionReset($uid,$token){
        if (!$this->module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }

        $user = User::findByPasswordRecoveryToken($uid,$token);

        if($user == null){
            return $this->redirect(['user/login']);
        }


        $model = new UpdatePasswordForm($user);

        if (Yii::$app->request->isPost &&  $model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');
            User::login($user);
            return $this->goHome();
        }

        return $this->render('reset', [
            'model' => $model,
            'module' => $this->module,
        ]);
    }

    public function actionUpdatePassword($uid,$token){
        $user = User::findByPasswordRecoveryToken($uid,$token);
       /* if($user == null){
            return $this->redirect(['site/login']);
        }*/
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

}
