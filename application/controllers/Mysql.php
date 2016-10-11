<?php

/**
 * @name IndexController
 * @author vagrant
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class MysqlController extends Yaf_Controller_Abstract
{


    public function insertAction()
    {

    }

    public function updateAction()
    {

    }

    public function selectAction()
    {
        $order = new OrderModel();
        $list = $order->getList();
    }

    public function deleteAction()
    {

    }

    public function joinAction()
    {

    }
}
