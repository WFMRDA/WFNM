<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'User: '. $model->fullName;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<<< User Home', ['index'], ['class' => 'btn btn-success btn-sm']) ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
        <?=Html::a('Reset Password', ['reset-password', 'id' => $model->id] ,
        [
            'class' => 'btn btn-sm btn-warning',
            'title' => Yii::t('app', 'Reset'),
            'data-confirm'=>'Are you sure you want to reset '. $model->username . ' password?',

        ]);?>

        <?= ($model->id == Yii::$app->user->getId())?'': Html::a('Delete', ['Deactivate', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-sm pull-right',
            'data' => [
                'confirm' => 'Are you sure you want to delete '. $model->username . ' ?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            'userStatus.name',
            'userRole.name',
            'fullName',
            'address',
            'profile.phone',
            // 'auth_key',
            // 'access_token',
            // 'password_hash',
            // 'confirmation_token',
            'confirmation_sent_at:datetime',
            'confirmed_at:datetime',
            // 'recovery_token',
            // 'recovery_sent_at',
            'blocked_at:datetime',
            // 'registration_ip',
            'created_at:datetime',
            // 'updated_at',
        ],
    ]) ?>

</div>
