<?php

/*
 * This file is part of the ptech project.
 *
 * (c) ptech project <http://github.com/ptech>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\widgets\Menu;

/** @var ptech\pyrocms\models\user\User $user */
$user = Yii::$app->user->identity;
$networksVisible = (Yii::$app->has ('eauth') && (count(Yii::$app->eauth->services) > 0));
$storeVisible = Yii::$app->hasModule('store');
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?= $user->username ?>
        </h3>
    </div>
    <div class="panel-body">
        <?= Menu::widget([
            'route' => Yii::$app->controller->id.'/'.Yii::$app->controller->action->id,
            'options' => [
                'class' => 'nav nav-pills nav-stacked',
            ],
            'items' => [
                ['label' => 'Profile', 'url' => ['/settings/profile']],
                ['label' => 'Account', 'url' => ['/settings/account']],
                ['label' => 'Networks', 'url' => ['/settings/networks'], 'visible' => $networksVisible],
                ['label' => 'Orders', 'url' => ['/orders/index'], 'visible' => $storeVisible],
                // ['label' => 'Payment Info', 'url' => ['/store/payment-info'], 'visible' => $storeVisible],
            ],
        ]) ?>
    </div>
</div>
