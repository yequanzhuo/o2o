<?php
namespace app\admin\controller;
use think\Controller;

class Featured extends Base
{
    private $obj;
    public function _initialize(){
        $this->obj = model("Featured");
    }

    public function index() {

        $data = input('get.');
        // 获取推荐位类别
        $types = config('featured.featured_type');
        $type = input('get.type', 0 ,'intval');
        // 获取列表数据
        $results = $this->obj->getFeaturedsByType($type);
       // print_r($types);exit;
        return $this->fetch('', [
            'types' => $types,
            'results' => $results,
            'type_key' => empty($data['type']) ? '' : $data['type'],
            ]);
    }
    public function add() {
        if(request()->isPost()) {
            // 入库的逻辑
            $data = input('post.');
            // 数据需要做严格校验 validate  小伙伴仿照之前我们做的 自行完成

            $id = model('Featured')->add($data);
            if($id) {
                $this->success('添加成功');
            }else{
                $this->error('添加失败');
            }
        }else {
            // 获取推荐位类别
            $types = config('featured.featured_type');
            return $this->fetch('', [
                'types' => $types,
            ]);
        }
    }

    public function detail()
    {
        $id = input('get.id');
        if(empty($id))
        {
            $this->error('ID不存在');
        }
        $types = config('featured.featured_type');
        $type = input('get.type', 0 ,'intval');

        // 获取列表数据
        //$results = $this->obj->getFeaturedsByType($type);
        $results = model('Featured')->get($id);
        return $this->fetch('', [
            'types' => $types,
            'results' => $results,
            'id' => $id,
        ]);

    }

    public function update(){
        $data = input('post.');
        $res = model('Featured')->save($data,['id' => $data['id']]);
        if($res) {
            $this->success('修改成功','featured/index');
        }else{
            $this->error('修改失败');
        }
    }

    //排序逻辑
    public function listorder($id,$listorder){
        $res = $this->obj->save(['listorder'=>$listorder],['id'=>$id]);
        //var_dump($res);exit;
        if($res){
            //result(data,code,msg)
            $this->result($_SERVER['HTTP_REFERER'],1,'success');

        }else{
            $this->result($_SERVER['HTTP_REFERER'],0,'更新失败');

        }

    }
}
