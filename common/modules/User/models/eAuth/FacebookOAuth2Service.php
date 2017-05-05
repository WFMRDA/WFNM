<?php
/**
 * FacebookOAuth2Service class file.
 *
 * Register application: https://developers.facebook.com/apps/
 *
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/yii2-eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace common\modules\User\models\eAuth;

use yii\helpers\ArrayHelper;
/**
 * Facebook provider class.
 *
 * @package application.extensions.eauth.services
 */
class FacebookOAuth2Service extends \nodge\eauth\services\FacebookOAuth2Service
{

    protected $scopes = [
        self::SCOPE_EMAIL,
        self::SCOPE_USER_BIRTHDAY,
        self::SCOPE_USER_HOMETOWN,
        self::SCOPE_USER_LOCATION,
        self::SCOPE_USER_PHOTOS,
    ];

	// https://developers.facebook.com/docs/authentication/permissions/
	protected function fetchAttributes()
	{
		$info = $this->makeSignedRequest('me', [
            'query' => [
                'fields' => join(',', [
                    'id',
                    'name',
                    'link',
                    'email',
                    'verified',
                    'first_name',
                    'last_name',
                    'gender',
                    'birthday',
                    'hometown',
                    'location',
                    'locale',
                    'timezone',
                    'updated_time',
                ])
            ]
        ]);

		$this->attributes = [
			'id' => ArrayHelper::getValue($info,'id'),
			'firstName'=> ArrayHelper::getValue($info,'first_name',''),
			'lastName'=> ArrayHelper::getValue($info,'last_name',''),
			'url' =>ArrayHelper::getValue($info,'link',''),
			'email' =>ArrayHelper::getValue($info,'email',''),
		];

        $this->attributes['image'] = $this->baseApiUrl.$this->getId().'/picture?width=500&height=500';
        $this->attributes['token'] = $this->getAccessTokenData();

		return true;
	}

}
