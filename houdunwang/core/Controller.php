<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/8
 * Time: 14:56
 */
//给Controller类设置命名空间
namespace houdunwang\core;
//声明Controller类，便于以后程序的提示消息和跳转地址
class Controller
{
    //定义跳转属性$url,默认跳转到上一页
    private $url='window.history.back()';
    //提示消息,用于以后框架系统显示所有的提示消息
    //声明message提示方法
    public function message($message){
        //加载public/view/message.php消息提示的模板文件
        include './view/message.php';
        //结束代码的运行
        exit;
    }
    //跳转地址，用于以后框架系统的所有跳转
    //声明跳转方法seturl并传一个参数$url为空
    public function seturl($url=''){
        //判断
        //如果没有传参数进来
        if(empty($url)){
            //那么跳转地址为跳转到上一页
            $this->url="window.history.back()";
            //否则，就说明有设定的跳转地址从外面传了进来
        }else{
            //然后跳转到从外部设定的跳转地址
            $this->url="location.href='$url";
        }
        //将结果返回出去
        return $this;
    }
}