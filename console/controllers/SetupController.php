<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\helpers\Console;
use yii\helpers\Html;

use common\dataMigration\AppMigrate;
use common\models\system\DataCreator;

class SetupController extends Controller {

    public function actionTestEnv(){
        if(YII_ENV_DEV) {
            echo 'ENV_DEV' . PHP_EOL;
        }elseif(!YII_ENV_DEV){
            echo 'ENV_PROD' . PHP_EOL;
        }else{
            echo "ENV Undetermined" . PHP_EOL;
        }
    }

    public function actionMigrate(){
        $this->stdout('Migrating Data Now... StandBy'. PHP_EOL, Console::FG_GREEN);
        $system = new AppMigrate();
        $response = $system->migrate();
        $this->stdout(VarDumper::dumpAsString($response,10,false) . PHP_EOL, Console::FG_CYAN);
        $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
        $this->stdout("Total Execution Time: {$time}". PHP_EOL, Console::FG_GREEN);
    }


    public function actionCreateAlerts($id,$count = 1){
        $this->stdout('Creating Data Now... StandBy'. PHP_EOL, Console::FG_GREEN);
        $system = new DataCreator();
        $response = $system->createAlerts($id,$count);
        $this->stdout(VarDumper::dumpAsString($response,10,false) . PHP_EOL, Console::FG_CYAN);
        $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
        $this->stdout("Total Execution Time: {$time}". PHP_EOL, Console::FG_GREEN);

    }
}
