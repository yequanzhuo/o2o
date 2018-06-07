<?php
namespace app\common\model;
use think\Model;
class User extends BaseModel
{
    /*
     *添加用户
     */
    public function add($data)
    {
        //如果提交的数据不是数组，抛异常
        if(!is_array($data)){
            exception('传递的数据不是数组');
        }
        $data['status'] = 1;
        //allowField(true)过滤字段 比如说repassword
        return $this->data($data)->allowField(true)
            ->save();


    }

    public function  login(){
        return $this->fetch();
    }

    /**
     * 通过用户名校验来登陆
     * @param $username
     */
    public function getUserByUsername($username){
        if(!$username){
            exception('数据不合法');
        }
        $data = ['username' => $username];
        return $this->where($data)
            ->find();


    }
}