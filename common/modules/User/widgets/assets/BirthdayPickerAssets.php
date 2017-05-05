<?php
namespace common\modules\User\widgets\assets;


use yii\web\AssetBundle;

class BirthdayPickerAssets extends AssetBundle
{  

    public $sourcePath = '@common/modules/User/widgets/assets/BirthdayPicker/dist';

    public $css = [
    ];
    public $js = [
        'birthdaypicker.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
