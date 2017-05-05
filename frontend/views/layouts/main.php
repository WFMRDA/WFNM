<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
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
        <div class='main-wrap'>
            <!-- =========================
               NAVBAR SECTION
            ============================== -->
            <?=$this->render('_navbar') ?>
            <div class="container">
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
        <footer class="footer">
            <div class="container">
                <?=$this->render('_footer') ?>
            </div>
        </footer>
    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
