<?php
namespace app\common\model;

use think\Model;

class Featured extends BaseModel
{
    /**
     * 根据类型获取数据
     * @param $type
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getFeaturedsByType($type) {
        $data = [
            'type' => $type,
            //不等于-1
            'status' => ['neq', -1],
        ];

        $order = [
            'listorder' => 'desc',
        ];

        $result = $this->where($data)
            ->order($order)
            ->paginate();
        return $result;
    }


}