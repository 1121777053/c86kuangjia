<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/8
 * Time: 16:11
 */
//给Base类设置命名空间
namespace houdunwang\view;
//声明Base类，用于页面的数据循环展示以及模板的加载
class Base
{

    protected $data=[];
    //声明$file属性用来存储模板文件的路径
    protected $file;
    //分配变量
    //声明with方法用于分配变量
    public function with($var){
        //调用data属性将需要展示的数据存进去
        $this->data=$var;
        //将存进去后的结果返回出去
        return $this;
    }
    //获得模板路径
    //声明make方法用于获得模板文件的路径
    public function make(){
        //调用file属性将需要展示的模板路径存进去
        $this->file =  "../app/".MODULE."/view/".strtolower (CONTROLLER)."/".ACTION."." . c('view.suffix');
        //将存进去后的结果返回出去
        return $this;
    }
    //显示模板以及数据展示
    //调用魔术方法__toString将数据以对象形式的字符串返回出去并加载模板文件
    //声明魔术方法__toString
    public function __toString()
    {
        //调用extract函数将data数组中的键名作为变量名，键值作为变量的值
        extract($this->data);
        //加载模板文件
        include $this->file;
        //将数据以对象形式的字符串返回出去
        return'';
    }
}