<?php

/*
 * This file is part of the ptech project.
 *
 * (c) ptech project <http://github.com/ptech>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use common\modules\User\models\UserSearch;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\web\View;
use yii\widgets\Pjax;
use common\modules\User\helpers\UserHelpers;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var UserSearch $searchModel
 */

$this->title = Yii::t('user', 'Manage users');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/_alert', [
    'module' => Yii::$app->getModule('user'),
]) ?>

<?= $this->render('/admin/_menu') ?>

<?php Pjax::begin() ?>

<?= GridView::widget([
    'dataProvider' 	=> $dataProvider,
    'filterModel'  	=> $searchModel,
    // 'layout'  		=> "{items}\n{pager}",
    'export'        => false,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        'fullName',
        'email:email',
        /*[
            'attribute' => 'status',
            'filter' => Html::activeDropDownList($searchModel, 'status', $listData['status'],['class'=>'form-control','prompt' => 'Select Status']),
            'format' => 'html',
            'value'=>function($data) { 
                $color = (UserHelpers::getStatusName($data->status) != 10)? 'success':'alert';
                return '<div class="text-center"><span class="text-'.$color.'">' .  UserHelpers::getStatusName($data->status) . '</span></div>'; 
            }

        ],
        [
            'attribute' => 'registration_ip',
            'value' => function ($model) {
                return $model->registration_ip == null
                    ? '<span class="not-set">' . Yii::t('user', '(not set)') . '</span>'
                    : $model->registration_ip;
            },
            'format' => 'html',
        ],*/
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                if (extension_loaded('intl')) {
                    return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]);
                } else {
                    return date('Y-m-d G:i:s', $model->created_at);
                }
            },
            'filter' => DatePicker::widget([
                'model'      => $searchModel,
                'attribute'  => 'created_at',
                'dateFormat' => 'php:Y-m-d',
                'options' => [
                    'class' => 'form-control',
                ],
            ]),
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
            'header' => Yii::t('user', 'Confirmation'),
            'value' => function ($model) {
                if ($model->isConfirmed) {
                    return '<div class="text-center"><span class="text-success">' . Yii::t('user', 'Confirmed') . '</span></div>';
                } else {
                    return Html::a(Yii::t('user', 'Confirm'), ['confirm', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-success btn-block',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
                    ]);
                }
            },
            'format' => 'raw',
            'visible' => Yii::$app->getModule('user')->enableConfirmation,
        ],
        [
            'attribute' => 'isActive',
            'filter' => Html::activeDropDownList($searchModel, 'isActive', $listData['status'],['class'=>'form-control','prompt' => 'Select Status']),
            'value' => function ($model) {
                if ($model->id == Yii::$app->user->getId() || !UserHelpers::isMinRequiredRole($model->role)) {
                    return '';
                }
                if ($model->isActive) {
                     return Html::a(Yii::t('user', 'Deactivate'), ['block', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-danger btn-block',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure you want to deactivate this user?'),
                    ]);
                } else {
                    return Html::a(Yii::t('user', 'Activate'), ['block', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-success btn-block',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure you want to activate this user?'),
                    ]);
                }
            },
            'format' => 'raw',

        ],
        /*[
            'header' => Yii::t('user', 'Status'),
            'value' => function ($model) {
                if ($model->isBlocked) {
                    return Html::a(Yii::t('user', 'Activate'), ['block', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-success btn-block',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure you want to activate this user?'),
                    ]);
                } else {
                    return Html::a(Yii::t('user', 'Deactivate'), ['block', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-danger btn-block',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure you want to deactivate this user?'),
                    ]);
                }
            },
            'format' => 'raw',
        ],*/

        [
            'class' => 'yii\grid\ActionColumn',
            'buttons' => [
                    //delete button
                    'delete' =>  function ($url, $model, $key) {
                        if ($model->id == Yii::$app->user->getId() || UserHelpers::getUserRoleId($model->id)  == UserHelpers::getRoleId('Master')) {
                            return '';
                        }
                        return Html::a('<span class="glyphicon glyphicon-trash">Delete</span>', $url, [
                            'title' => Yii::t('yii', 'Delete'),
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]);
                    },
                    //view button
                    'view' =>  function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open">View</span>', $url, [
                                'title' => Yii::t('yii', 'View'),
                                'data-pjax' => '0',
                        ]);
                    },
                    //update button
                    'update' =>  function ($url, $model, $key) {
                         return Html::a('<span class="glyphicon glyphicon-pencil">Edit</span>', $url, [
                            'title' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ]);
                    },
                ],
             'template' => '{update} {delete}',
        ],
    ],
]); ?>

<?php Pjax::end() ?>
