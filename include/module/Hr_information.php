<?php

/**
 * 人员信息类
 * @2011-04-29
 */


class Hr_information {
	private $fields;
	public $db;
	public $error;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	//列表人员信息-ok
	public function listHr($params = array()){
		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum	FROM {$this->dbtable[hr_information]} WHERE 1 $search";
		$sql['data'] = "SELECT * FROM {$this->dbtable[hr_information]} WHERE 1 $search  ORDER BY id DESC";
		$page = new Page($url, $sql);
		$list = $page->getPageData();
		$result['pages']	= $page->nav;
		$result['data']		= $list;
		$result['count']	= $page->count;
		return $result;
	}

	//获取单个人员信息
	public function query($id,$value = array()){
		$db = $this->db;

		$value == array() ? $field = '*' : $field = implode(',',$value);
		$result = $db->get_one("SELECT {$field} FROM `{$this->dbtable[hr_information]}` WHERE id='{$id}'");

		return $result;
	}

	//获取多个人员信息
	public function toArray($where,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$sql = "SELECT {$field} FROM {$this->dbtable[hr_information]} WHERE  {$where}";
		$query	= $db->query($sql);
		while($arr = $db->fetch_array($query)){
			$result []= $arr;
		}

		return $result;
	}

	//新增人员
	public function add($value){
		$this->savebefor('',$value,'add');
		$db = $this->db;
		DBUtil::insert_tb($db,$this->dbtable['hr_information'],$value);
		$id = $db->insert_id();
		return $id;
	}

	//修改人员信息
	public function update($id,$params = array()){
		$this->savebefor($id,$params,'update');
		$db = $this->db;
		DBUtil::update_tb($db, $this->dbtable['hr_information'], $params, "id='".$id."'");
		$db->query("UPDATE hr_reg_qualification SET htfrom='$params[htfrom]' WHERE hr_id='$id'");
		$db->query("UPDATE hr_audit_code SET htfrom='$params[htfrom]' WHERE hr_id='$id'");
		return $id;
	}

	//删除人员
	public function del($id){
		$this->savebefor($id,'','del');
		$db = $this->db;
		$params = array(
			$this->dbtable['hr_audit_code'],
			$this->dbtable['hr_education'],
			$this->dbtable['hr_file'],
			$this->dbtable['hr_profession'],
			$this->dbtable['hr_reg_qualification'],
			$this->dbtable['hr_resume'],
			//$this->dbtable['hr_training'],
			);
		DBUtil::Del($db,$params,"hr_id='$id'");
		DBUtil::Del($db,$this->dbtable['hr_information'],"id='$id'");

	}

	  //错误数据处理
	public function savebefor($hr_id,$value=array(),$func)
	{
		$db = $this->db;
		switch($func){
			case 'add':
				$rows = $db->get_one("SELECT id FROM {$this->dbtable['hr_information']} WHERE idcode ='".$value['idcode']."'");
				if($rows['id'] != ''){
					$error []= '人员编号已存在';
				}
			break;
			case 'update':
				if($value['idcode']!=''){
					$rows = $db->get_one("SELECT id FROM {$this->dbtable['hr_information']} WHERE idcode ='".$value['idcode']."' AND id!='$hr_id'");
					if($rows['id'] != ''){
						$error []= '人员编号已存在';
					}
				}else if($value['user']!=''){
					$rows = $db->get_one("SELECT id FROM {$this->dbtable['hr_information']} WHERE user ='".$value['user']."' AND id!='$hr_id'");
					if($rows['id'] != ''){
						$error []= '人员编号已存在';
					}
				}
			break;
			case 'del':

			break;
		}
		if(!empty($error)){
			Error::ShowError($error);
		}
	}

	//上传人员照片
	public function photos (array $params){

		$setup_photos_types = array();
		include(S_DIR.'/include/setup/setup_photos_types.php');
		if(!in_array($params['files']['type'],$setup_photos_types))
		{
			echo "上传文件格式不正确";exit;
		}
		if($params['files']['size'] > $params['MAXSIZE'])
		{
			echo "上传文件必须小于300k";exit;
		}
		$controlName = $params['files'];
		$uploadPath = 'upload/hr/'.$params['hr_id'];
		if (!is_dir($uploadPath)){
			mkdir("$uploadPath",0777);
		}
		$uploadPath = $uploadPath.'/photos/';
		if (!is_dir($uploadPath)){
			mkdir("$uploadPath",0777);
		}

		$maxSize = 320000;
		$allowedMimeTypes = array('gif','jpg','jpge','png','bmp');
		$Uploader = new Uploader($controlName,$uploadPath,$maxSize,$allowedMimeTypes);
		$upfile_arr = $Uploader->upload();

		$rows = $this->db->get_one("SELECT fid,fname FROM {$this->dbtable['hr_file']} WHERE hr_id='{$params['hr_id']}' AND filekind='8080'");
		if ($rows['fid'] != ''){
			$fileName = $uploadPath.$rows['fname'];
			if (file_exists($fileName))@unlink($fileName);
		}

		$value = array(
			'filename'	=> $controlName['name'],
			'fname'		=> $upfile_arr,
			'path'      => $uploadPath,
			'filetype'	=> $controlName['type'],
			'filekind'	=> $params['filekind'],
			'filetime'	=> date("Ymdhis"),
			'hr_id'		=> $params['hr_id'],
		);
	
		if ($rows['fid']== ''){
			DBUtil::Insert_tb($this->db, $this->dbtable['hr_file'], $value);
			$this->update($params['hr_id'],array('photos'=>$upfile_arr));
		}else{
			DBUtil::Update_tb($this->db, $this->dbtable['hr_file'], $value, "fid='{$rows['fid']}'");
			$this->update($params['hr_id'],array('photos'=>$upfile_arr));
		}
		LogRW::logWriter('','照片上传-'.$params['hr_id']);
	}

}


?>