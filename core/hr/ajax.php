<?php
!defined('IN_SUPU') && exit('Forbidden');
GrepUtil::InitGP(array('type'));

switch ($type) {
	case 'user':
		GrepUtil::InitGP(array('user','hr_id'));
		$result	= $db->get_one("SELECT id from hr_information WHERE user = '$user' AND id!='$hr_id' LIMIT 1");
		if ($result['id']) {
			exit('登陆账号已存在');
		}
		break;
	case 'code':
		GrepUtil::InitGP(array('iso','hr_id'));
		$result	= $db->get_one("SELECT id,qualification FROM `hr_reg_qualification` WHERE hr_id='{$hr_id}' AND iso='{$iso}' ORDER BY id DESC LIMIT 0,1");
		if ($result['id']) {
			echo $result['qualification'];
			exit;
		}
		break;
	case 'idcode':
		GrepUtil::InitGP(array('idcode','id'));
		if ($id == '' && $idcode != ''){
			$result = $db->get_one("SELECT id from hr_information WHERE idcode = '$idcode'");
			if ($result['id']) {
				exit('此人员编号已被占用！');
			}
		} elseif($id !='' && $idcode != '') {
			$result = $db->get_one("SELECT id from hr_information WHERE idcode = '$idcode' AND id !='$id' LIMIT 1");
			if ($result['id']) {
				exit('此人员编号已被占用！');
			}
		}
		break;
	case 'e_date' :
		GrepUtil::InitGP(array('e_date','s_date'));
		$s_date = explode('-',$s_date);
		$y = $s_date['0'];
		$m = $s_date['1'];
		$d = $s_date['2'];
		$e_date = date("Y-m-d",mktime(0,0,0,$m,$d-1,$y+3));
		echo $e_date;exit;
		break;
	default:;
}
?>