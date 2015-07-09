<?php
include_once S_DIR.'include/module/Item.php';
try {
	$project = new Project();
	$Item = new Item();
	$project->setRenwuId($rw_id);
	$result = $Item->queryByRenwu($project);
	print_r($result);exit;
	
} catch (Exception $e) {
	echo $e->error_message();
}
		
include TEMP.'header.htm';
include TEMP.'assess/pd_zlok_edit.htm';
include TEMP.'footer.htm';
?>