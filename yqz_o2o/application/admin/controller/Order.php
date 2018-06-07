<?php
namespace app\admin\controller;
use think\Controller;

class Order extends Base
{
    /**
     * 订单列表
     * @return mixed
     */
    public function index()
    {
        $status = input('get.status',1);
        $orders = model('Order')->getOrdersByStatus($status);
        return $this->fetch('',[
            'orders' => $orders,
        ]);
    }

}
