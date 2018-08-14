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
use common\models\messages\Messages;
use common\models\popup\PopTable;

class MyAlertsController extends Controller{

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
        return $this->getAllRecords();
    }

    public function actionGetAlert(){
        $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
        $id = ArrayHelper::getValue($params,'id');
        if($id == null){
            throw new InvalidParamException('Id Required');
        }
        $model = Messages::find()->where( ['and',['user_id'=> Yii::$app->user->identity->id,'id'=>$id]])->one();
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

    public function actionCheckAlerts(){
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
            return $this->getAllRecords();
        }
    }

    public function actionMarkAllNotificationSeen(){
        Messages::updateAll(['seen_at' => time()], ['user_id'=>Yii::$app->user->identity->id]);
        return $this->getAllRecords();
    }

    protected function getAllRecords(){
        return WfnmHelpers::findMyAlerts();
    }
}