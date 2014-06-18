<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include_once S_DIR.'/include/module/Auditor.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_htfrom.php';

$seach_arr = array(
	//每个表单控件NAME值作为KEY
	'empName'=>array(
	  'kind'=>'text',
	  'name'=>'empName',
	  'msg'=>'姓名',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'empName',
	  'sql_kind'=>'%like%'
	),
	'htfrom'=>array(
	  'kind'=>'select',   
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'104px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),
	'br'=>array(
		 'kind'=>'br'
	),
	'auditorBeginDate1'=>array(
	  'kind'=>'date1',
	  'name'=>'auditorBeginDate1',
	  'msg'=>'请假开始时间',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'auditorBeginDate',
	  'sql_kind'=>'>='
	),
   'auditorBeginDate2'=>array(
	  'kind'=>'date2',
	  'name'=>'auditorBeginDate2',
	  'msg'=>'',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'auditorBeginDate',
	  'sql_kind'=>'<='
	),
	  'auditorEndDate1'=>array(
	  'kind'=>'date1',
	  'name'=>'auditorEndDate1',
	  'msg'=>'请假结束时间',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'auditorEndDate',
	  'sql_kind'=>'>='
	),
	  'auditorEndDate2'=>array(
	  'kind'=>'date2',
	  'name'=>'auditorEndDate2',
	  'msg'=>'',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'auditorEndDate',
	  'sql_kind'=>'<='
	)
);

Power::CkPower('Z4S');//
$width='800px';

$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项

$params = array(
	'search' => $sql_temp .= " and online=9",
	'url' => $url,
);

$Auditor = new Auditor();
$result = $Auditor->listAuditor($params);

include T_DIR.'header.htm';
include T_DIR.'audit/ask_for_leave_list.htm';
include T_DIR.'footer.htm';
?>