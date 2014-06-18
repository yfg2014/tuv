<?php
/*
审核任务文档上传
*/
class TaskUpload  {

	public $db;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	public function delete($fid){
		$result = $this->db->get_one("SELECT zuzhi_id,path,fname
							 	FROM `{$this->dbtable['xm_task_file']}`
								WHERE id = '$fid'");
		$file = UPLOAD_DIR.$result['path'].'/'.$result['zuzhi_id'].'/'.$result['fname'];
		$file = str_replace('\\','/',$file);

		DBUtil::Del($this->db, $this->dbtable['xm_task_file'], "id = '$fid'");
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
					 	FROM `{$this->dbtable['xm_task_file']}`
						WHERE zuzhi_id = '$zuzhi_id'";
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
		$sql = "SELECT {$field} FROM {$this->dbtable[xm_task_file]} WHERE {$where} ORDER BY id DESC ";
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
							 	FROM `{$this->dbtable['xm_task_file']}`
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
							FROM `{$this->dbtable['xm_task_file']}`
							WHERE 1 $search";
		$sql['data'] = "SELECT *
					 		FROM `{$this->dbtable['xm_task_file']}`
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