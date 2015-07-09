<?php
/**
 * 字符过滤工具类
 */
class GrepUtil{
	/**
	 * 取全局变量
	 * @param mixed $keys
	 * @param mixed $method
	 * @param int $cvtype
	 */
	static function InitGP($keys,$method=null,$cvtype=1,$char_cv=1){//0=null,1=Char_cv,2=int
		!is_array($keys) && $keys = array($keys);
		foreach ($keys as $key) {
			$GLOBALS[$key] = NULL;
			if ($method != 'P' && isset($_GET[$key])) {
				$GLOBALS[$key] = $_GET[$key];
			} elseif ($method != 'G' && isset($_POST[$key])) {
				$GLOBALS[$key] = $_POST[$key];
			}
			if ($char_cv && isset($GLOBALS[$key]) && !empty($cvtype)) {
				$GLOBALS[$key] = self::Char_cv($GLOBALS[$key],$cvtype==2,true);
			}
			$rst [$key]= $GLOBALS[$key];
		}
		return $rst;
	}

	//===================         ==================
	/**
	 * 字符过滤
	 * @param mixed $mixed
	 * @param boolean $isint
	 * @param boolean $istrim
	 * @return mixed $mixed
	 */
	static function Char_cv($mixed,$isint=false,$istrim=false) {
		if (is_array($mixed)) {
			foreach ($mixed as $key => $value) {
				$mixed[$key] = self::Char_cv($value,$isint,$istrim);
			}
		} elseif ($isint) {
			$mixed = (int)$mixed;
		} elseif (!is_numeric($mixed) && ($istrim ? $mixed = trim($mixed) : $mixed) && $mixed) {
			$mixed = str_replace(array("\0","%00","\r"),'',$mixed);
			$mixed = preg_replace(
				array('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/','/&(?!(#[0-9]+|[a-z]+);)/is'),
				array('','&amp;'),
				$mixed
			);
			$mixed = str_replace(array("%3C",'<'),'&lt;',$mixed);
			$mixed = str_replace(array("%3E",'>'),'&gt;',$mixed);
			$mixed = str_replace(array('"',"'","\t",'  '),array('&quot;','&#39;','    ','&nbsp;&nbsp;'),$mixed);
		}
		return $mixed;
	}
	/**
	 * 针对SQL语句的变量进行反斜线过滤,并两边添加单引号
	 *
	 * @param mixed $var 过滤前变量
	 * @param boolean $strip 数据是否经过stripslashes处理
	 * @param boolean $is_array 变量是否为数组
	 * @return mixed 过滤后变量
	 */
	static function pwEscape($var,$strip = true,$is_array=false) {
		if (is_array($var)) {
			if (!$is_array) return " '' ";
			foreach ($var as $key => $value) {
				$var[$key] = trim(self::pwEscape($value,$strip));
			}
			return $var;
		} elseif (is_numeric($var)) {
			return " '".$var."' ";
		} else {
			return " '".addslashes($strip ? stripslashes($var) : $var)."' ";
		}
	}
	/**
	 * 构造单记录数据更新SQL语句
	 *  格式: field='value',field='value'
	 *
	 * @param array $array 更新的数据,格式: array(field1=>'value1',field2=>'value2',field3=>'value3')
	 * @param boolean $strip 数据是否经过stripslashes处理
	 * @return string SQL语句
	 */
	static function pwSqlSingle($array,$strip=true) {
		$array = self::pwEscape($array,$strip,true);
		$str = '';
		foreach ($array as $key => $val) {
			$str .= ($str ? ', ' : ' ').$key.'='.$val;
		}
		return $str;
	}
	/**
	 * 自动过滤POST,GET数组
	 * @param string $paraType
	 */
	/*static function autoGrep($paraType){
		self::InitGP($paraType);
		global ${$paraType};
		$grepType = ${$paraType};
		if ($grepType == 1){
			foreach ($_GET as $k => $v) {
				self::InitGP($k,NULL,1,0);
			}
			foreach ($_POST as $k => $v) {
				self::InitGP($k,NULL,1,0);
			}
		}elseif ($grepType == 0){
			foreach ($_GET as $k => $v) {
				self::InitGP($k);
			}
			foreach ($_POST as $k => $v) {
				self::InitGP($k);
			}
		}
	}*/

	static function InitGP_json($json,$cvtype=1){
		if (get_magic_quotes_gpc()){
			$json = stripslashes($json);
		}
		$json_arr = json_decode($json,1);
		foreach ($json_arr as $k => $v){
			$json_arr[$k] = self::Char_cv($v,$cvtype==2);
		}
		return $json_arr;
	}
}

class Error {
	//显示异常错误信息
	static function ShowError($error,$url = '') {
		$url == '' ? $url = 'history.back(-1)' : $url = 'window.location.href="'.$url.'"';
		if(!empty($error)){
			if(is_array($error)){
				foreach($error as $k=>$v){
					$i=$k+1;
					$error_msg []= $i.'、'.$v;
				}
				$error_msg = implode('\n',$error_msg);
			}else{
				$error_msg = $error;
			}
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo '<script type="text/javascript">alert("'.$error_msg.'");'.$url.'</script>';
			exit;
		}
	}
}

class Url {
	/**
	 * url跳转
	 *
	 * @param string $url 要跳转的url
	 * @return
	 */
	static function goto_url($url, $msg='') {
		if (!empty($msg)) {
			if(is_array($msg)){
				foreach($msg as $k=>$v){
					$i=$k+1;
					$error_msg []= $i.'、'.$v;
				}
				$error_msg = implode('\n',$error_msg);
			}else{
				$error_msg = $msg;
			}
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo '<script type="text/javascript">alert("'.$error_msg.'")</script>';
		}
		if($url != ''){
			echo '<script type="text/javascript">window.location.href="'.$url.'"</script>';
		}else{
			echo '<script type="text/javascript">window.history.go(-1);</script>';
		}
		exit;
	}
}
/**
 * 数据库工具类
 * @author Tom
 */
class DBUtil{
	private static $db = null;
	/**
	* @param string $table 要插入的目的表名
	* @param string $value 要插入的值
	* @return boolean 成功返回TRUE失败返回FALSE
	*/
	static function Insert_tb($db,$table,$value){
		if(!is_array(current($value))){
			return $db->update("INSERT INTO ".$table." SET ".GrepUtil::pwSqlSingle($value));
		}
		else{
			$fields = implode(',',array_keys($value['0']));
			foreach($value as $v){
				array_walk($v, 'DBUtil::callback');
				$v_t []= '(\''.implode('\',\'',$v).'\')';
			}
			$v_e = implode(',',$v_t);
			return $db->insert("INSERT INTO ".$table."(".$fields.") VALUES ".$v_e);
		}
	}
	/**
	 *
	 * 私有的回调方法，用于处理特殊字符
	 * @param mixed_type $item 数组值
	 * @param mixed $k 数组键值
	 */
	private static function callback(&$item,$k){
		$item = addslashes($item);
	}
	/**
	 *
	 * 更新数据
	 * @param object $db
	 * @param string $table
	 * @param array $value
	 * @param string $where
	 * @return boolean
	 */
	static function Update_tb($db,$table,$value,$where){
		//取修改记录
		$cg_action = str_replace('=','|',str_replace("'",'',$where));
		$new_cg = array();
		$edit_fields = array_keys($value);
		$edit_fields = implode(',',$edit_fields);
		$old_value_arr = $db->get_one("SELECT $edit_fields FROM $table WHERE $where");
		empty($old_value_arr) && $old_value_arr = array();
		foreach($old_value_arr as $k => $v){
			//if($v != $value[$k]){
			if(strcmp($v,$value[$k]) != 0){
				$cg_msg['old'] = $v;
				$cg_msg['new'] = $value[$k];
				$cg_msg['cg_field'] = $k;
				$cg_msg['cg_table'] = $table;
				$cg_msg['cg_action'] = $cg_action;
				$cg_msg['op_ren'] = $_SESSION['username'];
				$cg_msg['op_time'] = date("Ymdhis");
				$cg_msg['online'] = '0';
				$cg[] = $cg_msg;
				$new_cg[$k] = $value[$k];
			}
		}
		if(!empty($cg)){
			$cg = GrepUtil::Char_cv($cg);
			DBUtil::Insert_tb($db,'sys_change_log',$cg);
			return $db->update("UPDATE ".$table." SET ".GrepUtil::pwSqlSingle($new_cg)." WHERE ".$where);
		}
		return true;
	}

	/**
	 * 连接数据库
	 * @return object $db
	 */
	/*static function open($params = array()){
		if (self::$db == null){
			self::$db = new DB($params);
		}
		return self::$db;
	}*/
	//关闭数据库连接
	/*static function Close($db) {
		$result = @mysql_close($db->sql);
		self::$db = null;
		return $result;
	}*/

	//删除数据
	static function Del($db,$table,$where){
		if($where){
			if(is_array($table)){
				foreach($table as $v){
					$db->query("DELETE FROM $v WHERE $where");
				}
			}else{
				$db->query("DELETE FROM $table WHERE $where");
			}
			return true;
		}else{
			return false;
		}
	}
	//获取单条数据
	static function GetOne($db,$table,$fields,$where){
		empty($fields) ? $fields = '*' : $fields = implode(',',$fields);
		$sql = "SELECT $fields FROM $table WHERE 1 AND $where";
		$rst = $db->get_one($sql);
		return $rst;
	}

	//获取数组数据
	static function GetArr($db,$table,$fields=array(),$where){
		empty($fields) ? $fields = '*' : $fields = implode(',',$fields);
		$where!='' && $where = ' AND '.$where;
		$sql = "SELECT $fields FROM $table WHERE 1 $where";
		$query = $db->query($sql);
		while($rstdb = $db->fetch_array($query)){
			$rst []= $rstdb;
		}
		return $rst;
	}

}


class LogRW {
	/**
	 *
	 * 插入日志,显示日记
	 * @2011-5-9 修改
	 *
	 *  插入数据例子：	LogRW::logWriter('登入成功');
	 *			或者：	$s = new LogRW();
	 *					$s->logWriter('登入成功');
	 */

	static function logWriter($zuzhi_id,$msg,$hr=array()){
		global $db,$dbtable;
		$table = $dbtable['sys_log'];
		if(empty($hr)){
		$value = array(
			'zuzhi_id'	=> $zuzhi_id,
			'username'	=> $_SESSION['user'].":".$_SESSION['username'],
			'ip'		=> $_SERVER['REMOTE_ADDR'],
			'logtime'	=> date("YmdHis"),
			'info'		=> $msg
			);
		}else{
			$value = array(
			'username'	=> $hr['user'].":".$hr['username'],
			'ip'		=> $_SERVER['REMOTE_ADDR'],
			'logtime'	=> date("YmdHis"),
			'info'		=> $msg
			);
		}
		DBUtil::insert_tb($db,$table,$value);
	}

	static function logShow($params){
		global $dbtable;
		$table	= $dbtable['sys_log'];
		$search = $params['search'];
		$url	= $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum FROM $table WHERE 1 $search";
		$sql['data'] = "SELECT * FROM $table WHERE 1 $search  ORDER BY id DESC";
		$page = new Page($url, $sql);
		$result['data'] = $page->getPageData();
		$result['pages'] = $page->nav;
		$result['count'] = $page->count;
		return $result;
	}

}


/**
 *
 * 权限检查
 * @author Tom
 *
 */
class Power {
	/**
	 * 检测是否登录
	 * @param string $url
	 * @param string $msg
	 * @return boolean
	 */
	static function ckLogin($url='',$msg='') {
		if($_SESSION['username'] == '') {
			unset($_SESSION['power']);
			unset($_SESSION['userid']);
			unset($_SESSION['username']);
			unset($_SESSION['user']);
			unset($_SESSION['htfrom']);
			$url && Url::goto_url($url,$msg);
			return false;
		}else{
			return true;
		}
	}
	//控制分部合同来源权限
	static function xt_htfrom($ck=0,$md=1,$prefix='')
	{
		//$ck 默认 0 为list 清单显示关联企业，非 0 为 check 单个页面访问权限
		//默认取企业编号，当$md!=1时取企业ID
		global $main_htfrom;
		$xt_htfrom = $_SESSION['htfrom'];
		if($ck == '0')
		{
			if($xt_htfrom!=$main_htfrom)
			{
				if($md == 'hr')
				{
					$temp=$temp." and $prefix"."htfrom = '".$xt_htfrom."'" ;
				}
				else
				{
					$temp=$temp." and $prefix"."htfrom = '".$xt_htfrom."'" ;
				}
			}
			return $temp;
		}
		else
		{
			global $db,$dbtable;
			if($md == '1')
			{
				$cksql = $db->get_one("SELECT htfrom FROM $dbtable[mk_company] WHERE id='$ck'");
				if($xt_htfrom != $main_htfrom && $cksql['htfrom'] != $xt_htfrom){
					Url::goto_url('','权限不足，此企业合同来源与你账号不匹配');exit;
				}
			}
			elseif($md == 'hr')
			{
				$cksql = $db->get_one("SELECT htfrom FROM $dbtable[hr_information] WHERE id='$ck'");
				if($xt_htfrom != $main_htfrom && $cksql['htfrom'] != $xt_htfrom){
					Url::goto_url('','权限不足，此人员的所属分支与你账号不匹配');exit;
				}
			}
			else
			{
				$cksql = $db->get_one("SELECT htfrom FROM $dbtable[mk_company] WHERE id='$ck'");
				if($xt_htfrom != $main_htfrom && $cksql['htfrom'] != $xt_htfrom){
					Url::goto_url('','权限不足，此企业合同来源与你账号不匹配');exit;
				}
			}
		}
	}
	//权限控制
	static function CkPower($key) {
		if($_SESSION['power'] == '') {
			unset($_SESSION['power']);
			unset($_SESSION['userid']);
			unset($_SESSION['username']);
			unset($_SESSION['user']);
			unset($_SESSION['htfrom']);
			Url::goto_url("login.php");
			return false;
		}else{
			if(strpos($_SESSION['power'],$key)!==false){
				return true;
			}else{
				global $menu_setup;
				foreach($menu_setup as $m){
					$t = array_keys($m);
					if(in_array($key,$t)){
						$e_msg = $m[$key];
						break;
					}
				}
				Url::goto_url('',$e_msg.' 权限不足');exit;
			}

		}
	}

	static function CpPower($content = 0){
		$sql_temp = '';
		if(strpos($_SESSION['power'],'Z6S')!==false){
			if($content == 0){
				$sql_temp = " AND recommended_man='$_SESSION[username]'";
			}else{
				$sql_temp = " AND zuzhi_id IN(SELECT id FROM mk_company WHERE  recommended_man='$_SESSION[username]')";
			}
		}

		return $sql_temp;
	}
}
/**
 * 文件操作类
 * @author Tom
 *
 */
class File {
	public static function writeFile($filename,$data,$method='rb+',$iflock=1,$check=1,$chmod=1) {
		touch($filename);
		$handle = fopen($filename,$method);
		$iflock && flock($handle,LOCK_EX);
		fwrite($handle,$data);
		$method=='rb+' && ftruncate($handle,strlen($data));
		fclose($handle);
		$chmod && @chmod($filename,0777);
	}
	/**
	 * 读取文件内容
	 * @param string $filename
	 * @return string $fileContent
	 */
	public static function readFile($filename){
		$handle=fopen($filename,"r");
		$fileContent=fread($handle,fileSize($filename));
		fclose($handle);
		return $fileContent;
	}
	//过滤非法文件名字符
	public static function CleanFileName($filename){
		$filename = preg_replace ("/[\/\\ <> .\*\?\:\|]/","",$filename);
		$filename = substr($filename,0,40);
		return $filename;
	}
}
//用户登入
class UserLogin {
	public $db;

	public function __construct() {
		global $db;
		$this->db = $db;
	}
	private function ckLogin($usr,$pwd,$cert,$cert_open){
		$db = $this->db;
		$cert_open == '1' ? $temp_sql = " AND cert='$cert'" : $temp_sql = '';

		$sql = "SELECT id,power,user,username,htfrom
					FROM hr_information
						WHERE 1 AND user='$usr' AND password='".md5($pwd)."' AND (user = 'admin' OR (online = '1' AND working='1')) $temp_sql" ;
		

                $hr = $db->get_one($sql);
		if($hr){
			return $hr;
		}else{
			return null;
		}
	}
	private function sethrcookie($hr) {
		if($hr){
			$power = $hr['power'];
			$userid = $hr['id'];
			$username = $hr['username'];
			$user = $hr['user'];
			$htfrom = $hr['htfrom'];
		}else{
			$power = '';
			$userid = '';
			$username = '';
			$user = '';
			$htfrom = '';
		}
		//setcookie ("power", $power, 0,"/","");
		//setcookie ("userid", $userid, 0,"/","");
		//setcookie ("username", $username, 0,"/","");
		//setcookie ("user", $user, 0,"/","");
		$_SESSION['power'] = $power;
		$_SESSION['userid'] = $userid;
		$_SESSION['username'] = $username;
		$_SESSION['user'] = $user;
		$_SESSION['htfrom'] = $htfrom;
	}
	function login($usr,$pwd,$cert,$cert_open) {
		if(($hr = $this->ckLogin($usr,$pwd,$cert,$cert_open)) != null){
			$this->sethrcookie($hr);
			LogRW::logWriter('','登入成功',$hr);
			return true;
		}else {
			return false;
		}

	}
	function logout() {
		//$this->sethrcookie(null);
		unset($_SESSION['power']);
		unset($_SESSION['userid']);
		unset($_SESSION['username']);
		unset($_SESSION['user']);
		unset($_SESSION['htfrom']);
		return null;
	}
}

// 个人资料导航信息
class Personal{
	static function Navigation($hr_id){
		if (!empty($hr_id)){
			$url .= "<a href='index.php?m=hr&do=hr_information_edit&id=".$hr_id."'>人员基本信息</a>
					 <a href='index.php?m=hr&do=hr_reg_qualification&hr_id=".$hr_id."'>注册资格信息</a>
					 <a href='index.php?m=hr&do=hr_audit_code&hr_id=".$hr_id."'>专业能力信息</a>
					 <a href='index.php?m=hr&do=hr_photos_edit&hr_id=".$hr_id."'>照片</a>
					 <a href='index.php?m=hr&do=hr_resume&hr_id=".$hr_id."'>简历</a>
					 <a href='index.php?m=hr&do=hr_education&hr_id=".$hr_id."'>学历</a>
					 <a href='index.php?m=hr&do=hr_profession&hr_id=".$hr_id."'>职称</a>
					 <a href='index.php?m=hr&do=ziliao_edit&hr_id=".$hr_id."'>资料</a>
					 <a href='index.php?m=hr&do=hr_training_list2&hr_id=".$hr_id."'>培训</a>";
			return $url;
		}
	}
}
?>