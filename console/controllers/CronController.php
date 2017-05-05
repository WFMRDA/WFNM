<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Url;
use common\models\helpers\WfnmHelpers;
use yii\helpers\VarDumper;
use yii\helpers\Console;
use common\models\system\System;
use yii\helpers\ArrayHelper;
use common\models\migration\UserMigration;


class CronController extends Controller {

    public function actionTestEnv(){
        if(YII_ENV_DEV) {
            echo 'ENV_DEV';
        }elseif(!YII_ENV_DEV){
            echo 'ENV_PROD';
        }else{
            echo "ENV Undetermined";
        }
    }

    public function actionTestParams(){
        $this->stdout(VarDumper::dumpAsString(Yii::$app->params['adminEmail'],10,false) . PHP_EOL, Console::FG_GREEN);
    }

    public function actionSendTestEmail($message){
        Yii::$app->mailer->compose()
            ->setTo('Rgoolsby@firenet.gov')
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject('Testing Console Mailing System')
            ->setTextBody($message)
            ->send();
    }

    public function actionServeAndProtect(){
        $alertResponse = array();
        $emailResponse = array();
        $this->stdout('Protecting Now... StandBy'. PHP_EOL, Console::FG_GREEN);
        $system = new System();
        $alertResponse = $system->findNewAlerts();
        $emailResponse = $system->sendNewEmails();
        $this->stdout(VarDumper::dumpAsString(ArrayHelper::merge($alertResponse,$emailResponse),10,false) . PHP_EOL, Console::FG_CYAN);
        $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
        $this->stdout("Total Execution Time: {$time}". PHP_EOL, Console::FG_GREEN);
    }

    public function actionSendEmails(){
        $this->stdout('Processing Emails ... StandBy'. PHP_EOL, Console::FG_GREEN);
        $system = new System();
        $response = $system->sendNewEmails();
        $this->stdout(VarDumper::dumpAsString($response,10,false) . PHP_EOL, Console::FG_CYAN);
        $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
        $this->stdout("Total Execution Time: {$time}". PHP_EOL, Console::FG_GREEN);
    }

    /*public function actionTestFunct(){
        $this->stdout('Processing Users ... StandBy'. PHP_EOL, Console::FG_GREEN);
        $model = new UserMigration();
        $response = $model->migrateData();
        $this->stdout(VarDumper::dumpAsString($response,10,false) . PHP_EOL, Console::FG_CYAN);
        $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
        $this->stdout("Total Execution Time: {$time}". PHP_EOL, Console::FG_GREEN);

    }*/
}
