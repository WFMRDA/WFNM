<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;
use yii\web\View;
use frontend\assets\AppAsset;
use common\models\helpers\WfnmHelpers;
use yii\helpers\ArrayHelper;

$appAsset = AppAsset::register($this);
$this->params['assetUrl'] = $appAsset->baseUrl;

$fireData = WfnmHelpers::getFireData();
$params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
$fireId = ArrayHelper::getValue($params,'fid');
$query = ($fireId == null)?[]:WfnmHelpers::getFireInfo($fireId);
$schema = (YII_ENV_DEV)?true:'https';

$hostInfo =  Yii::$app->getUrlManager()->hostInfo;
$options = [
    'appName' => Yii::$app->name,
    'baseUrl' => Yii::$app->request->baseUrl,
    'homeUrl' => $hostInfo,
    'assetUrl' => $this->params['assetUrl'],
    'language' => Yii::$app->language,
    'mediaUrl' => Yii::getAlias('@media'),
    'plLevel' => Yii::$app->appSystemData->getPlLevel(),
    'defaultLocation' => Yii::$app->appSystemData->defaultLocation,
    'wfnm' => $fireData['geoJson'],
    'layers' => $fireData['layers'],
    'myFires' => WfnmHelpers::getMyFires(),
    'alerts' =>  WfnmHelpers::findMyAlerts(),
    'myLocations' =>  WfnmHelpers::findMyLocations(),
    'sitReport' => $fireData['sitReport'],
    'fireDb' => $fireData['fullFireDb'],
    'fid' =>  $query,
];

$this->registerJs(
    "var yiiOptions = ".\yii\helpers\Json::htmlEncode($options).";",
    View::POS_HEAD,
    'yiiOptions'
);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <!-- =========================
       HEADER SECTION
    ============================== -->
    <?=$this->render('_header') ?>
    <body>
    <?php $this->beginBody() ?>
        <!-- =========================
           ANALYTICS SECTION
        ============================== -->
        <?=$this->render('_analytics') ?>
        <div class='wrap' style='height:100%;'>
            <!-- =========================
               NAVBAR SECTION
            ============================== -->
            <?=$this->render('_navbar') ?>
            <div class="container-fluid">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>
        <!-- =========================
           Footer SECTION
        ============================== -->
          <?=$this->render('_footer') ?>
    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
