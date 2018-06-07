<?php
namespace app\bis\controller;
use think\Controller;

class Location extends Base
{
    private $obj;
    public function _initialize(){
        $this->obj = model("BisLocation");
    }
    /**
     * 门店列表页
     */
    public function index()
    {
        $bis = $this->obj->getLocationsByBisId();
        return $this->fetch('',[
            'bis' => $bis,
        ]);

    }
    /*
     * 新增门店列表
     */
    public function add() {
        if(request()->isPost()) {
            // 第一点 检验数据 tp5 validate

            $data = input('post.');
            $bisId = $this->getLoginUser()->bis_id;
            $res = model('Bis')->get($bisId);
            $email = $res->email;
            $data['cat'] = '';
            if(!empty($data['se_category_id'])) {
                $data['cat'] = implode('|', $data['se_category_id']);
            }

            // 获取经纬度
            $lnglat = \Map::getLngLat($data['address']);
            if(empty($lnglat) || $lnglat['status'] !=0 || $lnglat['result']['precise'] !=1) {
                $this->error('无法获取数据，或者匹配的地址不精确');
            }

            // 门店入库操作
            // 总店相关信息入库
            $locationData = [
                'bis_id' => $bisId,
                'name' => $data['name'],
                'logo' => $data['logo'],
                'tel' => $data['tel'],
                'contact' => $data['contact'],
                'category_id' => $data['category_id'],
                'category_path' => $data['category_id'] . ',' . $data['cat'],
                'city_id' => $data['city_id'],
                'city_path' => empty($data['se_city_id']) ? $data['city_id'] : $data['city_id'].','.$data['se_city_id'],
                'api_address' => $data['address'],
                'open_time' => $data['open_time'],
                'content' => empty($data['content']) ? '' : $data['content'],
                'is_main' => 0,
                'xpoint' => empty($lnglat['result']['location']['lng']) ? '' : $lnglat['result']['location']['lng'],
                'ypoint' => empty($lnglat['result']['location']['lat']) ? '' : $lnglat['result']['location']['lat'],
            ];
            $locationId = model('BisLocation')->add($locationData);
            if($locationId) {
                //发送邮件
                $url = request()->domain().url('bis/register/waiting', ['id'=>$bisId]);
                $title = "o2o分店入驻申请通知";
                $content = "您提交的分店入驻申请需等待平台方审核，您可以通过点击链接<a href='".$url."' target='_blank'>查看链接</a> 查看审核状态";
                \phpmailer\Email::send($email,$title, $content);  // 线上关闭 发送邮件服务
                return $this->success('门店申请成功');
            }else {
                return $this->error('门店申请失败');
            }
        }else {
            //获取一级城市的数据
            $citys = model('City')->getNormalCitysByParentId();
            //获取一级栏目的数据
            $categorys = model('Category')->getNormalCategorysByParentId();
            return $this->fetch('', [
                'citys' => $citys,
                'categorys' => $categorys,
            ]);
        }
    }

}
