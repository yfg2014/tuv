<?php
!defined('IN_SUPU') && exit('Forbidden');
GrepUtil::InitGP(array('kind','zs_change_date','zs_zanting_edate','certEnd'));
switch($kind){
	case 'zs_zanting_edate' :
	$zs_change_date = explode('-',$zs_change_date);
	$y = $zs_change_date['0'];
	$m = $zs_change_date['1'];
	$d = $zs_change_date['2'];
	$zs_zanting_edate = date("Y-m-d",mktime(0,0,0,$m+3,$d,$y));
	echo $zs_zanting_edate;exit;
	case 'shijiancha' : 
	$cha = strtotime($zs_zanting_edate) - strtotime($zs_change_date);
	if($cha > 6*30*24*3600){echo '01';exit;}
	if(strtotime($zs_zanting_edate) > strtotime($certEnd)){echo '02';exit;}
	break;
}
exit;
?>