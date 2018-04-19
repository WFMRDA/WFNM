<?php

/*
 * This file is part of the ptech project.
 *
 * (c) ptech project <http://github.com/ptech>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

 use ptech\pyrocms\models\helpers\Html;
use yii\helpers\Url;

/**
 * @var ptech\user\models\User  $user
 * @var ptech\user\models\Token $token
 */
 $passwordUrl = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['user/update-password','uid'=>$user->auth_key,'token'=>$user->recovery_token]);
?>
<?=Html::eText('Hello '.$user->username)?>
<?=Html::eText('We have received a request to reset the password for your account on '.Yii::$app->name.'.')?>
<?=Html::eText('Please click the link below to complete your password reset.')?>
<?=Html::eText('<a href="'.$passwordUrl.'">Reset Password</a>')?>
<?=Html::eText('If you did not make this request you can ignore this email.')?>
