<?php
include '../include/globals.php';
include_once '../include/setup/setup_industry.php';

GrepUtil::InitGP(array('code','msg'));

if ($code!="")	{
	foreach($setup_industry as $k=>$v){
		if($code != $k){
			unset($setup_industry[$k]);
		}
	}
}
if ($msg!="")	{
	foreach($setup_industry as $k=>$v){
		if(!strstr($v,$msg)){
			unset($setup_industry[$k]);
		}
	}
}

include 'template/header.htm';
include 'template/in_industry_list.htm';
include 'template/footer.htm';
?>
