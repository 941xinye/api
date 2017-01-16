<?php
/**
 * Created by PhpStorm.
 * User: gaoyuhong
 * Date: 2016/10/11
 * Time: 上午9:44
 */

namespace app\compents;

class CRedis extends \yii\redis\Connection
{
    public function set($key, $value, $ttl = null)
    {

        if ($ttl) {
            return parent::setex($key, $ttl, $value);
        }
        return parent::set($key, $value);
    }

}
