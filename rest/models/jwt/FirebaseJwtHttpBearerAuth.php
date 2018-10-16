<?php
namespace rest\models\jwt;

use Yii;
use yii\di\Instance;
use yii\filters\auth\AuthMethod;
use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Auth\UserRecord;
use rest\models\User;

/**
 * JwtHttpBearerAuth is an action filter that supports the authentication method based on JSON Web Token.
 *
 * You may use JwtHttpBearerAuth by attaching it as a behavior to a controller or module, like the following:
 *
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         'bearerAuth' => [
 *             'class' => \sizeg\jwt\JwtHttpBearerAuth::className(),
 *         ],
 *     ];
 * }
 * ```
 *
 * @author Dmitriy Demin <sizemail@gmail.com>
 * @since 1.0.0-a
 */
class FirebaseJwtHttpBearerAuth extends AuthMethod
{


    /**
     * @var string A "realm" attribute MAY be included to indicate the scope
     * of protection in the manner described in HTTP/1.1 [RFC2617].  The "realm"
     * attribute MUST NOT appear more than once.
     */
    public $realm = 'api';

    /**
     * @var string Authorization header schema, default 'Bearer'
     */
    public $schema = 'Bearer';

    public $authField = 'access_token';

    /**
     * @var callable a PHP callable that will authenticate the user with the JWT payload information
     *
     * ```php
     * function ($token, $authMethod) {
     *    return \app\models\User::findOne($token->getClaim('id'));
     * }
     * ```
     *
     * If this property is not set, the username information will be considered as an access token
     * while the password information will be ignored. The [[\yii\web\User::loginByAccessToken()]]
     * method will be called to authenticate and login the user.
     */
    public $auth;


    protected $firebase;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $serviceAccount = ServiceAccount::fromJsonFile(Yii::getAlias('@rest/keys/firebase.'. YII_ENV . '.json'));
        $this->firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
    }

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $authHeader = $request->getHeaders()->get('Authorization');
        if ($authHeader !== null && preg_match('/^' . $this->schema . '\s+(.*?)$/', $authHeader, $matches)) {
            $idTokenString = $matches[1];

            try {
                $verifiedIdToken = $this->firebase->getAuth()->verifyIdToken($idTokenString,true);
                $uid = $verifiedIdToken->getClaim('sub');
                $firebaseUser = $this->firebase->getAuth()->getUser($uid);
                $providerData = $firebaseUser->providerData[0];
                $firebaseUser = $firebaseUser->toArray();

                $identity = $user->findByEmail($firebaseUser['email']);
                Yii::$app->user->setIdentity($identity);
                return $identity;
            } catch (InvalidToken $e) {
                // Yii::trace($e->getMessage(),'dev');
                return null;
            }
        }
        return null;
    }

}
