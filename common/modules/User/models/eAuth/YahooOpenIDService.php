<?php
/**
 * YahooOpenIDService class file.
 *
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/yii2-eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace common\modules\User\models\eAuth;
use yii\helpers\ArrayHelper;
/**
 * Yahoo provider class.
 *
 * @package application.extensions.eauth.services
 */
class YahooOpenIDService extends \nodge\eauth\services\YahooOpenIDService
{

	protected $name = 'yahoo';
	protected $title = 'Yahoo';
	protected $type = 'OpenID';

	protected $url = 'https://me.yahoo.com';

	protected $requiredAttributes = [
		'name' => ['fullname', 'namePerson'],
		'login' => ['nickname', 'namePerson/friendly'],
		'email' => ['email', 'contact/email'],
	];
	protected $optionalAttributes = [
		'user_id' => ['guid','/person/guid'],
		'image' => ['image', 'media/image/default'],
	];

	protected function fetchAttributes() {
		list($firstName,$lastName) = explode(' ', $this->getAttribute('name'));
		$this->attributes['firstName'] = $firstName;
		$this->attributes['lastName'] = $lastName;
		$this->attributes['id'] = ArrayHelper::getValue(\Yii::$app->request->queryParams,'openid_identity');
        $this->attributes['token'] = null;
		return true;
	}
}
