<?php
namespace rest\controllers\v0;

use Yii;
use yii\rest\Controller;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\base\InvalidParamException;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use yii\data\DataFilter;
use common\models\helpers\WfnmHelpers;
use common\models\fireCache\FireCacheSearch;

class FireSearchController extends Controller{

    public function actionTest(){
       return ['response' => 'Yup Scottie, you got me'];
    }
    
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
    /**
     * Return Query from Fire Cache.
     * @return ActiveDataProvider
     */
    public function actionIndex()
    {
        $requestParams = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
        if(!isset($requestParams['q'])){
			throw new BadRequestHttpException('Query must be set using the variable \'q\'');
		}
        $searchModel = new FireCacheSearch();
        return $searchModel->searchRest($requestParams);
    }

    public function actionFireInfo(){
        $requestParams = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
        $searchModel = new FireCacheSearch();
        return $searchModel->searchInfo($requestParams);
        return $requestParams;
        if(!isset($requestParams['q'])){
			throw new BadRequestHttpException('Query must be set using the variable \'q\'');
		}
        $searchModel = new FireCacheSearch();
        return $searchModel->searchInfo($requestParams);
    }

}
