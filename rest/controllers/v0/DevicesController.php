<?php
namespace rest\controllers\v0;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use rest\models\jwt\JwtHttpBearerAuth;
use yii\data\ArrayDataProvider;
use rest\models\User;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\base\InvalidParamException;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use yii\data\DataFilter;
use common\models\helpers\WfnmHelpers;


use common\models\devices\DeviceLocations;
use common\models\devices\DeviceList;

use common\models\devices\DeviceLocationsSearch;
use common\models\devices\DeviceListSearch;

class DevicesController extends Controller{

    public function behaviors()
	{
	    $behaviors = parent::behaviors();
        return ArrayHelper::merge($behaviors, [
	        'authenticator' => [
                'class' => JwtHttpBearerAuth::className(),
		    ],
	    ]);
	}


	public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['view'],$actions['create'], $actions['update'],$actions['delete'], $actions['options']);
        // unset($actions['index'],$actions['create']);
        return $actions;
    }


    /**
     * Lists all My Alerts .
     * @return Array
     */
    public function actionIndex()
    {
        $requestParams = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);

    }

    public function actionStore(){
        $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
        $deviceId = ArrayHelper::getValue($params,'deviceId');
        $model = $this->findDeviceModel($deviceId);
        $model->token = ArrayHelper::getValue($params,'token');
        if(!$model->save()){
            throw new ServerErrorHttpException(\yii\helpers\VarDumper::dumpAsString($model->errors));
        }
        return $model;
    }

    public function actionLocation(){
        $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
        $deviceId = ArrayHelper::getValue($params,'deviceId');
        $model = $this->findLocationModel($deviceId);
        $model->latitude = ArrayHelper::getValue($params,'latitude');
        $model->longitude = ArrayHelper::getValue($params,'longitude');
        if(!$model->save()){
            throw new ServerErrorHttpException(\yii\helpers\VarDumper::dumpAsString($model->errors));
        }
        return $model;
    }

    /**
     * Finds the DeviceList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return DeviceList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findDeviceModel($id)
    {
        if (($model = DeviceList::findOne(['device_id'=>$id])) == null) {
            $model = new DeviceList(['device_id'=>$id]);
        }
        $model->user_id = Yii::$app->user->identity->id;
        return $model;
    }

    protected function findLocationModel($id)
    {
        if (($model = DeviceLocations::findOne(['device_id'=>$id])) == null) {
            $model = new DeviceLocations(['device_id'=>$id]);
        }
        return $model;
    }
}
