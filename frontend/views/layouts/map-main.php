<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;
use yii\web\View;
use frontend\assets\AppAsset;

$appAsset = AppAsset::register($this);
$this->params['assetUrl'] = $appAsset->baseUrl;

$options = [
    'appName' => Yii::$app->name,
    'baseUrl' => Yii::$app->request->baseUrl,
    'homeUrl' => Url::base(true),
    'assetUrl' => $this->params['assetUrl'],
    'language' => Yii::$app->language,
    'mediaUrl' => Yii::getAlias('@media'),
    'legendHelpToggle' => Yii::$app->appSystemData->legendHelpToggle,
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
