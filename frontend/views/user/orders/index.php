<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\DetailView;
use ptech\pyrocms\models\helpers\CartHelpers;
/* @var $this yii\web\View */
/* @var $searchModel app\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
// echo \Yii::$app->formatter->datetimeFormat;
?>
<div class="orders-index">
    <div class="row">
        <div class='container-fluid'>
            <div class="col-xs-12 col-lg-3">
                <?= $this->render('@pyrocms/views/user/settings/_menu') ?>
            </div>
            <div class="col-xs-12 col-lg-9">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?= Html::encode($this->title) ?>
                    </div>
                    <div class="panel-body">
                        <?php Pjax::begin(); ?>    <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            // 'filterModel' => $searchModel,
                            // 'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],

                            'formatter' =>  \Yii::$app->getFormatter(),
                            'columns' => [
                                [
                                    'attribute'=>'id',
                                    'label' => 'Order Id',
                                    'headerOptions' => ['class'=>' col-xs-12 visible-xs-block visible-sm-block visible-md-block'],
                                    'contentOptions' => ['class' => ' col-xs-12 visible-xs-block visible-sm-block visible-md-block'],
                                    'content' => function ($model, $key, $index, $column){
                                         return DetailView::widget([
                                            'model' => $model,
                                            'attributes' => [
                                                'id',
                                                [
                                                    'label' => 'confirmation_id',
                                                    'format'=>'html',
                                                    'value' =>  CartHelpers::getConfirmationStatus($model,'confirmation_id',false),
                                                    // 'value' =>  (!empty($model->cancelled_at))?'Order Cancelled':(empty($model->confirmation_id))?'Pending':$model->confirmation_id,


                                                ],
                                                [
                                                    'label' => 'confirmed_at',
                                                    'format'=>'html',
                                                    'value' => CartHelpers::getConfirmationStatus($model,'confirmed_at',true),
                                                ],
                                                [
                                                    'label' => 'Pick Up Time',
                                                    'value' => (is_null($model->pickup_at))?'As Soon As Possible':\Yii::$app->formatter->asDatetime($model->pickup_at),
                                                ],
                                                // 'cancelled_at',
                                                'costs:currency',
                                                'tax:currency',
                                                'total_costs:currency',
                                                'created_at:datetime',
                                                [
                                                    'label' => '',
                                                    'format' => 'html',
                                                    'value' =>(empty($model->confirmed_at) && empty($model->cancelled_at))?Html::a('<span class="glyphicon glyphicon-eye-open"></span> View Order',['orders/view','id'=>$model->id],['class'=>'view-order-link']).'<br>'.  Html::a('<span class="glyphicon glyphicon-trash"></span> Cancel Order', ['orders/cancel','id'=>$model->id], [
                                                            'title' => 'Cancel',
                                                            'data-confirm' => Yii::t('yii', 'Are you sure to cancel this item?'),
                                                            'data-method' => 'post',
                                                    ]):Html::a('<span class="glyphicon glyphicon-eye-open"></span> View Order',['orders/view','id'=>$model->id],['class'=>'view-order-link']),
                                                ],
                                                // 'updated_at',
                                            ],
                                        ]);
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\SerialColumn',
                                    'headerOptions' => ['class'=>' hidden-xs hidden-sm hidden-md'],
                                    'contentOptions' => ['class' => ' hidden-xs hidden-sm hidden-md'],
                                ],
                                [
                                    'attribute'=>'id',
                                    'label' => 'Order Id',
                                    'headerOptions' => ['class'=>' hidden-xs hidden-sm hidden-md'],
                                    'contentOptions' => ['class' => ' hidden-xs hidden-sm hidden-md'],
                                ],
                                [
                                    'attribute' => 'confirmation_id',
                                    'headerOptions' => ['class'=>' hidden-xs hidden-sm hidden-md'],
                                    'contentOptions' => ['class' => ' hidden-xs hidden-sm hidden-md'],
                                    'content' => function ($model, $key, $index, $column){
                                        return CartHelpers::getConfirmationStatus($model,'confirmation_id',false);
                                    }
                                ],
                                [
                                    'attribute' => 'confirmed_at',
                                    'headerOptions' => ['class'=>' hidden-xs hidden-sm hidden-md'],
                                    'contentOptions' => ['class' => ' hidden-xs hidden-sm hidden-md'],
                                    'content' => function ($model, $key, $index, $column){
                                        return CartHelpers::getConfirmationStatus($model,'confirmed_at',true);
                                    }
                                ],
                                [
                                    'attribute' => 'pickup_at',
                                    'label' => 'Pick Up Time',
                                    'format'=>'datetime',
                                    'headerOptions' => ['class'=>' hidden-xs hidden-sm hidden-md'],
                                    'contentOptions' => ['class' => ' hidden-xs hidden-sm hidden-md'],
                                ],
                                // 'pickup_at',
                                [
                                    'attribute' => 'total_costs',
                                    'format'=>'currency',
                                    'headerOptions' => ['class'=>' hidden-xs hidden-sm hidden-md'],
                                    'contentOptions' => ['class' => ' hidden-xs hidden-sm hidden-md'],
                                ],
                                [
                                    'attribute' => 'created_at',
                                    'format'=>'datetime',
                                    'headerOptions' => ['class'=>' hidden-xs hidden-sm hidden-md'],
                                    'contentOptions' => ['class' => ' hidden-xs hidden-sm hidden-md'],
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'headerOptions' => ['class'=>' hidden-xs hidden-sm hidden-md'],
                                    'contentOptions' => ['class' => ' hidden-xs hidden-sm hidden-md'],
                                    'header'=>'Actions',
                                    'template' => '{orders}{delete}',
                                    'buttons' => [
                                        'orders' => function ($url,$model,$key) {
                                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span> View Order',['orders/view','id'=>$model->id],['class'=>'view-order-link']);
                                        },
                                        'delete' => function ($url,$model,$key) {
                                            if(empty($model->confirmed_at) && empty($model->cancelled_at)){
                                                return '<br>'.Html::a('<span class="glyphicon glyphicon-trash"></span> Cancel Order', ['orders/cancel','id'=>$model->id], [
                                                                'title' => 'Cancel',
                                                                'data-confirm' => Yii::t('yii', 'Are you sure to cancel this order? You cannot reverse this.'),
                                                                'data-method' => 'post',
                                                        ]);
                                            }
                                        },
                                    ],
                                ],
                                // 'user_id',
                                // 'confirmation_id',
                                // 'confirmed_at',
                                // 'pickup_at',
                                // 'cancelled_at',
                                // 'costs',
                                // 'tax',
                                // 'total_costs:currency',
                                // 'created_at:datetime',
                                // 'updated_at',
                            ],
                        ]); ?>
                    <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
