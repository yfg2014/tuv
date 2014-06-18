<?php
include_once S_DIR.'/include/module/Company.php';
include_once S_DIR.'include/module/Information.php';
include_once S_DIR.'/include/setup/setup_zuzhi_uploadfile.php';

Power::CkPower('K0L');

GrepUtil::InitGP(array('taskId','zuzhi_id'));

$width = '550px';

$Company = new Company();
$com = $Company->GetCompany($zuzhi_id);

$upload = './core/auditor/auditor_file_upload.php';

$id_arr = array('taskId'=>$taskId,'zuzhi_id'=>$zuzhi_id);
$params = array('company' => array(),'task' => array());
$Information = new Information($id_arr,$width,'',$params);

include T_DIR.'header.htm';
include T_DIR.'auditor/auditor_upload_edit.htm';
include T_DIR.'footer.htm';
?>