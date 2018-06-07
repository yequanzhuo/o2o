<?php
namespace app\common\validate;
use think\Validate;
class User extends Validate
{

    protected $rule = [
        'username' => 'require|max:10',
        'email' => 'email',

    ];
    //场景设置
    protected $scene = [
        'add' => ['username','email'],//添加
        'listorder' => ['id','listorder'],//排序
        'status' => ['id','status'],
    ];



}
