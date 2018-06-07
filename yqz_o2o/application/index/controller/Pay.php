<?php
namespace app\index\controller;
use think\Controller;
class Pay extends Base
{
    public function index() {
        if(!$this->getLoginUser()) {
            $this->error('请登录', 'user/login');
        }
        $orderId = input('get.id', 0, 'intval');
        if(empty($orderId)) {
            $this->error('请求不合法');
        }
        $order = model('Order')->get($orderId);
        if(empty($order) || $order->status != 1 || $order->pay_status !=0 ) {
            $this->error('无法进行该项操作');
        }
        // 严格判定 订单是否 是用户 本人
        if($order->username != $this->getLoginUser()->username) {
            $this->error('不是你的订单你瞎点个啥，做人要厚道');
        }
        $deal = model('Deal')->get($order->deal_id);
        // 生成二维码
        Vendor('phpqrcode.phpqrcode');
        $level = 3;
        $size = 4;
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        //生成二维码图片,使用vendor要记得\
        $object = new \QRcode();
        $image_url = "{$order->username}_{$order->out_trade_no}.png";
        $text = "{$order->username}用户，您需支付{$order->total_price}元";
        $object->png($text,$image_url, $errorCorrectionLevel, $matrixPointSize, 2);
        //二维码的路径在public 下
        $url = "/{$image_url}";
        return $this->fetch('', [
            'deal' => $deal,
            'order' => $order,
            'url' => $url,
        ]);
    }
    //作用于微信支付第三步骤，跳转页面
    public function paysuccess(){
        //判断用户是否登录
        if(!$this->getLoginUser()){
            $this->error('请先登录','user/login');

        }
        return $this->fetch();


    }


}
