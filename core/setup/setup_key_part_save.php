<?php
/**
 * 认可标志
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_key_part.php');
$fields = array('id','code','product','products','msg','online');





$value = GrepUtil::InitGP($fields);

if($value['products']==''){$value['product']='';}
/*exit(print_r($value));*/
unset($value['id']);
unset($value['products']);
$s = new setup_key_part();
if ($id) {
	$s->edit_setup($id,$value);
} else {
	$s->add_setup($value);
}
Url::goto_url('index.php?m=setup&do=setup_key_part', '保存成功');
?>