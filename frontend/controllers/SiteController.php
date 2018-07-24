<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\helpers\WfnmHelpers;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        //User Info
        /*
        1. MyFires
        2. Alerts
        3. MyLocations
        4. Sit Report Data
        5. Map Fires
        6. Map Layers Last Chosen
        */
        // $myFires = WfnmHelpers::getMyFires();
        // $alerts = WfnmHelpers::findMyAlerts();
        // $myLocations = WfnmHelpers::findMyLocations();
        // $sitReport = WfnmHelpers::getSitReportData();
        // $mapFires = WfnmHelpers::getMapFires();
        // $layers = Yii::$app->appSystemData->mapLayers
        // Yii::$app->appSystemData->getUser()
        // $fireData  = WfnmHelpers::getFireData();
        // Yii::trace(array_keys($fireData),'dev');
        // $fireData = WfnmHelpers::getFireData();
        $this->layout = 'map-main';
        return $this->render('index',[
        ]);
    }


}
