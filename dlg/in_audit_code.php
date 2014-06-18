<?php
include '../include/globals.php';

GrepUtil::InitGP(array('iso','code','nqa_code'));

$arriso = $arrcode = $nqa_arrcode = array();
$code = str_replace(',', '；', $code);
$code = str_replace('，', '；', $code);
$code = str_replace(';', '；', $code);
$nqa_code = str_replace(',', '；', $nqa_code);
$nqa_code = str_replace('，', '；', $nqa_code);
$nqa_code = str_replace(';', '；', $nqa_code);
$arriso = explode('|',$iso);
$arrcode = explode('|',$code);
$nqa_arrcode = explode('|',$nqa_code);
foreach ($arriso as $k=>$v)
{
	$arr = array();
	$arr = explode('；',$arrcode[$k]);
	foreach ($arr as $vl)
	{
		$rows = $db->get_one("SELECT id FROM {$dbtable['setup_audit_code']} WHERE iso='{$v}' AND shangbao='{$vl}' LIMIT 1");
		if ($rows == ''){
			echo $v.':'.$vl;
			exit;
		}
	}

	$arr = array();
	$arr = explode('；',$nqa_arrcode[$k]);
	foreach ($arr as $vl)
	{
		$rows = $db->get_one("SELECT id FROM {$dbtable['setup_audit_code']} WHERE iso='{$v}' AND code='{$vl}' LIMIT 1");
		if ($rows == ''){
			echo $v.':'.$vl;
			exit;
		}
	}
}
exit;
?>