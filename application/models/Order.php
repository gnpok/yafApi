<?php

/**
 * Created by PhpStorm.
 * User: gnp
 * Date: 2016/10/11
 * Time: 14:56
 */
class OrderModel extends CommonModel
{
    const ORDER = 'order';

    public function getList()
    {
        $db = MysqliDb::getInstance();
        return $db->get(self::ORDER, 10); //contains an Array 10 users
    }

}