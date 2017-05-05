<?php

/*
 * This file is part of the ptech project.
 *
 * (c) ptech project <http://github.com/ptech/>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace common\modules\User\commands;

use common\modules\User\models\User;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Creates new user account.
 *
 * @property \common\modules\User\Module $module
 *
 * @author Reginald Goolsby <rjgoolsby@pyrotechsolutions.com>
 */
class MemberSyncController extends Controller
{
    public function actionIndex()
    {
        $this->stdout('wrong' . PHP_EOL, Console::FG_GREEN);  

/*        $model = Yii::createObject([
            'class' => 'common\modules\User\models\MailChimp',
        ]);
        $output = $model->updateBatches();
        // $output = $model->syncUsers();
        // $output = $model->checkBatch('a4e3b4724e');
        // $output = $this->module->mailChimp['apikey'];
        $output =  \yii\helpers\VarDumper::dumpAsString($output,10,false);
        $this->stdout($output . PHP_EOL, Console::FG_GREEN);  */
    }

    public function actionSyncMailchimp()
    {

        $model = Yii::createObject([
            'class' => 'common\modules\User\models\MailChimp',
        ]);
        $output = $model->syncUsers();
        // $output =  \yii\helpers\VarDumper::dumpAsString($output,10,false);
        // $this->stdout($output . PHP_EOL, Console::FG_GREEN);  
    }

    public function actionUpdateBatches()
    {

        $model = Yii::createObject([
            'class' => 'common\modules\User\models\MailChimp',
        ]);
        $output = $model->updateBatches();
        // $output =  \yii\helpers\VarDumper::dumpAsString($output,10,false);
        // $this->stdout($output . PHP_EOL, Console::FG_GREEN);  
    }
}
