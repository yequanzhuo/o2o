<?php
namespace app\index\controller;
use think\Controller;

class User extends Controller
{
    public function login()
    {

        // 获取session
        $user = session('o2o_user','', 'o2o');
        if($user && $user->id) {
            $this->redirect(url('index/index'));
        }
        return $this->fetch();
    }
	public function register()
	{
	    //是否post的请求，不是返回注册页面
		if(request()->isPost())
        {
            $data = input('post.');
            //基本校验数据
            $validate = validate('User');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            //返回值是bool
            if(!captcha_check($data['verifycode']))
            {
                //  校验失败
                $this->error('验证码不正确');
            }
            //严格校验数据匹配否 tp5，validate
            if($data['password'] != $data['repassword'])
            {
                $this->error('两次密码输入不一样');
            }
            //自动生成 密码的加盐字符串
            $data['code'] = mt_rand(100,10000);
            $data['password'] = md5( $data['password'].$data['code']);
            try{
                $res = model('User')->add($data);
            }catch (\Exception $e){
                $this->error($e->getMessage());
            }
            if($res){
                $this->success('注册成功',url('user/login'));
            }else{
                $this->error('注册失败');
            }
        }else{
            return $this->fetch();
        }
	}


	/*
	 * 检查登录
	 */
    public function logincheck(){

        if(!request()->isPost()){
            $this->error('提交不合法');
        }
        $data = input('post.');
        //严格校验数据匹配否 tp5，validate
        try{
           $user = model('User')->getUserByUsername($data['username']);
        }catch(\Exception $e){
            $this->error($e->getMessage());
        }

        //用户是否存在$user是对象
        if(!$user || $user->status != 1 ){
            $this->error('该用户不存在');
        }

        //判定密码是否合理
        if(md5($data['password'].$user->code) != $user->password){
            $this->error('密码错误 ');
        }

        // 登录成功
        model('User')->updateById(['last_login_time'=>time()], $user->id);


        //把用户信息记录到session
        session('o2o_user', $user, 'o2o');

        $this->success('登录成功', url('index/index'));




    }

    public function logout() {
        session(null, 'o2o');
        $this->redirect(url('user/login'));
    }
}
