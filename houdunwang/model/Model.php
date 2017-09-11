<?php
//给Model类设置命名空间
namespace houdunwang\model;
//声明Model类
class Model
{
    //声明魔术方法__call，当调用不存在的方法的时候触发
    //$name为未定义的方法名
    //$arguments为未定义的方法所带的参数
	public function __call ( $name , $arguments )
	{
        //如果调用的方法不存在，那么调用静态方法parseAction并将结果返回出去
		return self::parseAction ( $name , $arguments );
	}
    //声明静态魔术方法__callStatic，当调用不存在的静态方法的时候触发
    //$name为未定义的静态方法名
    //$arguments为未定义的静态方法所带的参数
	public static function __callStatic ( $name , $arguments )
	{
        //如果调用的静态方法不存在，那么调用静态方法parseAction并将结果返回出去
		return self::parseAction ( $name , $arguments );
	}
    //声明静态方法parseAction
    //$name为parseAction方法名
    //$arguments为parseAction方法所带的参数
	public static function parseAction ( $name , $argument )
	{
		//使用get_called_class函数返回当前调用的类名，并存入$class中
		$class = get_called_class ();
		//实例化houdunwang/model中的Base类并调用其中的某个方法，然后将结果返回出去
		return call_user_func_array ( [ new Base($class) , $name ] , $argument );
	}
}