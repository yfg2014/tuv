<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once SET_DIR.'setup_upplan_online.php';
GrepUtil::InitGP(array('kind','id','upplan'));
if($kind == 'upplan'){
	switch($upplan){
		case '0' : $db->query("UPDATE $dbtable[xm_item] SET upplan='1' WHERE id='$id'");exit('true');break;
		case '1' : $db->query("UPDATE $dbtable[xm_item] SET upplan='0' WHERE id='$id'");exit('true');break;
		case '2' : $db->query("UPDATE $dbtable[xm_item] SET upplan='3' WHERE id='$id'");exit('true');break;
		case '3' : $db->query("UPDATE $dbtable[xm_item] SET upplan='2' WHERE id='$id'");exit('true');break;
		default : exit('false');
	}
}else{
	exit('false');
}
?>