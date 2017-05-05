<?php

/*
 * This file is part of the ptech project.
 *
 * (c) ptech project <http://github.com/ptech>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use common\modules\User\widgets\Connect;
use yii\helpers\Html;

/*
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 */

$this->title = 'Networks';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('_menu') ?>
    </div>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode($this->title) ?>
            </div>
            <div class="panel-body">
                <div class="alert alert-info">
                    <p>You can connect multiple accounts to be able to log in using them.</p>
                </div>

                <?php // \common\modules\User\widgets\EauthConnect::widget(['action' => '/user/settings/connect']); ?>
               <?= \common\modules\User\widgets\EauthConnect::widget(['action' => '/site/connect']); ?>
            </div>
        </div>
    </div>
</div>
