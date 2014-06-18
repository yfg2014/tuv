<?php
include S_DIR.'/include/module/CompanyComplaint.php';

Power::CkPower('A2D');

GrepUtil::InitGP(array('id','zuzhi_id'));

//删除文件
$rows = $db->get_one("SELECT * FROM {$dbtable['mk_company_complaint']} WHERE id='".$id."'");
$file = UPLOAD_DIR.$rows['path'].'/'.$rows['fname'];
$file = str_replace('\\','/',$file);
if (file_exists($file))@unlink($file);

$CompanyComplaint = new CompanyComplaint();
$CompanyComplaint->delete($id);



LogRW::logWriter($id,'删除-'.Cache::cache_company($zuzhi_id).'-客户投诉');

Url::goto_url('index.php?m=company&do=qiyedengji_complaint_list', '删除成功');
?>