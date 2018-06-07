<?php
namespace app\Common\model;
use think\Model;
class City extends Model{

    protected $autoWriteTimestamp = true;
    public function add($data)
    {
        $data['status'] = 1;
        $this->save($data);
        return $this->id;

    }

    //获得省级城市(主后台城市列表)
    public function getFirstCitys($parentId = 0)
    {
        $data = [
            'parent_id' => $parentId,
            'status' => ['neq',-1],
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc',
        ];
        $result = $this->where($data)
            ->order($order)
            ->paginate();//默认15条
        return $result;

    }
    //(主后台城市列表)
    public function getNormalFirstCity()
    {
        $data = [
            'status' => 1,
            'parent_id' => 0,
        ];
        $order = [
            'id' => 'desc',
        ];
        return $this->where($data)
            ->order($order)
            ->select();

    }

	//获得省级城市
	public function getNormalCitysByParentId($parentId=0){
		$data = [
			'status' => 1,
			'parent_id' => $parentId,
		];
		
		$order = [
			'id' => 'desc', 
		];
		
		return $this->where($data)
			->order($order)
			->select();
	}
    //获得市级城市
    public function getNormalCitys(){
        $data = [
            'status' => 1,
            //['gt',0]获得大于0的数据
            'parent_id' => ['gt',0],
        ];

        $order = [
            'id' => 'desc',
        ];

        return $this->where($data)
            ->order($order)
            ->select();
    }
}