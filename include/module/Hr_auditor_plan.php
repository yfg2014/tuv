<?php

/**
 * 审核培训
 * @2011-05-4
 */

class Hr_auditor_plan {
	private $fields;
	public $db;
	public $error;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	//培训计划列表
	public function list_auditor_plan($params = array()){

		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum	FROM {$this->dbtable[hr_auditor_plan]} WHERE 1 $search";
		$sql['data'] = "SELECT * FROM {$this->dbtable[hr_auditor_plan]} WHERE 1 $search  ORDER BY id DESC";

		$page = new listAuditor_plan($url, $sql);
		$list = $page->getPageData();
		$result['pages']	= $page->nav;
		$result['data']		= $list;
		$result['count']	= $page->count;
		return $result;
	}

	//获取单个培训计划 信息
	public function query($id,$value = array()){
		$db = $this->db;

		$value == array() ? $field = '*' : $field = implode(',',$value);
		$result = $db->get_one("SELECT {$field} FROM `{$this->dbtable[hr_auditor_plan]}` WHERE id='{$id}'");
		return $result;
	}

	public function toArray($where,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$sql = "SELECT {$field} FROM {$this->dbtable[hr_auditor_plan]} WHERE  {$where}";
		$query	= $db->query($sql);
		while($arr = $db->fetch_array($query)){
			$result []= $arr;
		}

		return $result;
	}

	//新增培训计划
	public function add($value){
		$db = $this->db;
		
		DBUtil::insert_tb($db,$this->dbtable['hr_auditor_plan'],$value);
		
		$id = $db->insert_id();
		LogRW::logWriter($id,'新增-'.Cache::cache_username($value['hr_id']).'-培养计划');
	}
	
	//修改单个培训计划信息
	public function update($id,$params = array()){
		$db = $this->db;
		
		DBUtil::update_tb($db, $this->dbtable['hr_auditor_plan'], $params, "id='".$id."'");
		
		return $id;
		
		LogRW::logWriter($id,'修改-'.Cache::cache_username($params['hr_id']).'-培养计划');
	}

	//删除单个培训计划
	public function del($id){
		$db = $this->db;
		$rows = $db->get_one("SELECT hr_id FROM `{$this->dbtable['hr_auditor_plan']}` WHERE id='{$id}'"); 
		DBUtil::Del($db,$this->dbtable['hr_auditor_plan'],"id='$id'");
		
		return $id;
		
		LogRW::logWriter($id,'删除-'.Cache::cache_username($rows['hr_id']).'-培训计划');
	}

	//错误数据处理
	public function savebefore($func,$value){
	 	
	}
}
/**
 *
 * 扩展分页类，过滤结果
 *
 *
 */
class listAuditor_plan extends Page {

	
}

?>