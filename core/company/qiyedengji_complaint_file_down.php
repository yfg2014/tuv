<?php
/*
 * 下载文件
 */
GrepUtil::InitGP(array('id'));

if (empty($id)) {
	exit('无效的文件');
}
$setup_files_types = array();
include_once SET_DIR.'setup_files_types.php';

mysql_query("SET NAMES 'gbk'");
$result = $db->get_one("SELECT id,path,fname,filename,filetype
					 	FROM `mk_company_complaint`
						WHERE id = '$id'");

$filename = $result['filename'];
$ctype = $setup_files_types[$result['filetype']];
$file = UPLOAD_DIR.$result['path'].'/'.$result['fname'];

$handle = fopen($file,"r");
header('Last-Modified: '.gmdate('D, d M Y H:i:s',time()).' GMT');
header('Expires: '.gmdate('D, d M Y H:i:s',time()).' GMT');
header('Cache-control: max-age=86400');
header('Content-Encoding: none');
header("Content-Disposition: attachment; filename=$filename");
header("Content-type:$ctype");
header("Content-Transfer-Encoding: binary");
while (!feof($handle)) {
  $string= @fgets($handle, 86400);
  echo $string;
}
exit;

?>