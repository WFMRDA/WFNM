<?php

/*
 * This file is part of the ptech project.
 *
 * (c) ptech project <http://github.com/ptech/>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace common\modules\User\commands;

use common\modules\User\models\User;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Creates new user account.
 *
 * @property \common\modules\User\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class CreateController extends Controller
{
    /**
     * This command creates new user account. If password is not set, this command will generate new 8-char password.
     * After saving user to database, this command uses mailer component to send credentials (username and password) to
     * user via email.
     *
     * @param string      $email    Email address
     * @param string      $username Username
     * @param null|string $password Password (if null it will be generated automatically)
     */
    public function actionIndex($email, $username, $password = null, $role = 30)
    {
        // $this->stdout($email . PHP_EOL, Console::FG_GREEN);
        // $this->stdout($username . PHP_EOL, Console::FG_GREEN);
        // $this->stdout($role . PHP_EOL, Console::FG_GREEN);
        // $this->stdout($password . PHP_EOL, Console::FG_GREEN);
        //ex ./yii user/create rjgoolsby@pyrotechsolutions.com rjgoolsby paladin62
        //ex ./yii user/create developers@pyrotechsolutions testuser gohard
        $user = Yii::createObject([
            'class'    => User::className(),
            'email'    => $email,
            'username' => $username,
            'password' => $password,
            'role' => $role,
        ]);

        $user->enableConfirmation = false;

        if ($user->createAccount()) {
            $this->stdout('User has been created' . "!\n", Console::FG_GREEN);
        } else {
            $this->stdout( 'Please fix following errors:' . "\n", Console::FG_RED);
            foreach ($user->errors as $errors) {
                foreach ($errors as $error) {
                    $this->stdout(' - ' . $error . "\n", Console::FG_RED);
                }
            }
        }
    }
}
