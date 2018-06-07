<?php
namespace app\admin\controller;
use think\Controller;

class Bis extends Base
{
    private $obj;
    public function _initialize(){
        $this->obj = model("Bis");
    }
    /*
     * 商户列表
     */
    public function index()
    {
        $bis = $this->obj->getBisByStatus(1);
        return $this->fetch('',[
            'bis' => $bis,
        ]);
    }
    /*
     * 商户入驻申请列表
     */
    public function apply()
    {
        $bis = $this->obj->getBisByStatus();
        return $this->fetch('',[
            'bis' => $bis,
        ]);
    }
    /*
     *入驻申请列表 编辑|查看的功能
     */
    /*
     * 商户列表
     */
    public function dellist()
    {
        $bis = $this->obj->getBisByStatus(-1);
        return $this->fetch('',[
            'bis' => $bis,
        ]);
    }
    //修改，编辑功能
    public function detail()
    {
        $id = input('get.id');
        if(empty($id))
        {
            $this->error('ID不存在');
        }
        $citys = model('City')->getNormalCitysByParentId();
        //获取一级分类
        $categorys = model('Category')->getNormalCategorysByParentId();
        //获取商户数据（拼凑三张表的关联信息）
        $bisData = model('Bis')->get($id);
        $locationData = model('BisLocation')->get(['bis_id'=>$id,'is_main'=>1]);
        $accountData = model('BisAccount')->get(['bis_id'=>$id,'is_main'=>1]);

        return $this->fetch('',[
            'citys' => $citys,
            'categorys' => $categorys,
            'bisData' => $bisData,
            'locationData' => $locationData,
            'accountData' => $accountData,
            'id' => $id,
        ]);
    }
    // 修改状态
    public function status() {
        $data = input('get.');
        $status = $data['status'];
        $bisId = $data['id'];
        $res = model('Bis')->get($bisId);
        $email = $res->email;
        // 检验自行完成
        /*$validate = validate('Bis');
        if(!$validate->scene('status')->check($data)) {
            $this->error($validate->getError());
        }*/

        $res = $this->obj->save(['status'=>$data['status']], ['id'=>$data['id']]);
        $location = model('BisLocation')->save(['status'=>$data['status']], ['bis_id'=>$data['id'], 'is_main'=>1]);
        $account = model('BisAccount')->save(['status'=>$data['status']], ['bis_id'=>$data['id'], 'is_main'=>1]);
        if($res && $location && $account) {
            //不通过执行
            if($status == 2) {
                // 发送邮件  status 1通过  status 2不通过  status -1删掉 status 0待审
                $url = request()->domain().url('bis/register/waiting', ['id'=>$bisId]);
                $title = "o2o入驻申请通知";
                $content = "您提交的入驻申请没有通过平台方审核，您可以通过点击链接<a href='".$url."' target='_blank'>查看链接</a> 查看审核状态";
                \phpmailer\Email::send($email,$title, $content);  // 线上关闭 发送邮件服务
                $this->success('审核完毕');

            }
            if($status == 1) {
                //通过执行
                $url = request()->domain() . url('bis/register/waiting', ['id' => $bisId]);
                $title = "o2o入驻申请通知";
                $content = "恭喜您通过平台方审核，您可以通过点击链接<a href='" . $url . "' target='_blank'>查看链接</a> 查看审核状态";
                \phpmailer\Email::send($email, $title, $content);  // 线上关闭 发送邮件服务
                $this->success('审核完毕');
            }else{
                //删除执行
                $this->success('商家入驻状态更新成功');
            }


        }else {
            $this->error('商家入驻状态更新失败');
        }

    }


}
