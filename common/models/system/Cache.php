<?php

namespace common\models\system;

use Yii;
use common\models\helpers\WfnmHelpers;

class Cache extends yii\redis\Cache{
    /**
     * Stores a value identified by a key into cache.
     * If the cache already contains such a key, the existing value and
     * expiration time will be replaced with the new ones, respectively.
     *
     * @param mixed $key a key identifying the value to be cached. This can be a simple string or
     * a complex data structure consisting of factors representing the key.
     * @param mixed $value the value to be cached
     * @param int $duration default duration in seconds before the cache will expire. If not set,
     * default [[defaultDuration]] value is used.
     * @param Dependency $dependency dependency of the cached item. If the dependency changes,
     * the corresponding value in the cache will be invalidated when it is fetched via [[get()]].
     * This parameter is ignored if [[serializer]] is false.
     * @return bool whether the value is successfully stored into cache
     */
    public function set($key, $value, $duration = null, $dependency = null)
    {
        if ($duration === null) {
            $duration = $this->nextRefreshTime;
        }

        if ($dependency !== null && $this->serializer !== false) {
            $dependency->evaluateDependency($this);
        }
        if ($this->serializer === null) {
            $value = serialize([$value, $dependency]);
        } elseif ($this->serializer !== false) {
            $value = call_user_func($this->serializer[0], [$value, $dependency]);
        }
        $key = $this->buildKey($key);

        // Yii::trace($key,'dev');
        //         Yii::trace($duration,'dev');

        return $this->setValue($key, $value, $duration);
    }


    /**
     * @var int Seconds till next 5 min clock interval which the fire cache needs to be reset.
     */
    protected $_nextRefreshTime;

     /**
     *  Sets Next Time Refresh Variable for till the next 5 min clock interval which the fire cache needs to be reset.
     */
    protected function setNextRefreshTime(){
        $this->_nextRefreshTime = WfnmHelpers::getNextRefreshTime();
    }

    /**
     * Gets Next Time Refresh Variable for till the next 5 min clock interval which the fire cache needs to be reset.
     * @return int Seconds till next 5 min clock interval which the fire cache needs to be reset.
     */
    protected function getNextRefreshTime(){
        if(!isset($this->_nextRefreshTime)){
            $this->setNextRefreshTime();
        }
        return $this->_nextRefreshTime;
    }
}
