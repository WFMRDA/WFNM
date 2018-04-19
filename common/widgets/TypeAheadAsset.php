<?php
namespace common\widgets;

use yii\web\AssetBundle;

class TypeAheadAsset extends AssetBundle
{

    public $sourcePath = '@common/widgets/assets/dist';

    public $css = [
        YII_ENV_DEV ? 'css/bootstrap-typeahead.css' : '//assets.wildfiresnearme.wfmrda.com/css/bootstrap-typeahead.css',
    ];
    public $js = [
        YII_ENV_DEV ? 'js/typeahead.bundle.min.js' :'//assets.wildfiresnearme.wfmrda.com/js/typeahead.bundle.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
