<?php
!defined('IN_SUPU') && exit('Forbidden');
GrepUtil::InitGP(array('kind','certStart'));
switch($kind){
	case '1' :
	$certStart = explode('-',$certStart);
	$y = $certStart['0'];
	$m = $certStart['1'];
	$d = $certStart['2'];
	$certEnd = date("Y-m-d",mktime(0,0,0,$m,$d-1,$y+3));
	echo $certEnd;exit;

	case '2' :
	$certStart = explode('-',$certStart);
	$y = $certStart['0'];
	$m = $certStart['1'];
	$d = $certStart['2'];
	$certEnd = date("Y-m-d",mktime(0,0,0,$m,$d-1,$y+4));
	echo $certEnd;exit;
	break;
}
exit;
?>