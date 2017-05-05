# Using controllers events

The controllers packaged with the Yii2-user provide a lot of functionality that is sufficient for general use cases. But,
you might find that you need to extend that functionality and add some logic that suits the specific needs of your
application.

For this purpose, you can either override controller or use events. The controllers are dispatching events in many
places in their code. All events can be found in the constants of needed controller class.

For example, this event listener will redirect user to login page after successful registration instead of showing
message on a blank page:

```php
'user' => [
    'class' => \common\modules\User\Module::className(),
    'controllerMap' => [
        'registration' => [
            'class' => \common\modules\User\controllers\RegistrationController::className(),
            'on ' . \common\modules\User\controllers\RegistrationController::EVENT_AFTER_REGISTER => function ($e) {
                Yii::$app->response->redirect(array('/user/security/login'))->send();
                Yii::$app->end();
            }
        ],
    ],
],
```