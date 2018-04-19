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
$tokenUrl = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['/user/confirm','uid'=>$user->auth_key,'utk'=>$user->confirmation_token]);
?>

<?=Html::eText('Hello '.$user->username)?>
<?=Html::eText('Thank you for signing up on '.Yii::$app->name.'.')?>
<?=Html::eText('In order to complete your registration, please click the link below.')?>
<?=Html::eText('<a href="'.$tokenUrl.'">Confirm Account</a>')?>
<?=Html::eText('If you did not make this request you can ignore this email.')?>
