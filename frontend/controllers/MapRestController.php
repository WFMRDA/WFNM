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
            $mapData = Yii::createObject(Yii::$app->params['mapData']);
            $addtlLayers = ArrayHelper::getValue(Yii::$app->systemData->mapLayers,'addtlLayers');
            return ['wfnm' => $mapData->getWfnmGeoJsonLayer(),'addtlLayers'=>$addtlLayers];
        }
    }

    public function actionFireInfo(){
        if (Yii::$app->request->isAjax){
            $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            $fireId = ArrayHelper::getValue($params,'fid');
            $alertId = ArrayHelper::getValue($params,'aid');
            // Yii::trace($fireId,'dev');
            // Yii::trace($alertId,'dev');
            if($alertId != null){
                $this->checkSeen($alertId);
                $response['header'] = \common\widgets\NotificationsWidget::widget([
                    'dataProvider' => Yii::$app->systemData->userMessages,
                ]);
            }

            $query = WfnmHelpers::getFireInfo($fireId);
            $response['html'] = $this->renderAjax('fireinfo', [
                'irwin' => $query,
                'fireId'=>$fireId,
            ]);
            $response['coords'] = ['lat'=> $query['pooLatitude'], 'lon'=>$query['pooLongitude']];
            return $response;
            // return ['header'=>$header,'html'=>$html,'coords'=>['lat'=> $query['pooLatitude'], 'lon'=>$query['pooLongitude']]];
        }
    }

    protected function checkSeen($aid){
        list($tag,$id) = explode('-', $aid);
        $query = Messages::find()
           ->andWhere(['and',
                ['user_id' => Yii::$app->user->identity->id],
                ['id' => $id]
            ])->one();
                // Yii::trace($query->attributes,'dev');
        if($query != null && ($query->seen_at == null || $query->seen_at == 0)){
            // Yii::trace($query->attributes,'dev');
            $query->updateAttributes(['seen_at'=>time()]);
        }
    }

    public function actionUnfollowFire(){
        if (Yii::$app->request->isAjax){
            $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            $fireId = ArrayHelper::getValue($params,'fid');
            $html = WfnmHelpers::unFollowfire($fireId);
            return ['html'=>$html];
        }
    }

    public function actionFollowFire(){
        if (Yii::$app->request->isAjax){
            $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            $fireId = ArrayHelper::getValue($params,'fid');
            $html = WfnmHelpers::followfire($fireId);
            return ['html'=>$html];
        }
    }

    public function actionSitRep(){
        if (Yii::$app->request->isAjax){
            $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            $mapData = Yii::createObject(Yii::$app->params['mapData']);
            $emergingFireDataProvider = $mapData->getEmergingFiresDataProvider();
            $newFireDataProvider = $mapData->getNewFiresDataProvider();
            $sitReport = $mapData->getSitReportInfo();
            // $pl = $mapData->getPrepardnessLevel('NIC');
            $html = $this->renderAjax('sitrep', [
                'emergingFireDataProvider' => $emergingFireDataProvider,
                'newFireDataProvider' => $newFireDataProvider,
                'sitReport'=>$sitReport,
                // 'pl'=>$pl,
            ]);
            return ['html'=>$html];
        }
    }

    public function actionMyFires(){
        if (Yii::$app->request->isAjax){
            $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            $models = $this->findMyFires();
            // Yii::trace($models,'dev');
            $html = $this->renderAjax('my-fires', [
                'models' => $models,
            ]);
            return ['html'=>$html];
        }
    }

    public function actionAlerts(){
        if (Yii::$app->request->isAjax){
            $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            $html = $this->renderAjax('alerts');
            return ['html'=>$html];
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
        return $model = MyFires::findAll(['user_id'=>Yii::$app->user->identity->id]);

    }

    public function actionMyLocations(){
        if (Yii::$app->request->isAjax){
            $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            $model = new MyLocationsForm;
            $models = $this->findMyFires();
            $html = $this->renderAjax('my-locations', [
                'model' => $model,
                'models' => $this->findMyLocations(),
            ]);
            return ['html'=>$html];
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
        return $model = MyLocations::findAll(['user_id'=>Yii::$app->user->identity->id]);

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
            $html = $this->renderAjax('_mylocationstable',['models'=>$this->findMyLocations()]);
            return ['html'=>$html,'params'=>$params];
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
            $html = $this->renderAjax('_mylocationstable',['models'=>$this->findMyLocations()]);
            return ['html'=>$html,'params'=>$params];
            // return ['html'=>$params];
        }
    }

    public function actionFiresNearMe(){
        if (Yii::$app->request->isAjax){
            $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            $coords = ArrayHelper::getValue($params,'coords');
            $address = ArrayHelper::getValue($params,'coords.address');
            $mapData = Yii::createObject(Yii::$app->params['mapData']);

            // Yii::trace($params,'dev');
            if($coords !== null){
                $mapData->userData = [
                    'longitude'=> $coords['lng'],
                    'latitude'=>  $coords['lat'],
                ];
                $response['coords'] = ['lat'=> $coords['lat'], 'lon'=>$coords['lng']];
            }else{

            }

            $models = $mapData->getFiresNearUserLocation();

            //Get User Fire Locations;
            $html = $this->renderAjax('firesnearme', [
                'mapData' => $mapData,
                'models' => $models,
                'address' => $address,
                'myLocations' => $this->findMyLocations(),
            ]);
            $response['html'] = $html;
            // Yii::trace($response,'dev');
            return $response;
        }
    }

    /**
     * Your controller action to fetch the list
     */
    public function actionFireList() {
        if (Yii::$app->request->isAjax){
            $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            $mapData = Yii::createObject(Yii::$app->params['mapData']);
            $fires = $mapData->searchWfnmData($params);
            $out = [];

            // Yii::trace($fires,'dev');
            // $searchModel = new IrwinDbSearch;
            // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            foreach ($fires as $key => $value) {
                if(!WfnmHelpers::inString(ArrayHelper::getValue($params,'incident'),$value['incidentName'])){
                    continue;
                }
                $out[] = ['value' => $value['incidentName'] . ' Fire, ' . str_replace('US-', '', $value['pooState']),'id' => $value['irwinID'] ];
            }
            return $out;
        }
    }
}
