<?php
namespace app\common\validate;
use think\Validate;
class Bis extends Validate
{

    protected $rule = [
        'name' => 'require|max:25',
        'email' => 'email',
        'logo' => 'require',
        'city_id' => 'require',
        'bank_info' => 'require',
        'bank_user' => 'require',
        'bank_name' => 'require',
        'faren' => 'require',
        'faren_tel' => 'require',
    ];
    //场景设置
    protected $scene = [
        'add' => ['name','email','logo','city_id','bank_info','bank_user','bank_name','faren','faren_tel'],//添加
        'listorder' => ['id','listorder'],//排序
        'status' => ['id','status'],
    ];



}
