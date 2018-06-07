<?php
namespace app\common\model;
use think\Model;
class Admin extends BaseModel
{

    /**
     * 添加管理员
     * @param $data
     * @return false|int|mixed
     * @throws \Exception
     */
    public function add($data)
    {
        //如果提交的数据不是数组，抛异常
        if(!is_array($data)){
            exception('传递的数据不是数组');
        }
        //allowField(true)过滤字段 比如说repassword
        return $this->data($data)->allowField(true)
            ->save();
    }

    /**
     * 每次登录，通过id修改时间
     * @param $data
     * @param $id
     * @return false|int
     */
    public function updateById($data, $id) {
        // allowField 过滤data数组中非数据表中的数据
        return $this->allowField(true)->save($data, ['id'=>$id]);
    }

    /**
     * 通过状态获取管理员数据
     * @param int $status
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getUsersByStatus($status=0)
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




}