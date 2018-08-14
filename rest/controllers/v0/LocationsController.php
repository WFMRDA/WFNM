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
use common\models\myLocations\MyLocations;
use common\models\myLocations\MyLocationsSearch;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use yii\data\DataFilter;

class LocationsController extends Controller
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
        $query = $this->getAllRecords();

        return Yii::createObject([
           'class' => ActiveDataProvider::className(),
           'query' => $query,
           'pagination' => [
               'params' => $requestParams,
           ],
           'sort' => [
               'params' => $requestParams,
           ],
        ]);
    }

    /**
     * Creates a new model.
     * @return \yii\db\ActiveRecordInterface the model newly created
     * @throws ServerErrorHttpException if there is any error when creating the model
     */
    public function actionCreate()
    {
        $model = new MyLocations([
            'user_id' => Yii::$app->user->identity->id
        ]);

        $requestParams = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
        $model->load($requestParams, '');
        if ($model->save()) {
            // $response = Yii::$app->getResponse();
            // $response->setStatusCode(201);
            // $id = implode(',', array_values($model->getPrimaryKey(true)));
            // Yii::trace(Url::toRoute(['view', 'id' => $id], true),'dev');
            // $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
        }  elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $this->getAllRecords()->asArray()->all();
        // return  MyLocations::find()->andWhere(['user_id' => Yii::$app->user->identity->id])->orderBy(['id' => SORT_ASC])->asArray()->all();
    }

    /**
     * Deletes a model.
     * @throws ServerErrorHttpException on failure.
     */
    public function actionDelete()
    {
        $requestParams = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
        $model = $this->findModel(ArrayHelper::getValue($requestParams,'lid'));
        if ($model->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }
        $query = $this->getAllRecords();

        return Yii::createObject([
           'class' => ActiveDataProvider::className(),
           'query' => $query,
           'pagination' => [
               'params' => $requestParams,
           ],
           'sort' => [
               'params' => $requestParams,
           ],
        ]);
        // Yii::$app->getResponse()->setStatusCode(204);
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
        if ($id !== null && ($model = MyLocations::find()->where(['and',['user_id' => Yii::$app->user->identity->id],['id' => $id]])->one()) !== null) {
            return $model;
        } else {
            throw new ServerErrorHttpException('Record Not Found');
        }
    }

    protected function getAllRecords(){
        return MyLocations::find()->andWhere(['user_id' => Yii::$app->user->identity->id])->orderBy(['id' => SORT_ASC]);
    }
}
