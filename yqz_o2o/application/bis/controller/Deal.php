<?php
namespace app\bis\controller;
use think\Controller;
class Deal extends  Base
{
    private $obj;

    public function _initialize()
    {
        $this->obj = model("Deal");
    }
    /**
     * @return mixed 商户中心的 deal列表页面
     */
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

        $deals = $this->obj->getNormalDealsForDealList($sdata);
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



    public function  add() {
        $bisId = $this->getLoginUser()->bis_id;
        if(request()->isPost()) {
            // 走插入逻辑
            $data = input('post.');
            // 严格校验提交的数据， tp5 validate 小伙伴自行完成，
            //获取xpoint,ypoint
            $location = model('BisLocation')->get($data['location_ids'][0]);
            $deals = [
                'bis_id' => $bisId,
                'name' => $data['name'],
                'image' => $data['image'],
                'category_id' => $data['category_id'],
                'se_category_id' => empty($data['se_category_id']) ? '' : implode(',', $data['se_category_id']),
                'city_id' => $data['city_id'],
                'location_ids' => empty($data['location_ids']) ? '' : implode(',', $data['location_ids']),
                'start_time' => strtotime($data['start_time']),
                'end_time' => strtotime($data['end_time']),
                'total_count' => $data['total_count'],
                'origin_price' => $data['origin_price'],
                'current_price' => $data['current_price'],
                'coupons_begin_time' => strtotime($data['coupons_begin_time']),
                'coupons_end_time' => strtotime($data['coupons_end_time']),
                'notes' => $data['notes'],
                'description' => $data['description'],
                'bis_account_id' => $this->getLoginUser()->id,
                'xpoint' => $location->xpoint,
                'ypoint' => $location->ypoint,


            ];

            $id = model('Deal')->add($deals);
            if($id) {
                $this->success('添加成功', url('deal/index'));
            }else {
                $this->error('添加失败');
            }

        }else {
            //获取一级城市的数据
            $citys = model('City')->getNormalCitysByParentId();
            //获取一级栏目的数据
            $categorys = model('Category')->getNormalCategorysByParentId();
            return $this->fetch('', [
                'citys' => $citys,
                'categorys' => $categorys,
                'bislocations' => model('BisLocation')->getNormalLocationByBisId($bisId),
            ]);
        }
    }
}
