<?php

/*
 * This file is part of the ptech project
 *
 * (c) ptech project <http://github.com/ptech>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use ptech\rbac\widgets\Assignments;

/**
 * @var yii\web\View 				$this
 * @var common\modules\User\models\User 	$user
 */

?>

<?php $this->beginContent('@ptech/user/views/admin/update.php', ['user' => $user]) ?>

<?= yii\bootstrap\Alert::widget([
    'options' => [
        'class' => 'alert-info',
    ],
    'body' => Yii::t('user', 'You can assign multiple roles or permissions to user by using the form below'),
]) ?>

<?= Assignments::widget(['userId' => $user->id]) ?>

<?php $this->endContent() ?>
