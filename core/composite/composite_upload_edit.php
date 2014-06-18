<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/CompositeUpload.php';
include_once S_DIR.'/include/setup/setup_uploadfile.php';

Power::CkPower('L0S');

$upload = './core/composite/composite_file_upload.php';

include T_DIR.'header.htm';
include T_DIR.'composite/composite_upload_edit.htm';
include T_DIR.'footer.htm';
?>