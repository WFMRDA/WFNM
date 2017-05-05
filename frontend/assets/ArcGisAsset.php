<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * ArcGisAsset asset bundle.
 */
class ArcGisAsset extends AssetBundle
{
    public $sourcePath = '@frontend/assets/dist';
    public $css = [
        // '//js.arcgis.com/4.1/esri/css/main.css',
        '//js.arcgis.com/4.3/esri/css/main.css',
    ];
    public $js = [
        // '//js.arcgis.com/4.1/',
        '//js.arcgis.com/4.3/',
        YII_ENV_DEV ? 'js/jTouch.js' :'//assets.wildfiresnearme.wfmrda.com/js/jTouch.js',
        YII_ENV_DEV ? 'js/map.js' :'//assets.wildfiresnearme.wfmrda.com/js/map.js',
    ];
    public $depends = [
        'frontend\assets\AppAsset',
        'yii\jui\JuiAsset',
        'frontend\assets\DataTablesAssets',
        'common\widgets\TypeAheadAsset'
    ];
}
