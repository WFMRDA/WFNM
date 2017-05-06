<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use common\models\system\System;
use yii\helpers\Url;
use common\models\helpers\WfnmHelpers;
use yii\helpers\ArrayHelper;
use common\models\messages\Messages;



class WebHookController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                ],
            ],
        ];
    }


    public function actionCount($key){

        if($key == Yii::$app->params['webhook-key']){
            $c = Messages::find()
                ->andWhere([
                    'and',
                    ['messages.sent_at' => NULL],
                    ['>=', 'messages.created_at', Yii::$app->formatter->asTimestamp('-24 hours')]
                ])
                ->count();
            echo $c;
        }
        // Yii::trace($c,'dev');

    }
    /**
     * Lists all MyFires models.
     * @return mixed
     */
    public function actionProcessUpdates($key)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if($key == Yii::$app->params['webhook-key']){
            $alertResponse = array();
            $emailResponse = array();
            echo 'Protecting Now... StandBy'. PHP_EOL;
            $system = new System();
            $alertResponse = $system->findNewAlerts();
            $system = new System();
            $emailResponse = $system->sendNewEmails();
            // $fi = new \FilesystemIterator(Yii::getAlias('@frontend/runtime/mail'), \FilesystemIterator::SKIP_DOTS);
            // printf("There were %d Files", iterator_count($fi));
            echo VarDumper::dumpAsString(ArrayHelper::merge($alertResponse,$emailResponse),10,false) . PHP_EOL;
            $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
            echo "Total Execution Time: {$time}". PHP_EOL;
        }
        Yii::$app->response->statusCode = 200;
    }
}
