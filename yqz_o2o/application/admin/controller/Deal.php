<?php
namespace app\admin\controller;
use think\Controller;

class Deal extends Base
{
    private $obj;

    public function _initialize()
    {
        $this->obj = model("Deal");
    }

    public function index()
    {
        $data = input('get.');
        $sdata = [];
        if (!empty($data['start_time']) && !empty($data['end_time']) && strtotime($data['end_time']) > strtotime($data['start_time'])) {
            $sdata['create_time'] = [
                ['gt', strtotime($data['start_time'])],
                ['lt', strtotime($data['end_time'])],
            ];
        }
        if (!empty($data['category_id'])) {
            $sdata['category_id'] = $data['category_id'];
        }
        if (!empty($data['city_id'])) {
            $sdata['city_id'] = $data['city_id'];
        }
        if (!empty($data['name'])) {
            $sdata['name'] = ['like', '%' . $data['name'] . '%'];
        }
        $cityArrs = $categoryArrs = [];
        $categorys = model("Category")->getNormalCategorysByParentId();
        foreach ($categorys as $category) {
            $categoryArrs[$category->id] = $category->name;
        }

        $citys = model("City")->getNormalCitys();
        foreach ($citys as $city) {
            $cityArrs[$city->id] = $city->name;
        }

        $deals = $this->obj->getNormalDeals($sdata);
        //print_r($categorys);exit;
        return $this->fetch('', [
            'categorys' => $categorys,
            'citys' => $citys,
            'deals' => $deals,
            'category_id' => empty($data['category_id']) ? '' : $data['category_id'],
            'city_id' => empty($data['city_id']) ? '' : $data['city_id'],
            'start_time' => empty($data['start_time']) ? '' : $data['start_time'],
            'end_time' => empty($data['end_time']) ? '' : $data['end_time'],
            'name' => empty($data['name']) ? '' : $data['name'],
            'categoryArrs' => $categoryArrs,
            'cityArrs' => $cityArrs,
        ]);
    }
    //团购券申请列表用的
    public function apply(){

        $cityArrs = $categoryArrs = [];
        $categorys = model("Category")->getNormalCategorysByParentId();
        foreach ($categorys as $category) {
            $categoryArrs[$category->id] = $category->name;
        }

        $citys = model("City")->getNormalCitys();
        foreach ($citys as $city) {
            $cityArrs[$city->id] = $city->name;
        }

        $applyDeals = $this->obj->getDealByStatus();

        return $this->fetch('', [

            'start_time' => empty($data['start_time']) ? '' : $data['start_time'],
            'end_time' => empty($data['end_time']) ? '' : $data['end_time'],
            'categoryArrs' => $categoryArrs,
            'cityArrs' => $cityArrs,
            'applyDeals' => $applyDeals,
        ]);

    }

    // 修改状态 status 1通过  status 2不通过  status -1删掉 status 0待审
    public function status() {
        $data = input('get.');
        // 检验小伙伴自行完成
        /*$validate = validate('Bis');
        if(!$validate->scene('status')->check($data)) {
            $this->error($validate->getError());
        }*/

        $res = $this->obj->save(['status'=>$data['status']], ['id'=>$data['id']]);
        if($res ) {
            $this->success('商家团购提交状态更新成功');
        }else {
            $this->error('商家团购提交状态更新失败');
        }

    }


}
