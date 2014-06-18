<?php
include '../include/globals.php';

GrepUtil::InitGP(array('certNo','zsid'));

$zs = $db->get_one("SELECT id FROM zs_cert WHERE certNo='{$certNo}' and id!='{$zsid}' and online!='99' ORDER BY id DESC LIMIT 1");
if($zs['id'] != '')
{
	$wrap = '1';
}
echo $wrap;
?>
