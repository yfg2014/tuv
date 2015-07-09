<?php
!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/Hr_information.php');
include (S_DIR.'include/module/Review.php');
include (S_DIR.'include/topsearch.php');
include(SET_DIR.'setup_hr_review.php');

Power::CkPower('H8S');
$seach_arr=array(
	'username'=>array(
		'kind'=>'username',
		'name'=>'username',
		'msg'=>'姓名',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'hr_id ',
		'sql_kind'=>'in'
	  ),
	 'license_type'=>array(
	  'kind'=>'select',
	  'name'=>'license_type',
	  'msg'=>'授权类型',
	  'width'=>'80px',
	  'arr'=>array('1' =>'认证决定','2' =>'合同评审'),
	  'sql_field'=>'license_type',
	  'sql_kind'=>'='
	),
);

$TopSearch = new TopSearch($seach_arr);

$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;
$SearchHtml	= $TopSearch->SearchHtml;
$sql_temp	.= Power::xt_htfrom();

$width='700px';
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$s = new Review();
$result = $s->listElement($params);

include TEMP.'header.htm';
include TEMP.'hr/hr_review_list.htm';
include TEMP.'footer.htm';
?>