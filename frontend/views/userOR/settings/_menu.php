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

/** @var common\modules\User\models\User $user */
$user = Yii::$app->user->identity;

// echo \common\modules\User\widgets\Eauth::widget(['action' => 'site/login']);
$networksVisible = count(Yii::$app->eauth->services) > 0;
// $networksVisible = false;

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?= $user->username ?>
        </h3>
    </div>
    <div class="panel-body">
        <?= Menu::widget([
            'options' => [
                'class' => 'nav nav-pills nav-stacked',
            ],
            'items' => [
                ['label' => 'Profile', 'url' => ['settings/profile']],
                ['label' => 'Account', 'url' => ['settings/account']],
                ['label' => 'Networks', 'url' => ['settings/networks'], 'visible' => $networksVisible],
            ],
        ]) ?>
    </div>
</div>
