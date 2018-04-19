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

<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    Your account on <?= Yii::$app->name?> has been created.
</p>
<?php if ($user->getEnableGeneratingPassword()){
    $passwordUrl = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['user/update-password','uid'=>$user->auth_key,'token'=>$user->recovery_token]);
?>
    <p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
        We have generated an temporary password for you: <strong><?= $user->password ?></strong>
    </p>
    <p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
        This password will exipire after 24 hours. Please change it by clicking here:
    </p>
    <p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
        <a href="<?=$passwordUrl?>">Set Password</a>
    </p>
<?php } ?>
<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    If you did not make this request you can ignore this email.
</p>
