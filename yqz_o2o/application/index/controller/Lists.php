<?php
namespace app\index\controller;
use think\Controller;

class Lists extends Base
{
    public function index()
    {

        $firstCatIds = [];
        //1.获取一级分类
        $categorys = model('Category')->getNormalCategorysByParentId();
        //var_dump($categorys);exit;
        //2.捕获id 有三种状态 0/一级分类的id/二级分类的id
        $id = input('id',0,'intval');
        $data = [];
        foreach($categorys as $category) {
            $firstCatIds[] = $category->id;
        }
        /*var_dump($firstCatIds);exit;
        array (size=5)
             0 => int 9
             1 => int 8
             2 => int 7
             3 => int 2
             4 => int 1
        */
        if(in_array($id, $firstCatIds)) { // 一级分类
            $categoryParentId = $id;
            $data['category_id'] = $id;
        }elseif($id) { // 二级分类
            // 获取二级分类的数据
            $category = model('Category')->get($id);
            if(!$category || $category->status !=1) {
                $this->error('数据不合法');
            }
            $categoryParentId = $category->parent_id;
            $data['se_category_id'] = $id;
        }else{ // 0
            $categoryParentId = 0;
        }
        //获取父类下的所有 子分类/二级分类
        $sedcategorys = [];
        if($categoryParentId) {
            $sedcategorys = model('Category')->getNormalCategorysByParentId($categoryParentId);
        }
        $orders = [];
        // 排序数据获取的逻辑
        $order_sales = input('order_sales','');
        $order_price = input('order_price','');
        $order_time = input('order_time','');
        if(!empty($order_sales)) {
            $orderflag = 'order_sales';
            $orders['order_sales'] = $order_sales;
        }elseif(!empty($order_price)) {
            $orderflag = 'order_price';
            $orders['order_price'] = $orderflag;//这个地方默认写错了。注意修改下哦
        }elseif(!empty($order_time)) {
            $orderflag = 'order_time';
            $orders['order_time'] = $order_time;
        }else{
            $orderflag = '';
        }
        $data['city_id'] = $this->city->id; // add
        // 根据上面条件来查询商品列表数据
        $deals = model('Deal')->getDealByConditions($data, $orders);
        return $this->fetch('',[
            'categorys' => $categorys,
            'id' => $id,
            'categoryParentId' => $categoryParentId,
            'sedcategorys' => $sedcategorys,
            'orderflag' => $orderflag,
            'deals' => $deals,

        ]);

    }
}
