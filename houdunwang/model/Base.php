<?php
//给Base类设置命名空间
namespace houdunwang\model;
//使用PDO类
use PDO;
//使用PDOException类
use PDOException;
//使用Exception类
use Exception;
//声明Base类，用于数据库的连接和数据的查询
class Base
{
    //声明静态属性$pdo用来验证数据库是否连接上了
	private static $pdo = null;
	//声明$table属性用来存储操作的数据表名
	private $table;
	//声明$where属性用来存储where条件
    private $where='';
    //声明$data属性用来存储查询结构的数据
    private $data;
    //声明$field属性用来存储指定的字段
    private $field='';
	//声明构造方法来连接数据库和获得操作的数据表名
	public function __construct ($class)
	{
		//连接数据库
        //如果静态属性$pdo为null
		if(is_null (self::$pdo)){
		    //那么调用静态方法connect来连接数据库
			self::connect ();
		}
		//获得要操作的数据表名
		$info = strtolower (ltrim (strrchr ($class,'\\'),'\\'));
		$this->table = $info;
	}
	 //连接数据库
	 //throws Exception	抛出异常错误
	 //声明静态方法connect连接数据库
	private static function connect ()
	{
		try {
            //设置数据源
            //数据库类型为database中下标为driver的数据,
            //主机地址为database中下标为host的数据,
            //数据库名为database中下标为dbname的数据,
			$dsn      = c('database.driver').":host=".c('database.host').";dbname=".c('database.dbname');
			//数据库用户名为database中下标为user的数据,
			$user     = c('database.user');
            //数据库用户密码为database中下标为password的数据,
			$password = c('database.password');
            //实例化PDO类并存到静态属性$pdo中
			self::$pdo      = new PDO( $dsn , $user , $password );
			//设置字符集为utf8
			self::$pdo->query ('set names utf8');
			//设置错误属性为抛出异常
			self::$pdo->setAttribute (PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		} catch ( PDOException $e ) {
            //如果有错，将错误显示到页面上
			throw new Exception($e->getMessage ());
		}
	}
     //统计数据
     //声明方法count用于统计数据
    public function count(){
	    //首先把统计数据命令的样式组合起来并以字符串的形式存入到$sql中
        $sql = "select count(*) as total from {$this->table} {$this->where}";
        //调用query方法执行$sql语句并将结果存入到$data中
        $data =  $this->query ($sql);
        //将结果以数组的形式返回出去
        return $data[0]['total'];
    }
     //获取指定字段
     //声明field方法用来获取指定的字段
    public function field ( $field )
    {
        //将指定的字段语句存入到field属性中
        $this->field = $field;
        //将结果返回出去
        return $this;
    }
     //执行数据写入
     //声明insert方法用于写入数据
    //参数$data为要写入的数据
    public function insert ( $data )
    {
        //声明$fields用来存储写入数据的下标
        $fields = '';
        //声明$values用来存储写入数据的值
        $values = '';
        //循环写入的数据
        foreach ( $data as $k => $v ) {
            //给写入数据的下标后面加逗号
            $fields .= $k . ',';
            //判断写入数据的值是数字还是字符串
            //如果写入数据的值为数字
            if ( is_int ( $v ) ) {
                //那么给数据的值的后面加一个逗号
                $values .= $v . ',';
            } else {
                //否则就说明是字符串
                //然后给数据的值先加引号再加一个逗号
                $values .= "'$v'" . ',';
            }
        }
        //然后去掉数据下标的逗号
        $fields = rtrim ( $fields , ',' );
        //再去掉数据的值的逗号
        $values = rtrim ( $values , ',' );
        //然后把sql语句组合起来并以字符串的形式存到$sql中
        $sql = "insert into {$this->table} ({$fields}) values ({$values})";
        //最后调用exec方法执行sql语句并将结果返回出去
        return $this->exec ( $sql );
    }
     //执行更新数据
    //声明update方法用于更新数据
    //参数$data为要更新的数据
    public function update ( array $data )
    {
        //判断
        //如果没有where条件为空
        if ( empty( $this->where ) )
            //返回false，不允许更新
            return false;
        //声明$fields，用来存储重组完成的结果
        $fields = '';
        //循环更新的数据
        foreach ( $data as $k => $v ) {
            //判断更新数据的值是数字还是字符串
            //如果更新数据的值为数字
            if ( is_int ( $v ) ) {
                //那么给数据的值的后面加一个逗号
                $fields .= "$k=$v" . ',';
            } else {
                //否则就说明是字符串
                //然后给数据的值先加引号再加一个逗号
                $fields .= "$k='$v'" . ',';
            }
        }
        //然后去掉数据的值的后面的逗号
        $fields = rtrim ( $fields , ',' );
        //然后把sql语句组合起来并以字符串的形式存到$sql中
        $sql = "update {$this->table} set {$fields} {$this->where}";
        //最后调用exec方法执行sql语句并将结果返回出去
        return $this->exec ( $sql );
    }
     //删除数据
    //声明destory方法用于更新数据
    //参数$pk为要删除的数据的主键
    public function destory ( $pk = '' )
    {
        //如果没有where条件或者没有要删除的数据的主键
        if ( empty( $this->where ) || empty( $pk ) ) {
            //判断如果没有where条件
            if ( empty( $this->where ) ) {
                //那么调用getPK方法获取主键
                $priKey = $this->getPK();
                //然后调用where方法把destory传入的参数作为where条件
                $this->where ( "{$priKey}={$pk}" );
            }
            //然后把sql语句组合起来并以字符串的形式存到$sql中
            $sql = "delete from {$this->table} {$this->where}";
            //最后调用exec方法执行sql语句并将结果返回出去
            return $this->exec ( $sql );
        } else {
            //否则返回false，不允许删除
            return false;
        }
    }
     //获取所有数据
    //声明getAll方法用于获取所有数据
    public function getAll ()
    {
        //将field属性的值写为‘*’（所有）并存入$field中
        $field = $this->field ? : '*';
        //然后把sql语句组合起来并以字符串的形式存到$sql中
        $sql = "select {$field} from {$this->table} {$this->where}";
        //调用query方法查询
        $data = $this->query ( $sql );
        //如果查询的数据不为空
        if ( ! empty( $data ) ) {
            //那么把查到的数据存入data属性中
            $this->data = $data;
            //然后将结果返回出去
            return $this;
        }
        //返回的形式为数组的形式
        return [];
    }
	//查询数据
    //声明查询数据的方法find
	public function find ($id)
	{
		//获取当前操作的数据表的主键到底是什么
		$pk = $this->getPk();
        //为了把sql中where条件语句存储到where属性中
        $this->where ( "$pk={$id}" );
        $field = $this->field ? : '*';
		//获取当前操作的数据表的查询条件
		$sql = "select {$field}from {$this->table} {$this->where}";
		//执行查询
		$data = $this->query ($sql);
        //如果查询的数据不为空
        if ( ! empty( $data ) ) {
            //那么把查到的数据存入data属性中
            $this->data = current ( $data );
            //然后将结果返回出去
            return $this;
        }
        //然后将结果返回出去
        return $this;
        //返回的形式为数组的形式
        return [];
	}
     //sql查询语句中where条件
    //声明where条件方法where
    public function where ( $where )
    {
        //把where条件语句存储到where属性中
        $this->where = "where {$where}";
        //然后将结果返回出去
        return $this;
    }
     //将对象转为数组
    //声明toArray方法用于将对象转为数组
    public function toArray ()
    {
        //如果data属性中有数据
        if ( $this->data ) {
            //那么将数据返回出去
            return $this->data;
        }
        //返回的形式为数组的形式
        return [];
    }
	 //获取表主键到底是什么
	 //声明getPk方法来获取表的主键
	private function getPk(){
		//查看表结构
		$sql = "desc " . $this->table;
		//将表结构存入$data中
		$data  = $this->query ($sql);
		//声明$pk用来存储表的主键的名字
		$pk = '';
		//循环$data数组来获取表的主键名
		foreach($data as $v){
		    //如果$data数组中键值为Key的值为PRI,说明这个键值就是主键
			if($v['Key'] == 'PRI'){
			    //将主键的名字存入到$pk中
				$pk = $v['Field'];
				break;
			}
		}
		//将主键名返回出去
		return $pk;
	}
	 //执行有结果集的查询
	 //声明查询方法query来查询数据
	public function query($sql){
		try{
		    //执行查询
			$res = self::$pdo->query($sql);
			//取出结果
			return $row = $res->fetchAll(PDO::FETCH_ASSOC);
		}catch (PDOException $e){
		    //实例化Exception异常错误处理类，抛出异常
			throw new Exception($e->getMessage ());
		}
	}
     //执行没有结果集的操作
    //声明操作方法exec来操作数据
    public function exec ( $sql )
    {
        try {
            //执行操作
            $res = self::$pdo->exec ( $sql );
            //判断
            //如果是添加的话，获取返回的自增主键值
            if ( $lastInsertId = self::$pdo->lastInsertId () ) {
                //返回自增主键值
                return $lastInsertId;
            }
            return $res;
        } catch ( PDOException $e ) {
            //实例化Exception异常错误处理类，抛出异常
            throw new Exception( $e->getMessage () );
        }
    }
}