<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\user\UserSettings;



/**
 * System Controller
 */
class SystemRestController extends Controller
{
    public function actionStoreSettings(){
        if (Yii::$app->request->isAjax){
            $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            $model = new UserSettings;
            // Yii::trace($params,'dev');
            return ['success' => $model->findSettings($params)];
        }
    }
/*    protected function findSettings($id)
    {

        if (($model = UserSettings::findOne(['user_id'=> Yii::$app->user->identity->id, 'key'=>])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }*/
}
