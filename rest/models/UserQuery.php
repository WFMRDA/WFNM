<?php

namespace rest\models;

use yii\db\ActiveQuery;
use Yii;

class UserQuery extends ActiveQuery
{
    public function active(){

        return $this->select([
                '{{user}}.*',
                'IF(blocked_at IS NOT NULL, false ,true) as isActive',
            ])->andHaving(['isActive'=>true]);
    }


}
