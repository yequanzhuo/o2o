<?php
namespace app\admin\controller;
use think\Controller;

class City extends Base
{
    private $obj;

    public function _initialize()
    {
        $this->obj = model("City");
    }

    public function index()
    {
        $parentId = input('get.parent_id', 0, 'intval');
        $citys = model('City')->getFirstCitys($parentId);
        return $this->fetch('', [
            'citys' => $citys,
        ]);

    }

    public function add()
    {
        $citys = model('City')->getNormalFirstCity();
        return $this->fetch('', [
            //第一个参数是默认模版，第二个参数是将数据加入到模板中
            'citys' => $citys,
        ]);

    }

    public function save()
    {
        //做严格判定
        if (!request()->isPost()) {
            $this->error('请求失败');
        }
        $data = input('post.');


        if (!empty($data['id'])) {
            return $this->update($data);
        }
        //把$data放到model层
        $res = model('City')->add($data);
        if ($res) {
            $this->success('新增成功');

        } else {
            $this->error('新增失败');
        }


    }

    public function edit($id=0){
        if(intval($id)<1){
            $this->error('参数不合法');
        }
        $city = $this->obj->get($id);
        $citys = $this->obj->getNormalFirstCity();
        return $this->fetch('',[
            'citys'=>$citys,
            'city'=> $city,
        ]);
    }

    public function update($data){
        $res = $this->obj->save($data, ['id' => intval($data['id'])]);
        if($res)
        {
            $this->success('更新成功');

        }else{
            $this->error('更新失败');
        }

    }
}
