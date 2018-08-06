<?php
namespace rest\controllers\v0;

use Yii;
use yii\rest\Controller;
// use yii\rest\ActiveController as Controller;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use rest\models\jwt\JwtHttpBearerAuth;
use yii\data\ArrayDataProvider;
use rest\models\User;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use yii\data\DataFilter;

class FiresNearMeController extends Controller{

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
     * Lists all Fires Near User Monitored location.
     * @return ActiveDataProvider
     */
    public function actionIndex()
    {
        $requestParams = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);

		if(!isset($requestParams['lon'])){
			throw new BadRequestHttpException('Longitude must be set using the variable \'lon\'');
		}
		if(!isset($requestParams['lat'])){
			throw new BadRequestHttpException('Latitude must be set using the variable \'lat\'');
		}
        $mapData = Yii::createObject(Yii::$app->params['mapData']);
        $mapData->userData = [
            'longitude' => $requestParams['lon'],
            'latitude' =>  $requestParams['lat'],
            'distance' =>  ArrayHelper::getValue($requestParams,'distance',25),
        ];
        $query = $mapData->getFiresNearUserLocation();

        return Yii::createObject([
           'class' => ArrayDataProvider::className(),
           'allModels' => $query,
           'pagination' => [
               'params' => $requestParams,
           ],
           'sort' => [
               'params' => $requestParams,
           ],
        ]);
    }

}
