<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Pjax;
use yii\widgets\ListView;
$menuItems = array();
?>

<?php
    NavBar::begin([
        'brandLabel' => Html::img('@media/header-logo-trans.png',['class'=>'logo-img']),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);
    if (!Yii::$app->user->isGuest) {
        $menuItems[] = [
            'label' => '<p class="pl-text">P<span class="hidden-xs hidden-sm">reparedness </span>L<span class="hidden-xs">evel</span> <span class=" pl-sprite pl'.Yii::$app->appSystemData->getPlLevel().'"></span></p>',
            'url' => 'http://gacc.nifc.gov',
            'linkOptions'=> ['id'=>'pl-levels-btn-container','target'=>'_blank']
        ];
        if(Yii::$app->controller->layout == 'main' ||Yii::$app->controller->layout == null ){
            $menuItems[] = ['label' => 'Home', 'url' => ['/site/index']];
        }else if(Yii::$app->controller->layout == 'map-main'){
            // $menuItems[] = common\widgets\NotificationsWidget::widget([
            //     'dataProvider' => Yii::$app->appSystemData->userMessages,
            // ]);
            ob_start();
?>
<li id="top-notifications-li" class="dropdown notifications-menu">
    <a id="top-notification-href" href="#" @click="vueModel.seenAlerts" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span v-cloak v-show="badge>0" id = "header-notification-label" class="label label-warning label-as-badge">{{ badge }}</span>
    </a>
    <ul id="notifications-dropdown-menu" class="dropdown-menu">
        <div class="panel panel-default">
            <div class="panel-heading text-center">You have <span id="notifications-num">{{ unreadTotal }}</span> new notifications
                <a  href="javascript:;" @click="vueModel.markAllNotificationSeen" class="panel-heading text-center">Mark All Read</a></div>

            <div class="panel-body">
                <ul class="menu">
                    <div id="notifications-div">
                        <li @click="vueModel.gotoAlert(alert)" :class="{ unread : empty(alert.seen_at) , read: !empty(alert.seen_at) }" v-for="(alert,index) in vueModel.myAlerts" :key="index">
                            <p class="notifications ">
                                <i class="fa fa-clock-o">{{ alert.timeLapse }}</i>
                                <i class="fa fa-exclamation-triangle text-green" ></i> {{ alert.subject }}
                            </p>
                        </li>
                    </div>
                </ul>
            </div>
            <div @click="vueModel.activatePane('alerts',true)" class="panel-footer text-center"><a  href="javascript:;"  class="legend-btn" id ="liNotif">View All Notifications</a></div>
        </div>
    </ul>
</li>
<?php
            $menuItems[] = ob_get_clean();
        }

        // $menuItems[] = '<li><a id="feedback-btn-header" class ="hidden-xs hidden-sm" href="https://docs.google.com/forms/d/1dMNxmfK8GiDJ9U0KsLOBOK2_dKBAuOMOUR32vy3H1gA/edit?usp=sharing" target="_blank">Feedback</a></li>';
        $menuItems[] = '<li><a id="feedback-btn-header" class ="hidden-xs hidden-sm" href="https://www.facebook.com/wildfiresnearme" target="_blank">Feedback</a></li>';

    }
        $menuItems[] = ptech\pyrocms\widgets\UserNavWidget::widget();

        echo Nav::widget([
           'encodeLabels' => false,
           'activateParents' => true,
           'options' => ['class' => 'navbar-nav navbar-right'],
           'items' => $menuItems,
       ]);
    NavBar::end();
    ?>
