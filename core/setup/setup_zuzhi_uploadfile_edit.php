<?php
/**
 * ��������
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_zuzhi_uploadfile.php');
GrepUtil::InitGP(array('id'));
if ($id) {
	$s = new setup_zuzhi_uploadfile();
	$rst = $s->get_setup($id);
}

include TEMP.'header.htm';
include TEMP.'setup/setup_zuzhi_uploadfile_edit.htm';
include TEMP.'footer.htm';
?>