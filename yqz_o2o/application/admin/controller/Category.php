<?php
namespace app\admin\controller;
use think\Controller;

class Category extends Base
{
	private $obj;
	public function _initialize(){
		$this->obj = model("Category");
	}
    public function index()
    {
	   $parentId = input('get.parent_id',0,'intval');
	   $categorys = model('Category')->getFirstCategorys($parentId); 
       return $this->fetch('',[
           'categorys'=>$categorys,
       ]);

    }
	public function add()
	{
		$categorys = model('Category')->getNormalFirstCategory();
		return $this->fetch('',['categorys'=>$categorys,]);//第一个参数是默认模版，第二个参数是将数据加入到模板中
		
	}
	public function save()
	{
		//print_r(input('post.'));//获取数据方式
		//做严格判定
		if(!request()->isPost()){
			$this->error('请求失败');
		}
		$data = input('post.');
		//$data['status'] = 10;
		$validate = validate('Category');
		if(!$validate->scene('add')->check($data))
		{
			$this->error($validate->getError());
			
		}
		if(!empty($data['id'])){
			return $this->update($data);
		}
		//把$data放到model层
		$res = model('Category')->add($data);
		if($res)
		{
			$this->success('新增成功');
			
		}else{
			$this->error('新增失败');
		}
	//编辑页面	
	}
	public function edit($id=0){
		if(intval($id)<1){
			$this->error('参数不合法');
		}
		$category = $this->obj->get($id);
		$categorys = $this->obj->getNormalFirstCategory();
		return $this->fetch('',[
			'categorys'=>$categorys,
			'category'=> $category,
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
	/*
	//状态修改
	public function status(){
		$data = input('get.');
		$validate = validate('Category');
		if(!$validate->scene('status')->check($data))
		{
			$this->error($validate->getError());
		}
		$res=$this->obj->save(['status'=>$data['status']],['id'=>$data['id']]);
		if($res){
			$this->success('状态更新成功');
		}else{
			$this->error('状态更新失败');
		}

	}*/
	
	
	
}
