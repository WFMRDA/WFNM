<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use ptech\pyrocms\widgets\NavBar;
use yii\widgets\Pjax;
use yii\widgets\ListView;
$menuItems = array();
?>

<?php

    NavBar::begin([
        'brandLabel' => Html::img('@media/header-logo-trans.png',['class'=>'logo-img']),
        'brandUrl' => Yii::$app->homeUrl,
        'brandOptions' => ['class'=> 'brand-logo '],
        'options' => [
            'class' => 'navbar navbar-default navbar-fixed-top',
            'id' => 'main-header',
        ],
        'containerOptions'=>['class'=>'collapse navbar-collapse navbar-right'],

        'innerContainerOptions'=>[
            'class'=>'container-fluid',
        ],
        'appendContainer' => \ptech\pyrocms\widgets\UserNavWidget::widget(['user'=>false]),
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
            $menuItems[] = common\widgets\NotificationsWidget::widget([
                'dataProvider' => Yii::$app->appSystemData->userMessages,
            ]);
        }

        $menuItems[] = '<li><a id="feedback-btn-header" class ="hidden-xs hidden-sm" href="https://docs.google.com/forms/d/1dMNxmfK8GiDJ9U0KsLOBOK2_dKBAuOMOUR32vy3H1gA/edit?usp=sharing" target="_blank">Feedback</a></li>';
        $menuItems[] = \ptech\pyrocms\widgets\UserNavWidget::widget(['cart'=>false]);
    }

    echo Nav::widget([
       'encodeLabels' => false,
       'activateParents' => true,
       'options' => ['class' => 'navbar-nav navbar-right'],
       'items' => $menuItems,
   ]);
    NavBar::end();
    ?>
