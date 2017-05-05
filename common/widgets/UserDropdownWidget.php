<?php

namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class UserDropdownWidget extends Widget
{

    public function init()
    {
        parent::init();

    }

    public function run()
    {
        return  $this->renderBody();
    }

    public function renderBody(){
        $avatarUrl = Yii::$app->systemData->avatar;
        $avatarImage = Html::img ($avatarUrl, ["class"=>"img-circle"] ) ;
        $username = Yii::$app->user->identity->username;
        $startDate = Yii::$app->systemData->userStartDate;
        $userImage = Html::img ($avatarUrl, ["class"=>"user-image"] );
        $logoutBtn = Html::a(
                    "Sign out",
                    ["/user/security/logout"],
                    ["data-method" => "post", "class" => "btn btn-default btn-flat"]
                );
        $profileBtn = Html::a(
                    "Account",
                    ["/user/settings/profile"],
                    ["class" => "btn btn-default btn-flat","id"=>"profile-settings-btn"]
                ) ;
$html = <<<HTML
<li class=" user user-menu">
    <a id="profile-settings-dropdown" href="#" class="dropdown-toggle" data-toggle="dropdown">
        {$userImage}
        <span class="hidden-xs">$username</span>
        <i class="fa fa-cogs"></i>
    </a>
    <ul class="user-menu-dropdown dropdown-menu col-xs-12">
        <!-- User image -->
        <li class="user-header text-center">
            {$avatarImage}
            <p class="hidden-xs">
                {$username}
            </p>
            <small>Member since {$startDate}</small>
        </li>
        <!-- Menu Footer-->
        <li class="user-footer col-xs-12">
            <div class="col-xs-6 pull-left">
                {$profileBtn}
            </div>
            <div class="col-xs-6 pull-right">
                {$logoutBtn}
            </div>
        </li>
    </ul>
</li>
HTML;
        return $html;
    }

}
