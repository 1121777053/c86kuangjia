<?php
//给控制器类设置命名空间
namespace app\home\controller;
//使用Controller类
use houdunwang\core\Controller;
//使用View类
use houdunwang\view\View;
//使用Article类
use system\model\Article;
//声明控制器类Entry并继承Controller类
class Entry extends Controller
{
    //测试框架应用执行方法是否管用
    //声明index方法
   public function index(){
        //测试抛出异常加载的第三方类库whoop
        //include 'abc';
        //Article::query('abcd');
        //测试数据库根据主键查找数据表一条数据
        //$data = Article::find ( 2 );
        //dd($data);
        //$data = Article::find ( 1 )->toArray ();
        //dd($data);
        //测试where条件查找数据
        //$data = Article::where("aid=2")->getAll()->toArray();
        //dd($data);
        //测试查询所有数据
        //$data = Article::getAll ()->toArray ();
        //dd($data);
        //测试使用原生方式查询所有数据
        //$data = Article::query('select * from article');
        //dd($data);
        //测试删除数据
        //$res = Article::where('aid=4')->destory();
        //$res = Article::destory(5);
        //dd($res);
        //测试数据更新
        //$data = ['atitle' => '后盾'];
        //$res  = Article::where("aid=2")->update ($data);
        //dd($res);
        //测试增加数据
        //$data = ['atitle' => '后盾网'];
        //$res = Article::insert($data);
        //dd($res);
        //测试获取指定的字段
        //$data = Article::field('atitle')->getAll()->toArray();
        //$data = Article::getAll()->toArray();
        //dd($data);
        //测试统计方法
        //$count = Article::count();
        //dd($count);
        //测试数据库操作
        //$data = Article::find(3);
        //dd($data);
        //输出index
        //echo 'index';
        $test = '张邢';
       //加载欢迎页面并循环数据
       return View::with(compact ('test'))->make();
   }
   //声明add方法
    public function add(){
       //输出add
       //利用链式操作测试houdunwang/core/Controller.php中的message方法和seturl方法
        $this->seturl()->message('恭喜您，添加成功了!');
    }

}