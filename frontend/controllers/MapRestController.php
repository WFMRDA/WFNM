<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\sitReport\SitReportData;
use common\models\vulcan\VulcanMonitoring;
use common\models\myFires\MyFires;
use common\models\helpers\WfnmHelpers;
use common\models\myLocations\MyLocationsForm;
use common\models\myLocations\MyLocations;
use common\models\messages\Messages;
use yii\web\ServerErrorHttpException;
use common\models\popup\PopTable;


/**
 * MapRest Controller
 */
class MapRestController extends Controller
{
    public function init(){
        parent::init();
        $this->setViewPath('@frontend/views/map-panels');
    }

    public function actionFires(){
        if (Yii::$app->request->isPost){
            return WfnmHelpers::getMapFires();
        }
    }

    public function actionFireInfo(){
        if (Yii::$app->request->isAjax){
            $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            $fireId = ArrayHelper::getValue($params,'fid');
            $query = WfnmHelpers::getFireInfo($fireId);
            $prepLevel = WfnmHelpers::getPrepLevel($query['gacc']);
            $isFollowing = WfnmHelpers::isUserFollowing(Yii::$app->user->identity->id,$fireId);
            // Yii::trace($prepLevel,'dev');
            // Yii::trace($query['gacc'],'dev');
            Yii::trace($query,'dev');
            return ['fireInfo'=>$query,'localGaccPlLevel'=> $prepLevel,'isFollowing'=>$isFollowing];
        }
    }

    public function actionFiresNearMe(){
        if (Yii::$app->request->isAjax){
            $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            $lat = ArrayHelper::getValue($params,'lat');
            $lng = ArrayHelper::getValue($params,'lng');
            $address = ArrayHelper::getValue($params,'address');
            $mapData = Yii::createObject(Yii::$app->params['mapData']);
            // Yii::trace($params,'dev');
            if($lng == null || $lat == null){
                throw new InvalidParamException('Lat Lng Required');
            }
            $mapData->userData = [
                'longitude' => $lng,
                'latitude' =>  $lat,
                'distance' => 25,
            ];
            $data = $mapData->getFiresNearUserLocation();
            // Yii::trace($data,'dev');
            $prepLevel = WfnmHelpers::getPrepLevel($data['gacc']);
            return [
                'fireInfo'=> $data['fireInfo'],
                'gacc' => $data['gacc'],
                'localGaccPlLevel'=> $prepLevel
            ];
        }
    }

    public function actionMarkAllNotificationSeen(){
        Messages::updateAll(['seen_at' => time()], ['user_id'=>Yii::$app->user->identity->id]);
        return WfnmHelpers::findMyAlerts();
    }

    public function actionGetAlert(){
        if (Yii::$app->request->isAjax){
            $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            $id = ArrayHelper::getValue($params,'id');
            if($id == null){
                throw new InvalidParamException('Id Required');
            }
            $model = Messages::find()->where(['id'=>$id])->one();
            if($model == null){
                throw new InvalidParamException('Alert Not Found');
            }
            if($model != null && ($model->seen_at == null || $model->seen_at == 0)){
                $model->updateAttributes(['seen_at'=>time()]);
            }

            $fire = WfnmHelpers::getFireInfo($model->irwinID);
            return [
                'fireInfo' => $fire,
                'alerts' => WfnmHelpers::findMyAlerts(),
            ];
        }
    }

    public function actionUnfollowFire(){
        if (Yii::$app->request->isAjax){
            $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            $fireId = ArrayHelper::getValue($params,'fid');
            $model = $this->getFireFollowModel($fireId);
            if($model !== null){
                if(!$model->delete()){
                    throw new ServerErrorHttpException($model->errors);
                }
            }else{
                throw new ServerErrorHttpException('You are not monitoring this fire');
            }
            return ['data'=>$this->findMyFires(), 'status' => WfnmHelpers::isUserFollowing(Yii::$app->user->identity->id,$fireId)];
        }
    }

    protected function getFireFollowModel($fireId){
        return MyFires::find()->where(['and',['user_id'=> Yii::$app->user->identity->id,'irwinID'=>$fireId]])->one();
    }

    public function actionFollowFire(){
        if (Yii::$app->request->isAjax){
            $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            $fireId = ArrayHelper::getValue($params,'fid');
            $model = $this->getFireFollowModel($fireId);
            $mapData = Yii::createObject(Yii::$app->params['mapData']);
            $query = $mapData->getFireInfo($fireId);
            if($model == null){
                $model = Yii::createObject([
                    'class'=> MyFires::className(),
                    'user_id'=>Yii::$app->user->identity->id,
                    'irwinID'=>$fireId,
                    'name'=> $query['incidentName'],
                ]);
                if(!$model->save()){
                    throw new ServerErrorHttpException($model->errors);
                }
            }else{
                throw new ServerErrorHttpException('You Are Already Monitoring This Fire');
            }

            return ['data'=>$this->findMyFires(), 'status' => WfnmHelpers::isUserFollowing(Yii::$app->user->identity->id,$fireId)];
        }
    }

    public function actionCheckAlerts(){
        if (Yii::$app->request->isAjax){
            $model = PopTable::find()
               ->andWhere(['and',
                    ['user_id' => Yii::$app->user->identity->id],
                    ['type' => PopTable::NOTIFICATIONS]
                ])->one();
                    // Yii::trace($query->attributes,'dev');
            if($model == null){
                $model = new PopTable([
                    'user_id' => Yii::$app->user->identity->id,
                    'type' => PopTable::NOTIFICATIONS,
                    'seen_at' => time(),
                ]);
            } else{
                $model->seen_at = time();
            }
            if(!$model->save()){
                throw new ServerErrorHttpException($model->errors);
            }else{
                return WfnmHelpers::findMyAlerts();
            }
        }
    }

    public function actionSitRep(){
        if (Yii::$app->request->isAjax){
            return WfnmHelpers::getSitReportData();
        }
    }

    public function actionMyFires(){
        if (Yii::$app->request->isAjax){
            return $this->findMyFires();
        }
    }

    public function actionAlerts(){
        if (Yii::$app->request->isAjax){
            $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            return $this->findMyAlerts(ArrayHelper::getValue($params,'offset',0));
        }
    }

    /**
     * Finds the MyFires model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MyFires the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findMyFires()
    {
        return WfnmHelpers::getMyFires();
    }

    protected function findMyAlerts($offset = 0){
        return WfnmHelpers::findMyAlerts($offset);
    }

    public function actionMyLocations(){
        if (Yii::$app->request->isAjax){
            // $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            return $this->findMyLocations();
        }
    }
    /**
     * Finds the MyFires model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MyFires the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findMyLocations()
    {
        return WfnmHelpers::findMyLocations();

    }
    protected function findMyLocation($id)
    {
        return $model = MyLocations::findOne(['user_id'=>Yii::$app->user->identity->id,'place_id'=>$id]);

    }

    public function actionAddLocation(){
        if (Yii::$app->request->isAjax){
            $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            // Yii::trace($params,'dev');
            $model = Yii::createObject([
                'class'=> MyLocations::className(),
                'user_id'=>Yii::$app->user->identity->id,
            ]);
            $model->load($params,'');
            $model->save();
            return $this->findMyLocations();
        }
    }

    public function actionUnfollowLocation(){
        if (Yii::$app->request->isAjax){
            $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            Yii::trace($params,'dev');
            $model = $this->findMyLocation($params['pid']);
            if($model !== null){
                $model->delete();
            }
            return $this->findMyLocations();
        }
    }

}
