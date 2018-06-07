<?php
namespace app\bis\controller;
use think\Controller;

class Admin extends Base
{
    /**
     * 管理员列表
     * @return mixed
     */
    public function index()
    {
        $status = input('get.status', 1, 'intval');
        //dump($status);exit;
        $users = model('BisAccount')->getUsersByStatus($status);
        return $this->fetch('',[
            'users' => $users,
        ]);
    }


}
