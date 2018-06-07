<?php
namespace app\admin\controller;
use think\Controller;
class Register extends  Controller
{
    public function index()
    {

        /*
        * 是否post的请求，不是返回注册页面
        */
        if(request()->isPost())
        {
            $data = input('post.');
            //返回值是bool
            if(!captcha_check($data['verifycode']))
            {
                //  校验失败
                $this->error('验证码不正确');
            }
            if($data['password'] != $data['repassword'])
            {
                $this->error('两次密码输入不一样');
            }
            //自动生成 密码的加盐字符串
            $data['code'] = mt_rand(100,10000);
            $data['password'] = md5( $data['password'].$data['code']);

            try{
                $res = model('Admin')->add($data);
            }catch (\Exception $e){
                $this->error($e->getMessage());
            }
            if($res){
                $this->success('注册成功',url('login/index'));
            }else{
                $this->error('注册失败');
            }

        }else{

            return $this->fetch();
        }

    }

}