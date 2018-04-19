<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="shortcut icon" href="<?=Url::to('@media/favicon.ico', true)?>">
    <link rel="apple-touch-icon" sizes="57x57" href="<?=Url::to('@media/apple-icon-57x57.png', true)?>">
    <link rel="apple-touch-icon" sizes="60x60" href="<?=Url::to('@media/apple-icon-60x60.png', true)?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?=Url::to('@media/apple-icon-72x72.png', true)?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?=Url::to('@media/apple-icon-76x76.png', true)?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?=Url::to('@media/apple-icon-114x114.png', true)?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?=Url::to('@media/apple-icon-120x120.png', true)?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?=Url::to('@media/apple-icon-144x144.png', true)?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?=Url::to('@media/apple-icon-152x152.png', true)?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=Url::to('@media/apple-icon-180x180.png', true)?>">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?=Url::to('@media/android-icon-192x192.png', true)?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=Url::to('@media/favicon-32x32.png', true)?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?=Url::to('@media/favicon-96x96.png', true)?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?=Url::to('@media/favicon-16x16.png', true)?>">
    <link rel="manifest" href="<?=Url::to('@media/manifest.json', true)?>">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?=Url::to('@media/ms-icon-144x144.png', true)?>">
    <meta name="theme-color" content="#ffffff">
</head>
