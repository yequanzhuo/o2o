<?php
namespace app\admin\controller;
use think\Controller;

class Index extends Base
{
    public function index()
    {
       return $this->fetch();

    }
	public function test()
	{
		//\Map::getLngLat('北京昌平沙河地铁');
		return 'yequanzhuo';	
	}
	
	public function map(){
		return 	\Map::staticimage('北京昌平沙河地铁');
	}
	public function welcome()
	{
	   // \phpmailer\Email::send('2931545280@qq.com','tp5-email','success');
		//return '邮件发送成功';
		return "欢迎来到主后台页面";
	}
	
}
