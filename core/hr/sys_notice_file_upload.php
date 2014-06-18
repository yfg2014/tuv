<?php
include '../../include/globals.php';
include SET_DIR.'setup_uploadpath.php';

GrepUtil::InitGP(array('FileID'));

$save_path = UPLOAD_DIR.$setup_uploadpath[5].'/';
$upload_name = "resume_file";
$max_file_size_in_bytes = 2147483647;
$extension_whitelist = array("jpg", "gif", "png","pdf","rar","zip","doc","xls","doc","txt","ppt");

if (!isset($_FILES[$upload_name])) {
	HandleError();
	exit(0);
} else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
	HandleError($uploadErrors[$_FILES[$upload_name]["error"]]);
	exit(0);
} else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
	HandleError();
	exit(0);
} else if (!isset($_FILES[$upload_name]['name'])) {
	HandleError();
	exit(0);
}

$file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
if (!$file_size || $file_size > $max_file_size_in_bytes) {
	HandleError();
	exit(0);
}

if ($file_size <= 0) {
	HandleError();
	exit(0);
}

$path_info = pathinfo($_FILES[$upload_name]['name']);
$file_extension = $path_info["extension"];
$is_valid_extension = false;
foreach ($extension_whitelist as $extension) {
	if (strcasecmp($file_extension, $extension) == 0) {
		$is_valid_extension = true;
		break;
	}
}
if (!$is_valid_extension) {
	HandleError();
	exit(0);
}

if(!is_dir($save_path))
{
	@mkdir($save_path);
}

$file_name = basename($_FILES[$upload_name]['name'],".".$file_extension);
$filename = md5($filename.(time() + rand(0,999999))) . '.' . $file_extension;
if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path.$filename)) {
		HandleError();
		exit(0);
}else{
	$value = array(
		'filename' => $_FILES[$upload_name]['name'],
		'path' => $setup_uploadpath[5],
		'fname' => $filename,
		'filetype' => $file_extension,
	);
	if($FileID > 0){
		$rows = $db->get_one("SELECT * FROM {$dbtable['sys_information_release']} WHERE id='".$FileID."'");
		DBUtil::Update_tb($db,$dbtable['sys_information_release'],$value,"id='{$FileID}'");
		$id = $FileID;
		LogRW::logWriter('',$_FILES[$upload_name]['name'].' 公告附件修改上传');
		$file = UPLOAD_DIR.$rows['path'].'/'.$rows['fname'];
		$file = str_replace('\\','/',$file);
		if (file_exists($file))@unlink($file);
	}else{
		DBUtil::insert_tb($db, $dbtable['sys_information_release'], $value);
		$id = mysql_insert_id();
		LogRW::logWriter('',$_FILES[$upload_name]['name'].' 公告附件上传');
	}
	echo $id;
}

exit(0);

function HandleError() {
	echo 'error';
}
?>
