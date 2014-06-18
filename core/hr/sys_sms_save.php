<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/InformationRelease.php';
Power::CkPower('J4E');
GrepUtil::InitGP(array('id','title','user','content','num'));

$touserid = implode('|',$user);
$params = array(
	'title'		  =>$title,
	'touserid' 	  =>$touserid,
	'content'	  =>$content,
	'num'		  =>$num,
	'userid'	  =>$_SESSION['user'],
	'zd_ren'	  =>$_SESSION['username'],
	'zd_date'	  =>date('Y-m-d H:i:s'),
);

$InformationRelease = new InformationRelease();
$id = $InformationRelease->save($id,$params);

Url::goto_url('index.php?m=hr&do=sys_sms_edit&id='.$id, '保存成功');
?>