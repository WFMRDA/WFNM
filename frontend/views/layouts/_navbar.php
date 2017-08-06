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
            'class' => 'navbar navbar-default navbar-fixed-top',
        ],
    ]);
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = [
            'label' => '<p class="pl-text">P<span class="hidden-xs hidden-sm">reparedness </span>L<span class="hidden-xs">evel</span> <span class=" pl-sprite pl'.Yii::$app->systemData->getPlLevel().'"></span></p>',
            'url' => 'http://gacc.nifc.gov',
            'linkOptions'=> ['id'=>'pl-levels-btn-container','target'=>'_blank']
        ];
        if(Yii::$app->controller->layout == 'main' ||Yii::$app->controller->layout == null ){
            $menuItems[] = ['label' => 'Home', 'url' => ['/site/index']];
        }else if(Yii::$app->controller->layout == 'map-main'){
            $menuItems[] = common\widgets\NotificationsWidget::widget([
                'dataProvider' => Yii::$app->systemData->userMessages,
            ]);
        }

        $menuItems[] = '<li><a id="feedback-btn-header" class ="hidden-xs hidden-sm" href="https://docs.google.com/forms/d/1dMNxmfK8GiDJ9U0KsLOBOK2_dKBAuOMOUR32vy3H1gA/edit?usp=sharing" target="_blank">Feedback</a></li>';
    ob_start();
?>

<li class=" user user-menu">
    <a id='profile-settings-dropdown' href="#" class="dropdown-toggle" data-toggle="dropdown">
        <span class="hidden-xs"><?=Yii::$app->user->identity->username?></span>
        <?=Yii::$app->systemData->getAvatar('navbar-avatar')?>
        <i class="fa fa-cogs"></i>
    </a>
    <ul class="user-menu-dropdown dropdown-menu col-xs-12">
        <!-- User image -->
        <li class="user-header text-center">
            <?=Yii::$app->systemData->getAvatar('dropdown-avatar')?>
            <p>
                <?=Yii::$app->user->identity->username?>
            </p>
        </li>
        <!-- Menu Footer-->
        <li class="user-footer col-xs-12">
            <div class="col-xs-6 pull-left">
                <?= Html::a(
                    'Account',
                    ['/user/settings/profile'],
                    ['class' => 'btn btn-default btn-flat','id'=>'profile-settings-btn']
                ) ?>
            </div>
            <div class="col-xs-6 pull-right">
                <?= Html::a(
                    'Sign out',
                    ['/user/security/logout'],
                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                ) ?>
            </div>
        </li>
    </ul>
</li>
<?php
    $menuItems[] = ob_get_clean();
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
        'encodeLabels' => false,
    ]);
    NavBar::end();
    ?>
