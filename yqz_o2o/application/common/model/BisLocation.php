<?php
namespace app\common\model;
use think\Model;
use think\Session;
class BisLocation extends BaseModel
{
    /*
     * 通过状态来获取总店和分店的数据
     */
    public function getBisByStatus($status=0)
    {
        $order = [
            'id' => 'desc',
        ];
        $data = [
            'status' => $status,
        ];
        $result = $this->where($data)
            ->order($order)
            ->paginate();
        return $result;
    }
    public function getNormalLocationByBisId($bisId)
    {
        $data = [
            'bis_id' =>$bisId,
            'status' =>1,
        ];
        $result = $this->where($data)
            ->order('id','desc')
            ->select();
        return $result;
    }

    //获取分店信息

    public function getNormalLocationsInId($ids) {
        $data = [
            'id' => ['in', $ids],
            'status' => 1,
        ];
       // print_r($data);exit; array[]
        return $this->where($data)->select();

    }


    /**
     * 通过bis_id获得门店列表
     * @param int $status
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getLocationsByBisId()
    {
        $ret = Session::get('bisAccount','bis');
        $bisId = $ret->bis_id;
        //halt($bisId);
        $order = [
            'id' => 'desc',
        ];
        $data = [
            'bis_id' => $bisId,
        ];
        $result = $this->where($data)
            ->order($order)
            ->paginate();
        return $result;
    }

}