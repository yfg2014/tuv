<?php
include '../../include/globals.php';
include SET_DIR.'setup_uploadpath.php';

GrepUtil::InitGP(array('filekind','filegroup','other','username'));

$save_path = UPLOAD_DIR.$setup_uploadpath[1].'/';
$upload_name = "Filedata";
$max_file_size_in_bytes = 2147483647;	
$extension_whitelist = array("jpg", "gif", "png","pdf","rar","zip","doc","xls","doc","txt","ppt");

$uploadErrors = array(
    0=>"有没有错误，文件上传成功",
//    1=>"上传的文件超过了php.ini中upload_max_filesize指令",
    1=>"上传的文件超过了固定大小",
    2=>"上传的文件超过MAX_FILE_SIZE是在HTML表单中指定的指令",
    3=>"只上传了部分文件被上传",
    4=>"没有文件被上传",
    6=>"缺少一个临时文件夹"
);

if (!isset($_FILES[$upload_name])) {
	HandleError("没有发现上传文件");
	exit(0);
} else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
	HandleError($uploadErrors[$_FILES[$upload_name]["error"]]);
	exit(0);
} else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
	HandleError("上传失败is_uploaded_file测试");
	exit(0);
} else if (!isset($_FILES[$upload_name]['name'])) {
	HandleError("文件还没有名字");
	exit(0);
}

$file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
if (!$file_size || $file_size > $max_file_size_in_bytes) {
	HandleError("文件超过了允许的最大大小2G");
	exit(0);
}

if ($file_size <= 0) {
	HandleError("文件大小不能小于或者等于0");
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
	HandleError("上传文件扩展名无效");
	exit(0);
}

if(!is_dir($save_path))
{
	@mkdir($save_path);
}

$file_name = basename($_FILES[$upload_name]['name'],".".$file_extension);
$filename = md5($filename.(time() + rand(0,999999))) . '.' . $file_extension;
if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path.$filename)) {
		HandleError("上传文件无法保存文件");
		exit(0);
}else{
	$value = array(
		'filename' => $_FILES[$upload_name]['name'],
		'path' => $setup_uploadpath[1],
		'fname' => $filename,
		'filetype' => $file_extension,
		'filekind' => $filekind,
		'filegroup' => $filegroup,
		'other' => $other,
		'uploadtime' => date('Y-m-d H:i:s'),
		'zd_ren' => $username
	);
	
	DBUtil::insert_tb($db, $dbtable['mk_law_file'], $value);
	 
	LogRW::logWriter('',$_FILES[$upload_name]['name'].' 法律法规文件上传');
}

exit(0);

function HandleError($message) {
	echo $message;
}
?>