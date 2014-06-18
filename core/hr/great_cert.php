<?php
!defined('IN_SUPU') && exit('Forbidden');
GrepUtil::InitGP(array('hr_id'));

if($hr_id != '')
{
	$data = md5(time().$hr_id.rand(0,9999)).md5(time().$hr_id.rand(0,9999));
	$rst = DBUtil::Update_tb($db,'hr_information',array('cert'=>$data),"id='$hr_id'");
	if($rst == true){
		$dir = 'upload/hr/'.$hr_id;
		if(!is_dir($dir)){//如果目录不存在
			mkdir("$dir",0777);  //创建文件，权限0777（最大权限）
		}
		$filename = $dir.'/'.$data.'.crt';
		File::writeFile($filename,$data);
		if (!file_exists("$dir/index.htm")) {
			File::writeFile("$dir/index.htm",'');
		}

		header('Last-Modified: '.gmdate('D, d M Y H:i:s',time()).' GMT');
		header('Expires: '.gmdate('D, d M Y H:i:s',time()).' GMT');
		header('Cache-control: max-age=86400');
		header('Content-Encoding: none');
		header("Content-Disposition: attachment; filename=supusoft.crt");
		header("Content-type:text/plain");
		header("Content-Transfer-Encoding: binary");
		echo @file_get_contents($filename);
		exit;
	}else{
		exit('证书生成失败');
	}
}else{
	exit('错误的上传参数');
}
?>