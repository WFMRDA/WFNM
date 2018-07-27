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
        '//unpkg.com/leaflet@1.3.1/dist/leaflet.css',
        '//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css',
        '//cdn.datatables.net/1.10.18/css/jquery.dataTables.css',
        // '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css',
        '//cdn.rawgit.com/socib/Leaflet.TimeDimension/master/dist/leaflet.timedimension.control.min.css',
    ];
    public $js = [
        '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment-with-locales.min.js',
        '//cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.20/moment-timezone-with-data.min.js',
        '//cdn.jsdelivr.net/npm/vue',
        '//unpkg.com/leaflet@1.3.1/dist/leaflet.js',
        '//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js',
        '//cdn.datatables.net/1.10.18/js/jquery.dataTables.js',
        '//cdn.rawgit.com/nezasa/iso8601-js-period/master/iso8601.min.js',
        '//unpkg.com/leaflet.nontiledlayer@1.0.7/dist/NonTiledLayer.js',
        '//cdn.rawgit.com/socib/Leaflet.TimeDimension/master/dist/leaflet.timedimension.min.js',
        // '//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js',
        // '//unpkg.com/leaflet-bootstrap-zoom/bin/leaflet-bootstrap-zoom.min.js',
        // 'https://unpkg.com/esri-leaflet@2.1.4/dist/esri-leaflet.js',
        '//cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.2.1/typeahead.bundle.min.js',
        YII_ENV_DEV ? 'js/jTouch.js' :'//assets.wildfiresnearme.wfmrda.com/js/jTouch.js',
        YII_ENV_DEV ? 'js/map.js' :'//assets.wildfiresnearme.wfmrda.com/js/map.js',
    ];
    public $depends = [
        'frontend\assets\AppAsset',
        // 'common\widgets\TypeAheadAsset'
    ];
}
