<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    // public $sourcePath = '@frontend/assets/dist';
    public $css = [
        '//use.fontawesome.com/66160b7582.css',
        '//cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css',
        '//assets.wildfiresnearme.wfmrda.com/css/wfnm.css',
    ];
    public $js = [
        '//maps.googleapis.com/maps/api/js?key=AIzaSyBGrF8tvV6q8f5pIMS6eKMbPsLAj_IlXxE&libraries=places',
        '//assets.wildfiresnearme.wfmrda.com/js/google.js',
        '//assets.wildfiresnearme.wfmrda.com/js/wfnm.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
