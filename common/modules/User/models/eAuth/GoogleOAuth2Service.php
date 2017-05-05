<?php
/**
 * GoogleOAuth2Service class file.
 *
 * Register application: https://code.google.com/apis/console/
 *
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/yii2-eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace common\modules\User\models\eAuth;

use yii\helpers\ArrayHelper;

/**
 * Google provider class.
 *
 * @package application.extensions.eauth.services
 */
class GoogleOAuth2Service extends \nodge\eauth\services\GoogleOAuth2Service
{
	protected $scopes = [self::SCOPE_USERINFO_PROFILE,self::SCOPE_USERINFO_EMAIL,self::SCOPE_EMAIL,'https://www.googleapis.com/auth/userinfo.email'];
	protected function fetchAttributes()
	{
        $this->setScope($this->scopes);
        $info = $this->makeSignedRequest('https://www.googleapis.com/plus/v1/people/me');
		$this->attributes = [
		    'id' => ArrayHelper::getValue($info,'id'),
			'firstName'=> ArrayHelper::getValue($info,'name.givenName',''),
			'lastName'=> ArrayHelper::getValue($info,'name.familyName',''),
		    'url' =>ArrayHelper::getValue($info,'url',''),
		    'image' =>ArrayHelper::getValue($info,'image.url',''),
		];
		if(isset($info['emails'])){
			$this->attributes['email'] = ArrayHelper::getValue($info['emails'][0],'value','');
		}else{
			$this->attributes['email'] = null;
		}
		if(!empty($this->attributes['image'])){
			$this->attributes['image'] = substr($this->attributes['image'],0,strpos($this->attributes['image']."?sz=","?sz=")) . '?sz=500';
		}
        $this->attributes['token'] = $this->getAccessTokenData();
		return true;
	}


}
