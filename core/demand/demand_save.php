<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Demand.php';

GrepUtil::InitGP(array('id','FileID','menu','title','content'));

$Demand = new Demand();
$params = array(
	'menu' => $menu,
	'title' => $title,
	'content' => $content,
	'zd_time' => date("Y-m-d")
);

$FileID > 0 && $id = $FileID;

if($id > 0){
	$row = $db->get_one("SELECT title FROM  `sys_demand` WHERE id='$id'");
	$id = $Demand->update($id,$params);
	if($row['title'] == ''){
		LogRW::logWriter('', '需求登记 ID='.$id);
	}else{
		LogRW::logWriter('', '需求修改 ID='.$id);
	}
}else{
	$id = $Demand->add($params);
	LogRW::logWriter('', '需求登记 ID='.$id);
}
Url::goto_url('index.php?m=demand&do=publish_content_edit&fbid='.$id, '保存成功');
?>
