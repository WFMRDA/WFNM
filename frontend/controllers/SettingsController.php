<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;


use common\models\user\Profile;
use common\models\helpers\WfnmHelpers;
use common\models\user\DefaultLocation;
use yii\web\Response;
use yii\bootstrap\ActiveForm;

class SettingsController extends \ptech\pyrocms\controllers\user\SettingsController
{
    public function actionProfileAlertAjaxValidate(){

        $model = new Profile;
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    public function actionClearLocation(){
        $model = DefaultLocation::findOne(['user_id' => Yii::$app->user->identity->id]);
        if($model != null){
            $model->delete();
        }
        return $this->redirect(['prefs']);

    }

    public function actionPrefs(){
        $id = Yii::$app->user->identity->id;
        $model = $this->findProfileModel($id);
        $defaultLocation = $this->findDefaultLocationModel();

        if ($model->load(Yii::$app->request->post()) && $defaultLocation->load(Yii::$app->request->post()) && $model->save() && $defaultLocation->save()) {
            Yii::$app->getSession()->setFlash('success','Your profile has been updated');
            // return $this->refresh();
        }
        // Yii::trace($model->errors,'dev');
        // Yii::trace($defaultLocation->errors,'dev');


        return $this->render('preferences',[
            'model' => $model,
            // 'listData' => $listData,
            'defaultLocation' => $defaultLocation,
        ]);
    }

    protected function findProfileModel($id)
    {
        if (($model = Profile::findOne(['user_id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findDefaultLocationModel()
    {
        if (($model = DefaultLocation::findOne(['user_id' => Yii::$app->user->identity->id])) === null) {
            $model = \Yii::createObject([
                'class'          => DefaultLocation::className(),
                'user_id'       => Yii::$app->user->identity->id,
            ]);
        }
        return $model;
    }
}
