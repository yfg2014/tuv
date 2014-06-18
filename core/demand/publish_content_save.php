<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/PublishContent.php';

GrepUtil::InitGP(array('eid','fbid','title','content','name'));

$PublishContent = new PublishContent();
$params = array(
	'fbid' => $fbid,
	'name' => $name,
	'content' => $content,
	'zd_time' => date("Y-m-d")
);
$id = $PublishContent->save($eid,$params);

Url::goto_url('index.php?m=demand&do=publish_content_edit&fbid='.$fbid, '保存成功');
?>
