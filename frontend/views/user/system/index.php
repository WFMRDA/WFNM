<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\cms\SysVariablesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sys Variables';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sys-variables-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Sys Variables', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'enableFlashMessages',
            'enableRegistration',
            'enableGeneratingPassword',
            'enableConfirmation',
            // 'enableUnconfirmedLogin',
            // 'enablePasswordRecovery',
            // 'emailChangeStrategy:email',
            // 'rememberFor',
            // 'confirmWithin',
            // 'recoverWithin',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
