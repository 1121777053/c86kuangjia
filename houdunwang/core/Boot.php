<?php
//给Boot类设置命名空间
namespace houdunwang\core;
//声明框架启动类Boot
class Boot
{
     //执行应用
      public static function run(){
          //3.运行抛出异常
          self::handler();
          //测试自动加载是否管用
          //echo 1;
          //dd(1);
          //1.框架初始化
          self::chushi();
          //2.执行框架应用
          self::apprun();
      }
    //3.声明静态方法：handler用于运行抛出异常
    private static function handler(){
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }
      //1.初始化框架
    //声明静态方法：框架初始化方法
    public static  function chushi(){
          //声明头部
        //如果不声明头部，浏览器显示会乱码
        header ( 'Content-type:text/html;charset=utf8' );
        //设置时区
        //如果不设置时区，使用时间的时候可能会不正确
        date_default_timezone_set ( 'PRC' );
        //开启session
        //如果有session_id就不用重复开启session,如果没有就开启session
        session_id()||session_start();
    }
    //2.框架应用执行
    //声明静态方法：框架应用执行方法
    public static function apprun(){
        //给地址栏加S参数
        //判断，检测$_GET中的s参数是否存在
        if(isset($_GET['s'])){
            //如果存在，将s参数的值转换成数组形式
            //使用explode函数将s参数的值以‘/’为分割线分成3份并存入$info中
            $info = explode ( '/' , $_GET[ 's' ] );
            //将$info中下标为0号和1号的数据放到对应的位置中，以便各个模块和控制器类的调用
            //$class中的"\\"是转义的意思
            //将各个控制器类的命名空间的方式存入$class中
            $class  = "\app\\{$info[0]}\controller\\" . ucfirst ( $info[ 1 ] );
            //将各个方法名存到$action中
            $action = $info[ '2' ];
            //定义常量
            //将模块名、控制器名、方法名分别存入对应的常量中，以便整个框架的调用
            //声明常量MODULE,将模块名存入MODULE中，以便整个框架的调用
            define ( 'MODULE' , $info[ 0 ] );
            //声明常量CONTROLLER,将控制器名存入CONTROLLER中，以便整个框架的调用
            define ( 'CONTROLLER' , $info[ 1 ] );
            //声明常量ACTION,将方法名存入ACTION中，以便整个框架的调用
            define ( 'ACTION' , $info[ 2 ] );
        }else{
            //如果没有s参数，给地址栏一个默认值
            //地址栏默认的模块为home，控制器类为Entry
            $class  = "\app\home\controller\Entry";
            //默认的方法为index
            $action = 'index';
            //定义常量
            //将模块名、控制器名、方法名分别存入对应的常量中，以便整个框架的调用
            //声明常量MODULE,将home存入MODULE中，以便整个框架的调用
            define ( 'MODULE' , 'home' );
            //声明常量CONTROLLER,将entry存入CONTROLLER中，以便整个框架的调用
            define ( 'CONTROLLER' , 'entry' );
            //声明常量ACTION,将index存入ACTION中，以便整个框架的调用
            define ( 'ACTION' , 'index' );
        }
        //实例化调用控制器类和方法
        //使用 call_user_func_array 方法实例化调用对应的控制器类和方法
        echo call_user_func_array ( [ new $class , $action ] , [] );
    }


}