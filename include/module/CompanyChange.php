<?php
/**
 *  组织重大变更
 */

class CompanyChange {
	
	private $db;
	public $dbtable;
	
	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}
	
	public function add($params){
		$db = $this->db;
		
		DBUtil::insert_tb($db, $this->dbtable['zs_company_change'], $params);
		$id = $db->insert_id();
		return $id;
	}
	
	public function update($id, $params){
		$db = $this->db;
		
		DBUtil::update_tb($db, $this->dbtable['zs_company_change'], $params, "id='{$id}'");
		
		return $id;
	}
	
	public function delete($id){
		$db = $this->db;

		DBUtil::Del($db, $this->dbtable['zs_company_change'], "id='{$id}'");
		return true;
	}
	
	public function query($id,$params = array()){
		$db = $this->db;
		
		$params == array() ? $field = '*' : $field = implode(',', $params);
		$result = $db->get_one("SELECT {$field} FROM {$this->dbtable['zs_company_change']} WHERE id='{$id}'");
		
		return $result;
	}
	
	public function toArray($where,$params){
		$db = $this->db;
		
		$params == array() ? $field = '*' : $field = implode(',', $params);
		$q = $db->query("SELECT {$field} FROM {$this->dbtable['zs_company_change']} WHERE {$where}");
		while ($rows = $db->fetch_array($q)) {
			$result []= $rows;			
		}

		return $result;
	}
	
	public function listElement($params = array()){
		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum 
							FROM `{$this->dbtable['zs_company_change']}`
							WHERE 1 $search";
		$sql['data'] = "SELECT *
					 		FROM `{$this->dbtable['zs_company_change']}`
								WHERE 1 $search ORDER BY id";
		try {
			$page = new list_st($url, $sql);
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
		}elseif ((int)$id == 0) {
			$id = $this->add($params);
		}
		
		return $id;
	}
}

/**
 *
 * 扩展分页类，过滤结果，追加列
 * @author Tom
 *
 */
class list_st extends Page {

	protected function resultFilter($result) {
		return $result;
	}
}
?>