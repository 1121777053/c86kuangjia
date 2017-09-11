<?php
/**
 * 助手函数
 */
//头部
header ('Content-type:text/html;charset=utf8');
//设置时区
date_default_timezone_set('PRC');

/**
 * 定义常量判断是否为post请求
 */
define ('IS_POST',$_SERVER['REQUEST_METHOD']=='POST'?true:false);

if(!function_exists ('dd')){
	/**
	 * 打印函数
	 */
	function dd($var){
		echo '<pre style="background: #ccc;padding: 8px;border-radius: 5px">';
		//print_r打印函数，不显示数据类型
		//print_r不能打印null，boolen
		if(is_null ($var)){
			var_dump ($var);
		}elseif(is_bool ($var)){
			var_dump ($var);
		}else{
			print_r ($var) ;
		}
		echo '</pre>';
	}
}
if(!function_exists ('getRunTime')){
	/**
	 * 计算脚本运行时间
	 * @param $pos	开始/结束标记
	 */
	function getRunTime($pos){
		//声明静态变量
		//存开始时间，为了结束调用的时候还可以使用$time
		static $time = 0;
		//脚本运行开始，start开始的标识
		if($pos=='start'){
			//将当前时间存到静态变量
			//存起来给结束时候调用
			$time = microtime (true);
		}
		//end脚本运行结束标识
		if($pos=='end'){
			//microtime (true)结束时间
			//$time开始时间
			//时间差即为脚本运行时间差
			return microtime (true) - $time;
		}
	}
}

/**
 * 上传函数
 */
function  up($dir='upload'){
	//1.重组数组
	$arr = resetArr();
	//dd($arr);die;
	//2.移动上传
	return move($arr,$dir);
}
 //移动上传
function move($arr,$dir){
	$path = [];//用来存储最终路径
	foreach($arr as $k=>$v){
		if(is_uploaded_file ($v['tmp_name'])){
			//上传目录
			$uploadDir = $dir ."/" . date('Y/m/d');
			is_dir ($uploadDir) || mkdir ($uploadDir,0777,true);
			//上传文件名
			$type = strrchr ($v['name'],'.');
			$fileName = time () . mt_rand (1,9999) . $type;
			//组合完成路径
			$dest = $uploadDir . '/' . $fileName;
			$path[] = $dest;
			//移动上传
			move_uploaded_file ($v['tmp_name'],$dest);
		}
	}
	//上传所有文件，最终路径
	return $path;
}
/**
 * 重组数组
 */
function resetArr(){
	$file = current ($_FILES);
	//dd($file);die;
	//2.数组重组，优化数据结构
	$arr = [];
	//判断是为了技能处理单文件上传又可以处理多文件上传
	if(is_array ($file['name'])){//多文件  up[]
		foreach($file['name'] as $k=>$v){
			$arr[] = [
				'name'=>$v,
				'type'=>$file['type'][$k],
				'tmp_name'=>$file['tmp_name'][$k],
				'error'=>$file['error'][$k],
				'size'=>$file['size'][$k],
			];
		}
	}else{
		//单文件上传  name=up
		//dd($file);
		$arr[] = $file;
		//dd($arr);die;
	}

	return $arr;
}

if(!function_exists ('dataToFile')){
	/**
	 * 将数据写入文件
	 * @param $file   要写入的文件
	 * @param $data	  写入的数据
	 */
	function dataToFile($file,$data){
		file_put_contents ($file,"<?php\r\nreturn " . var_export ($data,true) . ";");
	}
}

if(!function_exists ('error')){
	/**
	 * 成功提示信息
	 * @param $msg	 提示消息
	 */
	function error($msg){
		echo "<script>alert('$msg');history.back();</script>";
		exit;

	}
}

//自动加载，实例化未找见的类触发，自动把类名传入
function __autoload($name){
	//dd($name);
    if(substr ($name,-10)=='Controller'){
        //说明是控制器
        include "./controller/{$name}.class.php";
    }else{
        include "./tools/{$name}.class.php";
    }
}
if(!function_exists ('c')){
    /**
     * 读取配置项的c函数
     * @param $var
     *
     * @return null
     */
    function c($var){
        //dd($var);//database.host
        $info = explode ('.',$var);
        //dd($info);
        $data = include "../system/config/".$info[0].".php";
        //dd($data);
        return isset($data[$info[1]]) ? $data[$info[1]] : null;
    }
}

