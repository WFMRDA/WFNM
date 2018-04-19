<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
use ptech\pyrocms\models\helpers\Permissions;
use ptech\pyrocms\models\helpers\UserHelpers;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'fullName',
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'label'=>'Is Active?',
                'filter' => Html::activeDropDownList($searchModel, 'status', $listData['status'],['class'=>'form-control selectpicker','prompt' => 'Select Status']),
                'value' => function ($model) {
                    if ($model->id == Yii::$app->user->getId() || !Permissions::isMinRequiredRole($model->role,Yii::$app->user->getId())) {
                        $html = '<div class="text-center"><span class="text-success">' . 'Active'. '</span></div>';

                    }elseif($model->isActive) {
                         $html =  Html::a( 'Deactivate', ['deactivate', 'id' => $model->id], [
                            'class' => 'btn btn-xs btn-danger btn-block',
                            'data-method' => 'post',
                            'data-confirm' => 'Are you sure you want to deactivate this user?',
                        ]);
                    } else {
                        $html = Html::a('Activate', ['activate', 'id' => $model->id], [
                            'class' => 'btn btn-xs btn-success btn-block',
                            'data-method' => 'post',
                            'data-confirm' => 'Are you sure you want to activate this user?',
                        ]);
                    }
                    return $html;
                },
                'format' => 'raw',

            ],
            [
                'attribute'=>'role',
                'filter' => Html::activeDropDownList($searchModel, 'role', $listData['role'],['class'=>'form-control selectpicker','prompt' => 'Select Status']),
                'value' => function ($model){
                    return '<div class="text-center"><span class="text-success">' . $model->userRole->name. '</span></div>';
                },
                'format'=>'raw',
            ],
            [
                'label' =>  'Confirmation',
                'attribute'=>'confirmed_at',
                'value' => function ($model) {
                    if ($model->confirmed_at == null) {
                        $html = Html::a('Confirm', ['confirm', 'id' => $model->id], [
                            'class' => 'btn btn-xs btn-success btn-block',
                            'data-method' => 'post',
                            'data-confirm' => 'Are you sure you want to confirm this user?',
                        ]);
                    } else {
                        $html = '<div class="text-center"><span class="text-success">' . 'Confirmed'. '</span></div>';
                    }
                    return $html;
                },
                'format' => 'raw',
            ],
            [
                'label' => 'Password Reset',
                'format' => 'html',
                'content' =>  function ($data) {
                    return Html::a('Reset Password', ['reset-password', 'id' => $data->id] ,
                    [
                        'class' => 'btn btn-xs btn-warning',
                        'title' => Yii::t('app', 'Reset'),
                        'data-confirm'=>'Are you sure you want to reset '. $data->username . ' password?',

                    ]);
                }
            ],
            [
                'label' => 'Status',
                'attribute'=> 'blocked_at',
                'value' => function ($model) {
                    if(!Permissions::isSameuser($model->id)){
                        if ($model->blocked_at == null) {
                            $html = Html::a('Block', ['block', 'id' => $model->id], [
                                'class' => 'btn btn-xs btn-success btn-block',
                                'data-method' => 'post',
                                'data-confirm' =>  'Are you sure you want to block this user?',
                            ]);
                        } else {
                            $html = Html::a('UnBlock', ['unblock', 'id' => $model->id], [
                                'class' => 'btn btn-xs btn-danger btn-block',
                                'data-method' => 'post',
                                'data-confirm' => 'Are you sure you want to unblock this user?',
                            ]);
                        }
                        return $html;
                    }
                    return '<div class="text-center"><span class="text-success">' . 'Can\'t Block <br>Yourself'. '</span></div>';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'created_at',
                'format' => 'date',
                'filter'=>false,
                /*'filter' => \yii\jui\DatePicker::widget([
                    'model'      => $searchModel,
                    'attribute'  => 'created_at',
                    'dateFormat' => 'php:Y-m-d',
                    'options' => [
                        'class' => 'form-control',
                    ],
                ]),*/
            ],
            // 'auth_key',
            // 'access_token',
            // 'password_hash',
            // 'confirmation_token',
            // 'confirmation_sent_at',
            // 'confirmed_at',
            // 'recovery_token',
            // 'recovery_sent_at',
            // 'blocked_at',
            // 'registration_ip',
            // 'created_at',
            // 'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '80'],
                'template'=> '{view}</br>{update}</br>{delete}',
                // 'template'=> '{view}</br>{delete}',
                'buttons' => [
                    //delete button
                    'delete' =>  function ($url, $model, $key) {
                        if(!Permissions::isSameuser($model->id)){
                            $options = [
                                'title' => Yii::t('yii', 'Delete'),
                                'aria-label' => Yii::t('yii', 'Delete'),
                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-trash">Delete</span>', $url, $options);
                        }
                    },
                    //view button
                    'view' =>  function ($url, $model, $key) {
                            $options = [
                                'title' => Yii::t('yii', 'View'),
                                'aria-label' => Yii::t('yii', 'View'),
                                'data-pjax' => '0',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-eye-open">View</span>', $url, $options);
                    },
                    //update button
                    'update' =>  function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Update'),
                            'aria-label' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-pencil">Edit</span>', $url, $options);
                    },
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
