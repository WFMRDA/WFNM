<?php
namespace rest\controllers\v0;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use rest\models\jwt\JwtHttpBearerAuth;
use yii\data\ActiveDataProvider;
use rest\models\User;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

class LocationsController extends Controller
{

    public function behaviors()
	{
	    $behaviors = parent::behaviors();
        return ArrayHelper::merge($behaviors, [
	        'authenticator' => [
                'class' => JwtHttpBearerAuth::className(),    
		    ],
		    'corsFilter' => [
	            'class' => \yii\filters\Cors::className(),
	             'cors' => [
	                'Origin' => ['*'],
			        'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
			        'Access-Control-Request-Headers' => ['*'],
			        'Access-Control-Allow-Credentials' => null,
			        'Access-Control-Max-Age' => 86400,
			        'Access-Control-Expose-Headers' => [],
	            ],
	        ],
	    ]);
	}


	public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['view'],$actions['create'], $actions['update'],$actions['delete'], $actions['options']);
        return $actions;
    }

	public function actionIndex()
    {
        $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
            return ['rest' => 'here'];
    }

}
