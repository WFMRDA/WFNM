<?php

/*
 * This file is part of the ptech project.
 *
 * (c) ptech project <http://github.com/ptech>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
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
<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    Hello <?=$user->username?>,
</p>
<?=Html::eText('Hello'.$user->username)?>
<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    Your account on <?= Yii::$app->name?> has been created.
</p>
<?php if ($user->getEnableGeneratingPassword()){
    $passwordUrl = Yii::$app->urlManagerFrontEnd->createUrl(['/user/update-password','uid'=>$user->auth_key,'token'=>$user->recovery_token]);
?>
    <p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
        For security reasons we did not create a password for you. Please use this link to create your password. This link will expire after 24 hours.
    </p>
    <p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
        <a href="<?=$passwordUrl?>">Set Password</a>
    </p>
<?php } ?>
<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    If you did not make this request you can ignore this email.
</p>
