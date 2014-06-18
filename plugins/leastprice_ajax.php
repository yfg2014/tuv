<?php
!defined('IN_SUPU') && exit('Forbidden');
GrepUtil::InitGP(array('grade','iso','htxm_id','isonum'));

//最低限价计算
$leastprice = array();

if($isonum=='')
{
	$row = $db->get_one("SELECT id,iso,audit_type,iso_people_num FROM ht_contract_item WHERE id = '$htxm_id'");
	$leastprice['z'] = leastprice($row['iso'],$row['iso_people_num'],$grade,'再认证');
	$leastprice['j'] = leastprice($row['iso'],$row['iso_people_num'],$grade,'监督');
	$leastprice['c'] = leastprice($row['iso'],$row['iso_people_num'],$grade,'认证');
}
if($isonum>=0)
{
	$leastprice['z'] = leastprice($iso,$isonum,$grade,'再认证');
	$leastprice['j'] = leastprice($iso,$isonum,$grade,'监督');
	$leastprice['c'] = leastprice($iso,$isonum,$grade,'认证');
}

//最低限价计算
function leastprice($iso,$renshu,$grade=0,$audit_type) {
	$iso == 'QJ' && $iso = 'Q';
	$iso == 'QT' && $iso = 'Q';
	if ($iso == 'Q'  && $grade == 0) {
		if ($renshu <= 65) {
			$leastprice = 12000;
		} else if ($renshu >=66 && $renshu <= 125) {
			$leastprice = 18000;
		} else if ($renshu >=126 && $renshu <= 275) {
			$leastprice = 21000;
		} else if ($renshu >=276 && $renshu <= 625) {
			$leastprice = 28000;
		} else if ($renshu >=626 && $renshu <= 1175) {
			$leastprice = 32000;
		} else if ($renshu >=1176 && $renshu <= 1550) {
			$leastprice = 36000;
		} else if ($renshu >=1551 && $renshu <= 2000) {
			$leastprice = 40000;
		} else if ($renshu >= 2001) {
			$leastprice = 40000;
		}
	} else if (($iso == 'E' || $iso == 'S' || $iso == 'H' || $iso == 'F') && $grade != 0) {
		if ($renshu <= 30) {
			$leastprice = $grade == 1 ? 16000 : ($grade == 2 ? 14000 : 12000);
		} else if ($renshu >=31 && $renshu <= 100) {
			$leastprice = $grade == 1 ? 25000 : ($grade == 2 ? 23000 : 21000);
		} else if ($renshu >=101 && $renshu <= 275) {
			$leastprice = $grade == 1 ? 30000 : ($grade == 2 ? 28000 : 26000);
		} else if ($renshu >=276 && $renshu <= 500) {
			$leastprice = $grade == 1 ? 38000 : ($grade == 2 ? 36000 : 34000);
		} else if ($renshu >=501 && $renshu <= 875) {
			$leastprice = $grade == 1 ? 46000 : ($grade == 2 ? 43000 : 40000);
		} else if ($renshu >=876 && $renshu <= 1550) {
			$leastprice = $grade == 1 ? 54000 : ($grade == 2 ? 51000 : 48000);
		} else if ($renshu >=1551 && $renshu <= 2000) {
			$leastprice = $grade == 1 ? 62000 : ($grade == 2 ? 59000 : 56000);
		} else if ($renshu >=2001) {
			$leastprice = $grade == 1 ? 62000 : ($grade == 2 ? 59000 : 56000);
		}
	} else {
		$leastprice = 0;
	}
	switch($audit_type)
	{
		case '初审' : $leastprice = floor($leastprice);break;
		case '监督' : $leastprice = floor($leastprice/3);break;
		case '再认证' : $leastprice = floor($leastprice*2/3);break;
		default : $leastprice = floor($leastprice);
	}
	return $leastprice;
}
echo $leastprice['c'].'|'.$leastprice['j'].'|'.$leastprice['z'];
exit;
?>