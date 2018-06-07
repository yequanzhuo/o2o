<?php
namespace app\common\model;
use think\Model;
use think\Session;
class BisAccount extends BaseModel
{
    public function updateById($data, $id) {
        // allowField 过滤data数组中非数据表中的数据
        return $this->allowField(true)->save($data, ['id'=>$id]);
    }


    /**
     * 通过状态获得商家数据
     * @param int $status
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getUsersByStatus($status=0)
    {
        $ret = Session::get('bisAccount','bis');
        $username = $ret->username;
        $order = [
            'id' => 'desc',
        ];
        $data = [
            'status' => $status,
            'username' => $username,
        ];
        $result = $this->where($data)
            ->order($order)
            ->paginate();
        return $result;
    }

}