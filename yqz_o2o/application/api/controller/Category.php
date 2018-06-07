<?php
namespace app\api\controller;
use think\Controller;

class Category extends Controller
{
	private $obj;
	public function _initialize(){
		$this->obj = model("Category");
	}
    public function getCategorysByParentId()
    {
		$id = input('post.id',0,'intval');
		if(!$id){
			$this->error('ID不合法');
		}
		//通过id获取二级城市
		$categorys = $this->obj->getNormalCategorysByParentId($id);
		if(!$categorys){
			return show(0,'error');
		}
		return show(1,'success',$categorys);
		
		
    }
	
}