<?php
/**
 *  工资
 */

class EducationTrain {
	
	private $db;
	public $dbtable;
	
	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}
	
	public function add($params){
		$db = $this->db;
		
		DBUtil::insert_tb($db, $this->dbtable['hr_education_train'], $params);
		$id = $db->insert_id();
		return $id;
	}
	
	public function update($id, $params){
		$db = $this->db;
		
		DBUtil::update_tb($db, $this->dbtable['hr_education_train'], $params, "id='{$id}'");
		
		return $id;
	}
	
	public function delete($id){
		$db = $this->db;

		DBUtil::Del($db, $this->dbtable['hr_education_train'], "id='{$id}'");
		return true;
	}
	
	public function query($id,$params = array()){
		$db = $this->db;
		
		$params == array() ? $field = '*' : $field = implode(',', $params);
		$result = $db->get_one("SELECT {$field} FROM {$this->dbtable['hr_education_train']} WHERE id='{$id}'");
		
		return $result;
	}
	
	public function toArray($where,$params){
		$db = $this->db;
		
		$params == array() ? $field = '*' : $field = implode(',', $params);
		$q = $db->query("SELECT {$field} FROM {$this->dbtable['hr_education_train']} WHERE {$where}");
		while ($rows = $db->fetch_array($q)) {
			$result []= $rows;			
		}

		return $result;
	}
	
	public function listElement($params = array()){
		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum 
							FROM `{$this->dbtable['hr_education_train']}`
							WHERE 1 $search";
		$sql['data'] = "SELECT *
					 		FROM `{$this->dbtable['hr_education_train']}`
								WHERE 1 $search ORDER BY id";
		try {
			$page = new listWage($url, $sql);
			$list = $page->getPageData();
		} catch (Exception $e) {
			echo $e->error_msg();
		}

		$result['pages'] = $page->nav;
		$result['data'] = $list;
		$result['count'] = $page->count;

		return $result;
	}
	


	public function save($id,$params){		
		if((int)$id > 0){
			$id = $this->update($id,$params);
			LogRW::logWriter('','审核员教育/培训修改');
		}elseif ((int)$id == 0) {
			$id = $this->add($params);
			LogRW::logWriter('','审核员教育/培训登记');
		}
		
		return $id;
	}

}
	class listWage extends Page {

	protected function resultFilter($result) {
		$Hr_information = new Hr_information();
		$rows = $Hr_information->query($result['hr_id']);
		$result['username']		= $rows['username'];
		return $result;
	}
}
?>