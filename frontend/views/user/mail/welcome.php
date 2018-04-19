<?php

/*
 * This file is part of the ptech project.
 *
 * (c) ptech project <http://github.com/ptech>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Url;
use ptech\pyrocms\models\helpers\Html;
/**
 * @var ptech\user\Module 		$module
 * @var ptech\user\models\User   $user
 * @var ptech\user\models\Token  $token
 * @var bool                        $showPassword
 */
// Yii::trace($user->getEnableGeneratingPassword(),'dev');
// Yii::trace($user->confirmed_at,'dev');
?>
    <?=Html::eText('Hello '.$user->username)?>
    <?=Html::eText('Your account on '.Yii::$app->name.' has been created.')?>

<?php if ($user->getEnableGeneratingPassword()){
    $passwordUrl = Yii::$app->urlManagerFrontEnd->createUrl(['/user/update-password','uid'=>$user->auth_key,'token'=>$user->recovery_token]);
?>
    <?=Html::eText('For security reasons we did not create a password for you. Please use this link to create your password. This link will expire after 24 hours.')?>
    <?=Html::eText('<a href="'.$passwordUrl.'">Set Password</a>')?>
<?php } ?>
<?=Html::eText('If you did not make this request you can ignore this email.')?>
