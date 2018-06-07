<?php
namespace app\api\controller;
use think\Controller;
use think\Request;
use think\File;

class Image extends Controller
{
    public function upload(){
        $file = Request::instance()->file('file');
        //给定一个目录
        $info = $file->move('upload');
        //print_r($info); getPathname是$info的一个属性(通过控制台可以看到)
        if($info && $info->getPathname()){
            //第三个参数，将路径返回给前端
            return show(1,'success','/'.$info->getPathname());
        }
        return show(0,'upload error');
    }


}