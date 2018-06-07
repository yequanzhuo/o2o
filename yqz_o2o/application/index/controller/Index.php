<?php
namespace app\index\controller;
use think\Controller;

class Index extends Base
{
    public function index()
    {
        // 获取首页大图 相关数据
        $res = model('Featured')->getFeaturedsByType(0);
        $res = $res->toArray();;
        $head = $res['data'];
        //halt($ret);
        //获得image值得一维数组
        $headImages = array_column($head, 'image','listorder');
        //halt($images);
        $listorders = array_keys($headImages);
        //halt($listorders);
        //得到排序最大的值，将其对应的image输出
        $max = max($listorders);
        $putHead = $headImages["$max"];
        //halt($put);
        // 获取广告位相关的数据
        //1. 找到所有广告位数据  2 转为数组  3 遍历数组 4 输出
        $res1 = model('Featured')->getFeaturedsByType(1);
        $res1 = $res1->toArray();
        $right = $res1['data'];
        $rightImages = array_column($right, 'image','listorder');
        $rightListorders = array_keys($rightImages);
        //halt($rightListorders);
        //得到排序最大的值，将其对应的image输出
        $max = max($rightListorders);
        $putRight = $rightImages["$max"];
        //halt($putRight);


        // 商品分类 数据-美食 推荐的数据
        $datas = model('Deal')->getNormalDealByCategoryCityId(1, $this->city->id);
        // 获取4个子分类
        $meishicates = model('Category')->getNormalRecommendCategoryByParentId(1, 4);
        return $this->fetch('',[
            'datas' => $datas,
            'meishicates' => $meishicates,
            'putHead' => $putHead,
            'putRight' => $putRight,
        ]);


    }
}
