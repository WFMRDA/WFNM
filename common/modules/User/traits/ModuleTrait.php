<?php


namespace common\modules\User\traits;

use common\modules\User\Module;

/**
 * Trait ModuleTrait
 * @property-read Module $module
 * @package common\modules\User\traits
 */
trait ModuleTrait
{
    /**
     * @return Module
     */
    public function getModule()
    {
        return \Yii::$app->getModule('user');
    }
}