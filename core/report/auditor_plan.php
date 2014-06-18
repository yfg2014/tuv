<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/topsearch.php');
include SET_DIR.'setup_province.php';

$width = '1000px';
$seach_arr = array(
	'ydate'=>array(
	  'kind'=>'select',
	  'name'=>'ydate',
	  'msg'=>'年',
	  'width'=>'70px',
	  'arr'=>array('2008'=>'2008','2009'=>'2009','2010'=>'2010','2011'=>'2011','2012'=>'2012','2013'=>'2013','2014'=>'2014','2015'=>'2015'),
	  'sql_field'=>'',
	  'sql_kind'=>''
	),
	'mdate'=>array(
	  'kind'=>'select',
	  'name'=>'mdate',
	  'msg'=>'月',
	  'width'=>'70px',
	  'arr'=>array('01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12'),
	  'sql_field'=>'',
	  'sql_kind'=>''
	),
	'username'=>array(
	  'kind'=>'text',
	  'name'=>'username',
	  'msg'=>'人员姓名',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'',
	  'sql_kind'=>''
	),
	'yjm'=>array(
	  'kind'=>'text',
	  'name'=>'yjm',
	  'msg'=>'人员易记码',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'yjm',
	  'sql_kind'=>'='
	),
    'working'=>array(
	  'kind'=>'select',
	  'name'=>'working',
	  'msg'=>'在聘情况',
	  'width'=>'70px',
	  'arr'=>array('1'=>'在职','0'=>'离职'),
	  'sql_field'=>'working',
	  'sql_kind'=>'='
	),
	'address'=>array(
	  'kind'=>'select',
	  'name'=>'address',
	  'msg'=>'地区',
	  'width'=>'70px',
	  'arr'=>$setup_province,
	  'sql_field'=>'',
	  'sql_kind'=>''
	)
);
$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项


if($ydate==""){$ydate=date("Y");}
if($mdate==""){$mdate=date("m");}

$tianshu=date("t",mktime(0,0,0,$mdate,01,$ydate));//获得当月天数

$s_date=$ydate."-".$mdate."-01";
$e_date=$ydate."-".$mdate."-".$tianshu;

if($username != ''){
	$username = str_replace(',',"','",$username);
	$username = str_replace('，',"','",$username);
	$username = str_replace('；',"','",$username);
	$username = str_replace(';',"','",$username);
	$username = str_replace('.',"','",$username);
	$username = str_replace('、',"','",$username);
	$username = str_replace('/',"','",$username);
	$sql_temp .= " AND username IN('$username')";
}

if ($address != "") {
	foreach ($setup_province as $v){
		if ($v['code'] == $address){
			$address = $v['msg'];
		}
	}
	$sql_temp = $sql_temp."  AND address LIKE '%".$address."%'" ;
}
if($working == ''){
	$sql_temp = $sql_temp." and working='1'";
}
$sql['count'] = "SELECT COUNT(*) AS sum FROM $dbtable[hr_information] WHERE worktype!='08' $sql_temp";
$sql['data'] = "SELECT username,id,working FROM $dbtable[hr_information] WHERE worktype!='08' $sql_temp ORDER BY id DESC";
$page = new Page($url, $sql);
$list = $page->getPageData();

$result['count'] = $page->count;
$result['pages'] = $page->nav;
$result['data'] = $list;

include T_DIR.'header.htm';
include T_DIR.'report/auditor_plan.htm';
include T_DIR.'footer.htm';
?>