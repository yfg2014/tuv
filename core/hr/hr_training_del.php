<?php
include_once S_DIR.'/include/module/Training.php';

Power::CkPower('H4D');

GrepUtil::InitGP(array('id','hr_id'));

$Training = new Training();
$Training->delete($id);
LogRW::logWriter('', '删除培训发布信息');

Url::goto_url('index.php?m=hr&do=hr_training_list', '删除成功');
?>