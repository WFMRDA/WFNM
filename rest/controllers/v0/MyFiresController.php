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
use yii\data\ActiveDataProvider;
use rest\models\User;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use common\models\myFires\MyFires;
use common\models\myFires\MyFiresSearch;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use yii\data\DataFilter;
use common\models\helpers\WfnmHelpers;

class MyFiresController extends Controller
{

    public function behaviors()
	{
	    $behaviors = parent::behaviors();
        return ArrayHelper::merge($behaviors, [
	        'authenticator' => [
                'class' => JwtHttpBearerAuth::className(),
		    ]
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
     * Lists all MyLocations models.
     * @return ActiveDataProvider
     */
    public function actionIndex()
    {

        $requestParams = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
        return $this->getAllRecords();
    }

    /**
     * Creates a new model.
     * @return \yii\db\ActiveRecordInterface the model newly created
     * @throws ServerErrorHttpException if there is any error when creating the model
     */
    public function actionCreate()
    {

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
        return ['data'=>$this->getAllRecords(), 'status' => WfnmHelpers::isUserFollowing(Yii::$app->user->identity->id,$fireId)];

        // return  MyLocations::find()->andWhere(['user_id' => Yii::$app->user->identity->id])->orderBy(['id' => SORT_ASC])->asArray()->all();
    }

    /**
     * Deletes a model.
     * @throws ServerErrorHttpException on failure.
     */
    public function actionDelete()
    {
        $requestParams = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
        $fireId = ArrayHelper::getValue($requestParams,'fid');
        $model = $this->getFireFollowModel($fireId);
        if($model !== null){
            if(!$model->delete()){
                throw new ServerErrorHttpException($model->errors);
            }
        }else{
            throw new ServerErrorHttpException('You are not monitoring this fire');
        }
        return ['data'=>$this->getAllRecords(), 'status' => WfnmHelpers::isUserFollowing(Yii::$app->user->identity->id,$fireId)];
    }

    /**
     * Finds the MyLocations model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  string $id
     * @return MyLocations the loaded model
     * @throws ServerErrorHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if ($id !== null && ($model = MyFires::find()->where(['and',['user_id' => Yii::$app->user->identity->id],['irwinID' => $id]])->one()) !== null) {
            return $model;
        } else {
            throw new ServerErrorHttpException('Record Not Found');
        }
    }
    protected function getFireFollowModel($fireId){
        return MyFires::find()->where(['and',['user_id'=> Yii::$app->user->identity->id,'irwinID'=>$fireId]])->one();
    }
    protected function getAllRecords(){
        return WfnmHelpers::getMyFires();
    }
}
