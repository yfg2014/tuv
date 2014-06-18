<?php

/** 
 * 资料
 * @author baisiye
 * 
 * 
 */
class ZiliaoDao{
	private $db;
	private $Ziliao;
	public $error;
	public $dbtable;
	
	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
		$this->Ziliao= $Ziliao;
	}
	
	public function add($params){
		$db = $this->db;
		DBUtil::insert_tb($db,$this->dbtable['hr_file'],$params);
		
	}
	public function delete($fid){
		$result = $this->db->get_one("SELECT *
							 	FROM `{$this->dbtable['hr_file']}`
								WHERE fid = '$fid'");
		$file = $result['path'].'/'.$result['fname'];
		$file = str_replace('\\','/',$file);

		DBUtil::Del($this->db, $this->dbtable['hr_file'], "fid='$fid'");
		if (file_exists($file))@unlink($file);
		LogRW::logWriter('','删除人员文件-'.$result['filename']);

		return $result;
		
	}
	public function query($id){
		$db = $this->db;
		$sql="SELECT * FROM `{$this->dbtable[hr_file]}` WHERE 1 and hr_id='{$id}' AND filekind!='8080'";
		$q = $db->query($sql);
	
		while ($row = $db->fetch_array($q)) {			
			$a[] = $row;
		}
		return $a;
	}
	
	/*
	 * 资料文件上传
	 */
	public function uploadfile($params){
		$setup_files_types = array();
		include_once(S_DIR.'/include/setup/setup_files_types.php');
		foreach ($params['files']['type'] as $k=>$v){
			if(!in_array($v,$setup_files_types))
			{
				echo "上传人员文件格式不正确".$v;exit;
			}		
			if($params['files']['size'][$k]> 5000000)
			{
				echo "上传人员文件必须小于5M".$params['files']['size'][$k];exit;
			}
		}

		$dir = 'upload/hr/'.$params['hr_id'];
		if(!is_dir($dir))
		{
			mkdir("$dir",0777);
		}
		
		$fileFormat = array('gif','jpg','jpge','png','bmp','rar','zip','xls','doc','ppt','pdf','txt');
		$maxSize = 0;
		$overwrite = 1;
		$f = new clsUpload($dir, $fileFormat, $maxSize, $overwrite);
		$f->setThumb(0);
		if (!$f->run('files',0)){
			echo "上传人员文件失败";exit;
		}
		$arr = array();
		$arr=$f->getInfo();
		if(!empty($arr))
		{
			foreach ($arr as $k=>$v){
				$value = array(
					'filename'		=> $v['filename'],
					'fname'			=> $v['fname'],
					'path'			=> $dir,
					'filetype'		=> $v['filetype'],
					'filekind'		=> $params['filekind'][$k],
					'filetime'		=> date("Ymdhis"),
					'hr_id'			=> $params['hr_id'],
				);
				$this->add($value);
			}
		}
			
		LogRW::logWriter('','上传人员文件-'.$params['hr_id']);
	}
	
	/*
	 * 资料文件下载
	 */
	public function down($fid) {
		if (empty($fid)) {
			throw new Exception('无效的文件');
		}
		$setup_files_types = array();
		include_once(S_DIR.'/include/setup/setup_files_types.php');
		
		mysql_query("SET NAMES 'gbk'");
		$result = $this->db->get_one("SELECT hr_id,path,fname,filename,filetype
							 	FROM `{$this->dbtable['hr_file']}`
								WHERE fid = '$fid'");
		$filename = $result['filename'];
		$ctype = $setup_files_types[$result['filetype']];
		$file = $result['path'].'/'.$result['fname'];
		header('Last-Modified: '.gmdate('D, d M Y H:i:s',time()).' GMT');
		header('Expires: '.gmdate('D, d M Y H:i:s',time()).' GMT');
		header('Cache-control: max-age=86400');
		header('Content-Encoding: none');
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-type:$ctype");
		header("Content-Transfer-Encoding: binary");
		echo @file_get_contents($file);
		exit;
	}
	
	//列表人员资料信息
	public static function list_ziliao($params = array()){	
		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum 
							FROM `{$this->dbtable[hr_file]}` as z
					 		left join `{$this->dbtable[hr_information]}` as r on z.hr_id=r.id
								WHERE 1 $search";
		$sql['data'] = "SELECT z.*,r.username
					 		FROM `{$this->dbtable[hr_file]}` as z
					 		left join `{$this->dbtable[hr_information]}` as r on z.hr_id=r.id
								WHERE 1 $search ORDER BY id";
		try {
			$page = new Page($url, $sql);
			$list = $page->getPageData();
		} catch (Exception $e) {
			echo $e->error_msg();
		}
		$result['pages']	= $page->nav;
		$result['data']		= $list;
		$result['count']	= $page->count;
		return $result;
		
	}

}

?>