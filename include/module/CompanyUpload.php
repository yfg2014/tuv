<?php
/*
组织文档上传
*/
class CompanyUpload  {

	public $db;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	public function add($params = array()){
		include_once dirname(__FILE__).'/../class_uploader.php';
		include_once dirname(__FILE__).'/../setup/setup_uploadpath.php';
		include_once dirname(__FILE__).'/../setup/setup_files_types.php';
		include_once dirname(__FILE__).'/../../conf/uploadfiletype.inc.php';

		$zuzhi_id = $params['zuzhi_id'];
		$htfrom = $params['htfrom'];
		$filekind = $params['filekind'];				//资料类型
		$filegroup = $params['filegroup'];				//保存的文件组
		$other = $params['other'];
		$uploadtime = date('Y-m-d H:i:s');
        $zd_ren = $params['zd_ren'];

		$controlname = $params['controlname'];			//上传控件名称
		$allowedMimeTypes = $uploadfiletype['all'];	//允许的上传文件类型
		$uploadpath = $this->mkpath(UPLOAD_DIR.$setup_uploadpath[0].'/'.$zuzhi_id.'/');	//上传文件路径
		$maxsize = $params['MAXSIZE'];					//允许的最大文件大小
		$uploader = new Uploader($controlname,$uploadpath,$maxsize,$allowedMimeTypes);

		//检查上传的文件类型
		if (!$uploader->chkMimetype()) {
			Url::goto_url('', '不允许的上传文件类型');
		}
		//检查上传的文件大小
		if (!$uploader->chkSize()) {
			Url::goto_url('', '超出了最大文件限制');
		}
		$files = $uploader->getFilesInfo();
		$filename = $uploader->upload();	//上传文件
		if (empty($filename)) {
			Url::goto_url('', '上传失败');
		}

		foreach ($files['name'] as $k => $v) {
			$addArray = array(
				'zuzhi_id' => $zuzhi_id,
				'htfrom' => $htfrom,
				'filename' => $files['name'][$k],
				'path' => $setup_uploadpath[0],
				'fname' => $filename[$k],
				'filetype' => $uploader->getExt($files['name'][$k]),
				'filekind' => $filekind[$k],
				'filegroup' => $filegroup,
				'other' => $other[$k],
				'uploadtime' => $uploadtime,
				'zd_ren' => $zd_ren
			);
			DBUtil::insert_tb($this->db, $this->dbtable['mk_company_file'], $addArray);
		}

		return true;
	}
	/*
	 * 生成文件夹路径
	 * 参数为要生成的目录路径
	 * 返回生成后的目录路径
	 */
	private function mkpath($path){
		if (is_dir($path)) {
			return $path;
		} else {
			if (@mkdir($path)) {
				@file_put_contents($path.'index.htm','');
				return $path;
			} else {
				throw new Exception('路径'.$path.'无效');
			}
		}
	}
	public function delete($fid){
		$result = $this->db->get_one("SELECT zuzhi_id,path,fname
							 	FROM `{$this->dbtable['mk_company_file']}`
								WHERE id = '$fid'");
		$file = UPLOAD_DIR.$result['path'].'/'.$result['zuzhi_id'].'/'.$result['fname'];
		$file = str_replace('\\','/',$file);

		DBUtil::Del($this->db, $this->dbtable['mk_company_file'], "id = '$fid'");
		if (file_exists($file))@unlink($file);

		return $result['zuzhi_id'];
	}
	/*
	 * 查询上传文件信息
	 */
	public function query($params = array()) {
		$zuzhi_id = $params['zuzhi_id'];
		$filegroup = $params['filegroup'];

		$sql_guest = "SELECT *
					 	FROM `{$this->dbtable['mk_company_file']}`
						WHERE zuzhi_id = '$zuzhi_id' AND filegroup = '$filegroup'";
		$quer_guest	= $this->db->query($sql_guest);
		while($forum_guest= $this->db->fetch_array($quer_guest))
		{
			$forum_guest['filekind'] = Cache::cache_company_uploadfile($forum_guest['filekind']);
			$result[] = $forum_guest;
		}

		return $result;
	}

	public function toArray($where,$params = array()){
		$db = $this->db;
		$params == array() ? $field = '*' : $field = implode(',', $params);
		$sql = "SELECT {$field} FROM {$this->dbtable[mk_company_file]} WHERE {$where} ORDER BY id DESC ";
		$query	= $db->query($sql);
		while($arr = $db->fetch_array($query)){
			$arr['filekind'] = Cache::cache_company_uploadfile($arr['filekind']);
			$result []= $arr;
		}
		return $result;
	}
	/*
	 * 下载上传文件
	 */
	public function down($fid) {
		if (empty($fid)) {
			exit('无效的文件');
		}
		$setup_files_types = array();
		include_once SET_DIR.'setup_files_types.php';

		mysql_query("SET NAMES 'gbk'");
		$result = $this->db->get_one("SELECT zuzhi_id,path,fname,filename,filetype
							 	FROM `{$this->dbtable['mk_company_file']}`
								WHERE id = '$fid'");
		$filename = $result['filename'];
		$ctype = $setup_files_types[$result['filetype']];
		$file = UPLOAD_DIR.$result['path'].'/'.$result['zuzhi_id'].'/'.$result['fname'];
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
	}

	/*
	 * 文档列表
	 */
	public function listSetup($params = array()){
		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['mk_company_file']}`
							WHERE 1 $search";
		$sql['data'] = "SELECT *
					 		FROM `{$this->dbtable['mk_company_file']}`
								WHERE 1 $search ORDER BY id DESC";
		try {
			$page = new listSetup($url, $sql);
			$list = $page->getPageData();
		} catch (Exception $e) {
			echo $e->error_msg();
		}
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		$result['count'] = $page->count;

		return $result;
	}

}

/**
 *
 * 扩展分页类，过滤结果，追加列
 *
 */
class listSetup extends Page {
	protected function resultFilter($result) {

		$result['filekind'] = Cache::cache_company_uploadfile($result['filekind']);
		return $result;
	}
}

?>