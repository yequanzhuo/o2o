<?php
namespace app\admin\controller;
use think\Controller;

class Admin extends Base
{
    /**
     * 管理员列表
     * @return mixed
     */
    public function index()
    {
        $status = input('get.status',1);
        $users = model('Admin')->getUsersByStatus($status);
        return $this->fetch('',[
            'users' => $users,
        ]);
    }

    /**
     * 跳转到添加管理员页面
     * @return mixed
     */
    public function add()
    {
        return $this->fetch();

    }

    /**
     * 保存管理员数据
     * @return mixed
     */
    public function save()
    {
        //做严格判定
        if (!request()->isPost()) {
            $this->error('请求失败');
        }
        $data = input('post.');
        //自动生成 密码的加盐字符串
        $data['code'] = mt_rand(100,10000);
        $data['password'] = md5( $data['password'].$data['code']);


        //把$data放到model层
        $res = model('Admin')->add($data);
        if ($res) {
            $this->success('新增成功');

        } else {
            $this->error('新增失败');
        }

    }




}
