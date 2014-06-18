<?php
date_default_timezone_set("Asia/Shanghai");
//DEFINE设置
define('S_DIR',dirname(__FILE__).'/../'); // 定义根目录
define('T_DIR',S_DIR.'frontEnd/template/');	//模板文件目录
define('UPLOAD_DIR',S_DIR.'upload/');	//上传根目录的绝对路径
define('SET_DIR',S_DIR.'include/setup/');	//缓存目录路径
define('MAXSIZE',320000);	//最大上传文件大小
define('IN_SUPU', TRUE); // 用于阻止非法查看引用文件

//数据库操作
$conf = array();
$dbconf = array();
include(S_DIR.'conf/config.inc.php');
include(S_DIR.'conf/dbConfig.inc.php');
include(S_DIR.'conf/dbtable.inc.php');
include(S_DIR.'conf/menu.inc.php');
include(S_DIR.'include/db_mysql.php');
include(S_DIR.'include/Cache.php');
//$dbconf = json_decode(confirm_myhello_compiled("1"), true);
$db = new DB($dbconf); //实例化数据库DB类

//引用通用类库函数库文件
include(S_DIR.'include/common.php');
include(S_DIR.'include/Page.php');
/*
 *软件信息
 */
$softname = $conf['softname'];	// 软件名称  Certification Body Information Management System
$version = $conf['version']; // 软件版本
$qiyename = $conf['provider'];	// 软件公司名称
$main_htfrom = $conf['main_htfrom'];	// 总部合同来源代码
/*
 * 注册企业名称
 */
$regqiye = $conf['org'];	// 系统标题
$regname = $conf['top'];	// 顶部大标题
$audorgid = $conf['audorgid'];	// 认证机构信息号
$certid = $conf['certid'];	// 认证机构证书默认前三位

/*
*有关数据库的配置参数
*/
$dbname = $dbconf['dbname']; // 数据库名称
$dbuser = $dbconf['dbuser']; // 用户名称
$dbpw = $dbconf['dbpw']; // 用户密码
$dbhost = $dbconf['dbhost']; // 数据库服务器或IP地址
$charset = $dbconf['charset']; // 数据库字符集
$dbpre = $dbconf['dbpre']; // 数据库前缀
$pconnect = $dbconf['pconnect']; // 持续连接

/*
 * 部分参数配置
 */
$db_perpage = $conf['db_perpage']; // 分页，每页记录条数
?>