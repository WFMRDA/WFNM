<?php
namespace rest\controllers;

use Yii;
use yii\rest\Controller;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use rest\models\User;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class GenerateTokenController extends Controller
{

    public function behaviors()
	{

        return ArrayHelper::merge(parent::behaviors(), [
            'basicAuth' => [
	            'class' => HttpBasicAuth::className(),
				'auth' => function ($username, $password) {
                    return User::restLogin($username, $password);
				}
            ]
	    ]);
	}


	public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'],$actions['view'],$actions['create'], $actions['update'],$actions['delete'], $actions['options']);
        return $actions;
    }

	public function actionIndex()
    {
        $params = ArrayHelper::merge(Yii::$app->request->queryParams,Yii::$app->request->bodyParams);
        $expirationTime = Yii::$app->jwt->expirationTime;
        $signer = new Sha256();
        $accessKey = Yii::$app->jwt->jti;
        $token = Yii::$app->jwt->builder
            ->issuedBy(Yii::$app->jwt->serverName) // Configures the issuer (iss claim)
            ->canOnlyBeUsedBy(Yii::$app->jwt->iss) // Configures the audience (aud claim)
            ->identifiedBy(Yii::$app->user->identity->$accessKey, true) // Configures the id (jti claim), replicating as a header item
            ->issuedAt(time()) // Configures the time that the token was issue (iat claim)
            ->expiresAt($expirationTime) // Configures the expiration time of the token (exp claim)
            ->with('uid', Yii::$app->user->identity->id) // Configures a new claim, called "uid"
            ->with('username', Yii::$app->user->identity->username) // Configures a new claim, called "uid"
            ->sign($signer, Yii::$app->jwt->privateKey) // creates a signature using your private key
            ->getToken(); // Retrieves the generated token

        // Yii::trace($token->verify($signer, Yii::$app->jwt->publicKey),'dev');
        return ['jwt'=>$token->__toString(),'expiresAt'=> $expirationTime];
    }

}
