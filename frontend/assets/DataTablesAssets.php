<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Reginald Goolsby <Rjgoolsby1@gmail.com>
 * @since 2.0
 */
class DataTablesAssets extends AssetBundle
{
    public $css = [
        '//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css',
        // 'https://cdn.datatables.net/v/bs-3.3.7/dt-1.10.13/af-2.1.3/b-1.2.4/b-colvis-1.2.4/fh-3.1.2/r-2.1.1/sc-1.4.2/datatables.min.css',
    ];
    public $js = [
        '//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js',
        // 'https://cdn.datatables.net/v/bs-3.3.7/dt-1.10.13/af-2.1.3/b-1.2.4/b-colvis-1.2.4/fh-3.1.2/r-2.1.1/sc-1.4.2/datatables.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];

}
