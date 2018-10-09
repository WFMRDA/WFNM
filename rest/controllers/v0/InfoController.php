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
use yii\base\InvalidParamException;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use yii\data\DataFilter;
use common\models\helpers\WfnmHelpers;

class InfoController extends Controller{

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

    public function actionSitRep(){
        $mapData = Yii::createObject(Yii::$app->params['mapData']);
        $mapData = Yii::createObject(Yii::$app->params['mapData']);
        return [
            'prepardnessLevl' => $mapData->getPrepardnessLevel('NIC'),
            'report' => $mapData->sitReportInfo
        ];
    }

    public function actionFireInfo(){
        $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
        $fireId = ArrayHelper::getValue($params,'fid');
        $query = WfnmHelpers::getFireInfo($fireId);
        $prepLevel = WfnmHelpers::getPrepLevel($query['gacc']);
        $isFollowing = WfnmHelpers::isUserFollowing(Yii::$app->user->identity->id,$fireId);
        return ['fireInfo'=>$query,'localGaccPlLevel'=> $prepLevel,'isFollowing'=>$isFollowing];
    }

    public function actionStoreDisclaimer(){
        if (Yii::$app->request->isAjax){
            $isNew = false;
            $id = Yii::$app->user->identity->id;
            $model = PopTable::find()
                ->andWhere(['user_id'=> Yii::$app->user->identity->id])
                ->andWhere(['type' => PopTable::DISCLAIMER])
                ->one();
            // Yii::trace($model,'dev');
            if($model == null){
                $model = Yii::createObject([
                    'class' => PopTable::className(),
                    'user_id' => $id,
                    'type' => PopTable::DISCLAIMER,
                    'seen_at' => time(),
                ]);
                $isNew = true;
            }
            $model->seen_at = time();
            if ($model->save($isNew)) {
                Yii::$app->getResponse()->setStatusCode(204);
            } elseif ($model->hasErrors() && !$model->update()) {
                throw new ServerErrorHttpException(\yii\helpers\VarDumper::dumpAsString($model->errors));
            }else{
                throw new ServerErrorHttpException('Failed to Save Settings for unknown reason.');
            }
        }
    }
}
