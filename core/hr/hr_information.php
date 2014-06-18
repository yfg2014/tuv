<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');
include(SET_DIR.'setup_htfrom.php');
include(SET_DIR.'setup_province.php');
include(S_DIR.'include/topsearch.php');
include(SET_DIR.'setup_hr_education.php');
include_once S_DIR.'core/hr/hr_information_search_arr.php';

Power::CkPower('H0S');

$width='1000px';

$TopSearch = new TopSearch($seach_arr);
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//SQL
$SearchHtml	= $TopSearch->SearchHtml;		//HTML?
$sql_temp .= Power::xt_htfrom();

if($xueli != ''){
	$sql_temp .= " AND id IN(SELECT hr_id FROM {$dbtable[hr_education]} WHERE xueli='{$xueli}')";
}

$sql_temp .= " AND id!='1' ";
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$s = new hr_information();
$result = $s->listHr($params);

include T_DIR.'header.htm';
include T_DIR.'hr/hr_information.htm';
include T_DIR.'footer.htm';
?>