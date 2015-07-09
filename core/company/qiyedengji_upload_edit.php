<?php
include_once S_DIR.'/include/module/Company.php';
include_once S_DIR.'/include/module/CompanyUpload.php';
include_once S_DIR.'/include/setup/setup_zuzhi_uploadfile.php';

Power::CkPower('A1E');

GrepUtil::InitGP(array('zuzhi_id','code'));

Power::xt_htfrom($zuzhi_id);

$width = '550px';
$Company = new Company();
$com = $Company->GetCompany($zuzhi_id);

$sql_temp = " zuzhi_id='{$zuzhi_id}'";
if($code != ""){
$sql_temp .=" AND filekind='{$code}'";
}

//上传文件 权限
if(strpos($_SESSION['power'],'Z5A') === false){ unset($setup_zuzhi_uploadfile['1001']);$sql_temp .=" AND filekind!='1001'";}
if(strpos($_SESSION['power'],'Z5B') === false){ unset($setup_zuzhi_uploadfile['1002']);$sql_temp .=" AND filekind!='1002'";}
if(strpos($_SESSION['power'],'Z5C') === false){ unset($setup_zuzhi_uploadfile['1003']);$sql_temp .=" AND filekind!='1003'";}
if(strpos($_SESSION['power'],'Z5D') === false){ unset($setup_zuzhi_uploadfile['1004']);$sql_temp .=" AND filekind!='1004'";}

$CompanyUpload = new CompanyUpload();
$arr = $CompanyUpload->toArray($sql_temp);

$upload = './core/company/qiyedengji_file_upload.php';



include TEMP.'header.htm';
include TEMP.'company/qiyedengji_upload_edit.htm';
include TEMP.'footer.htm';
?>