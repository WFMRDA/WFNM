<?php
namespace common\modules\User\assets;

use yii\web\AssetBundle;

class ConnectAsset extends AssetBundle
{  

    public $css = [
    	'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/4.12.0/bootstrap-social.min.css',
    ];
    public $js = [
    ];
    public $depends = [
    	'yii\bootstrap\BootstrapAsset',
    ];
}


