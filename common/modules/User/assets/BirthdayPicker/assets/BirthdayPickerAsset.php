<?php
namespace common\modules\User\assets\BirthdayPicker\assets;

use yii\web\AssetBundle;

class BirthdayPickerAsset extends AssetBundle
{  

    public $sourcePath = '@vendor/ptech/yii2-user/assets/BirthdayPicker/dist';

    public $css = [
    ];
    public $js = [
        'bday-picker.js',
        'onload.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
