<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/InformationRelease.php';
Power::CkPower('J3E');
GrepUtil::InitGP(array('id','FileID','title','departments','content','num'));

$params = array(
	'title'		  =>$title,
	'departments' =>$departments,
	'content'	  =>$content,
	'num'		  =>$num,
	'userid'	  =>$_SESSION['user'],
	'zd_ren'	  =>$_SESSION['username'],
	'zd_date'	  =>date('Y-m-d H:i:s'),
);

$InformationRelease = new InformationRelease();

$FileID > 0 && $id = $FileID;
			
if($id > 0){
	$row = $db->get_one("SELECT title FROM  `sys_information_release` WHERE id='$id'");
	$InformationRelease->update($id,$params);
	if($row['title'] == ''){
		LogRW::logWriter($params['id'], '公告登记');
	}else{
		LogRW::logWriter($params['id'], '公告修改');
	}
}else{
	$InformationRelease->add($params);
	LogRW::logWriter($params['id'], '公告登记');
}

Url::goto_url('index.php?m=hr&do=sys_notice_edit&id='.$id, '保存成功');
?>