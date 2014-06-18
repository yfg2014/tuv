<?php

/**
 * 人员职称类
 * @2011-05-5
 */


class hr_profession {
	private $fields;
	public $db;
	public $error;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	//列表人员职称信息
	public function list_audit_code($params = array()){

		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum	FROM {$this->dbtable[hr_profession]} WHERE 1 $search";
		$sql['data'] = "SELECT * FROM {$this->dbtable[hr_profession]} WHERE 1 $search  ORDER BY id DESC";

		$page = new Page($url, $sql);
		$list = $page->getPageData();
		$result['pages']	= $page->nav;
		$result['data']		= $list;
		$result['count']	= $page->count;
		return $result;
	}

	//获取单个人员职称
	public function query($id,$value = array()){
		$db = $this->db;

		$value == array() ? $field = '*' : $field = implode(',',$value);
		$result = $db->get_one("SELECT {$field} FROM `{$this->dbtable[hr_profession]}` WHERE id='{$id}'");
		return $result;
	}

	public function toArray($where,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$sql = "SELECT {$field} FROM {$this->dbtable[hr_profession]} WHERE  {$where}";
		$query	= $db->query($sql);
		while($arr = $db->fetch_array($query)){
			$result []= $arr;
		}

		return $result;
	}

	//新增人员职称
	public function add($value){
	    $this->savebefore();
		$db = $this->db;
		DBUtil::insert_tb($db,$this->dbtable['hr_profession'],$value);
	}

	//修改人员职称信息
	public function update($id,$params = array()){
         $this->savebefore();
		$db = $this->db;
		DBUtil::update_tb($db, $this->dbtable['hr_profession'], $params, "id='".$id."'");
		return $id;
	}

	//删除人员职称
	public function del($id){
	    $this->savebefore();
		$db = $this->db;
		DBUtil::Del($db,$this->dbtable['hr_profession'],"id='$id'");
	}
	//错误数据处理
	public function savebefore()
	{
	   return;
	}

}


?>