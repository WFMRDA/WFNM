<?php

namespace rest\models\jwt;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Claim\Factory as ClaimFactory;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Parsing\Decoder;
use Lcobucci\JWT\Parsing\Encoder;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use Yii;
use yii\base\Component;
use yii\base\InvalidParamException;
use Lcobucci\JWT\Signer\Key;
use yii\helpers\ArrayHelper;
use Lcobucci\JWT\Signer\Rsa\Sha256;

/**
 * JSON Web Token implementation, based on this library:
 * https://github.com/lcobucci/jwt
 *
 * @author Dmitriy Demin <sizemail@gmail.com>
 * @since 1.0.0-a
 */
class Jwt extends Component
{

    /**
     * @var array Supported algorithms
     * @todo Add RSA, ECDSA suppport
     */
    public $supportedAlgs = [
        'HS256' => 'Lcobucci\JWT\Signer\Hmac\Sha256',
        'RS256' => 'Lcobucci\JWT\Signer\Rsa\Sha256',
        'HS384' => 'Lcobucci\JWT\Signer\Hmac\Sha384',
        'HS512' => 'Lcobucci\JWT\Signer\Hmac\Sha512',
    ];

    /**
     * @var array|null $key The key, or map of keys.
     */
    public $keys = [
        'private' => '@rest/keys/private.pem',
        'public' =>  '@rest/keys/public.pem'
    ];

    /**
     * @var string Passphrase used creating keys.
     */
    public $passphrase = '';
    public $uniqueToken = 'access_token';
    public $serverName;
    public $exp = 3600;
    public $_user;

    public function getUser(){
        return $this->_user;
    }
    public function getExpirationTime(){
        return $this->exp + time();
    }

    public function getIss(){
        return ($this->serverName == null) ? Yii::$app->request->serverName : $this->serverName;
    }

    public function getJti(){
        return $this->uniqueToken;
    }

    /**
     * @see [[Lcobucci\JWT\Builder::__construct()]]
     * @return Builder
     */
    public function getBuilder(Encoder $encoder = null, ClaimFactory $claimFactory = null){
        return new Builder($encoder, $claimFactory);
    }

    /**
     * @see [[Lcobucci\JWT\Parser::__construct()]]
     * @return Parser
     */
    public function getParser(Decoder $decoder = null, ClaimFactory $claimFactory = null){
        return new Parser($decoder, $claimFactory);
    }

    /**
     * Parses the JWT and returns a token class
     * @param string $token JWT
     * @return Token|null
     */
    public function loadToken($token, $validate = true, $verify = true)
    {
        // Yii::trace($token,'dev');
        try {
            $token = $this->getParser()->parse((string)$token);
        } catch (\RuntimeException $e) {
            Yii::warning("Invalid JWT provided: " . $e->getMessage(), 'jwt');
            return null;
        } catch (\InvalidArgumentException $e) {
            Yii::warning("Invalid JWT provided: " . $e->getMessage(), 'jwt');
            return null;
        }
        if ($validate && !$this->validateToken($token)) {
            return null;
        }
        if ($verify && !$this->verifyToken($token)) {
            return null;
        }
        return $token;
    }

    /**
     * Validate token
     * @param Token $token token object
     * @return bool
     */
    public function validateToken(Token $token, $currentTime = null)
    {
        $data = new ValidationData($currentTime);
        $data->setIssuer(Yii::$app->jwt->serverName);
        $data->setAudience(Yii::$app->jwt->serverName);
        $class = Yii::$app->user->identityClass;
        $this->_user = $class::findOne($token->getClaim('uid'));
        if($this->_user  == null){
            return false;
        }
        $accessKey = $this->jti;
        $data->setId($this->_user->$accessKey);
        return $token->validate($data);
    }

    /**
     * Validate token
     * @param Token $token token object
     * @return bool
     */
    public function verifyToken(Token $token)
    {
        $alg = $token->getHeader('alg');
        if (empty($this->supportedAlgs[$alg])) {
            throw new InvalidParamException('Algorithm '.$this->supportedAlgs[$alg].' not supported');
        }
        $signer = Yii::createObject($this->supportedAlgs[$alg]);
        return $token->verify($signer, $this->publicKey);
    }

    /**
     * Get Private Key
     * @return string
     */
    public function getPrivateKey(){
        $keyVar = ArrayHelper::getValue($this->keys,'private');
        if ($keyVar != null){
            if (substr($keyVar, 0, 1) === '@'){
                $key = 'file://'.Yii::getAlias($keyVar);
            }else{
                $key = $keyVar;
            }
        }else{
            throw new InvalidParamException('Private Key not supported');
        }
        return new Key($key,$this->passphrase);
    }

    /**
     * Get Public Key
     * @return string
     */
    public function getPublicKey(){
        $keyVar = ArrayHelper::getValue($this->keys,'public');
        if ($keyVar != null){
            if (substr($keyVar, 0, 1) === '@'){
                $key = 'file://'.Yii::getAlias($keyVar);
            }else{
                $key = $keyVar;
            }
        }else{
            throw new InvalidParamException('Public Key not supported');
        }
        return new Key($key,$this->passphrase);
    }

}
