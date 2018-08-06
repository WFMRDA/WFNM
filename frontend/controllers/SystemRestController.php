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
use common\models\popup\PopTable;
use yii\web\ServerErrorHttpException;



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
            // return ['success' => $params];
            if($model->setSettings($params)){
                Yii::$app->getResponse()->setStatusCode(204);
            }else{
                throw new ServerErrorHttpException('Failure to Save Settings');
            }
        }
    }

    public function actionStoreDisclaimer(){
        if (Yii::$app->request->isAjax){
            $id = Yii::$app->user->identity->id;
            $model = PopTable::findOne(['user_id'=> Yii::$app->user->identity->id, 'type' => PopTable::DISCLAIMER]);
            if($model == null){
                $model = Yii::createObject([
                    'class' => PopTable::className(),
                    'user_id' => $id,
                    'type' => PopTable::DISCLAIMER,
                    'seen_at' => time(),
                ]);

            }
            $model->seen_at = time();
            if ($model->save()) {
                Yii::$app->getResponse()->setStatusCode(204);
            } elseif ($model->hasErrors() && !$model->update()) {
                throw new ServerErrorHttpException(\yii\helpers\VarDumper::dumpAsString($model->errors));
            }else{
                throw new ServerErrorHttpException('Failed to Save Settings for unknown reason.');
            }

            // if($model->save()){
            //     Yii::$app->getResponse()->setStatusCode(204);
            // }else{
            //     // Yii::trace($model->errors,'dev');
            //     throw new ServerErrorHttpException('Failure to Save Settings');
            // }
        }
    }

    public function actionGetDisclaimer(){
        if (Yii::$app->request->isAjax){
            $id = Yii::$app->user->identity->id;
            $model = PopTable::findOne(['user_id'=> Yii::$app->user->identity->id, 'type' => PopTable::DISCLAIMER]);
            $success = ($model == null)?'show':'hide';
            // Yii::trace($model->attributes,'dev');
            return ['success' => $success];
        }
    }


    protected function seenDisclaimer($id){
        return PopTable::findOne(['user_id'=> Yii::$app->user->identity->id, 'type' => PopTable::DISCLAIMER]);
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
