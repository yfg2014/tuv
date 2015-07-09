<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/CompositeUpload.php';
include_once S_DIR.'/include/setup/setup_uploadfile.php';

Power::CkPower('L0S');

$upload = './core/composite/composite_file_upload.php';

include TEMP.'header.htm';
include TEMP.'composite/composite_upload_edit.htm';
include TEMP.'footer.htm';
?>