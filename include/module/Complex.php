<?php
/**
 *  复评
 */

class Complex {
	
	private $db;
	public $dbtable;
	
	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}
	
	public function add($params){
		$db = $this->db;
		
		DBUtil::insert_tb($db, $this->dbtable['ht_repeat_contract'], $params);
		$id = $db->insert_id();
		return $id;
	}
	
	public function update($id, $params){
		$db = $this->db;
		
		DBUtil::update_tb($db, $this->dbtable['ht_repeat_contract'], $params, "id='{$id}'");
		
		return $id;
	}
	
	public function delete($id){
		$db = $this->db;

		DBUtil::Del($db, $this->dbtable['ht_repeat_contract'], "id='{$id}'");
		return true;
	}
	
	public function query($id,$params = array()){
		$db = $this->db;
		
		$params == array() ? $field = '*' : $field = implode(',', $params);
		$result = $db->get_one("SELECT {$field} FROM {$this->dbtable['ht_repeat_contract']} WHERE id='{$id}'");
		
		return $result;
	}
	
	public function toArray($where,$params){
		$db = $this->db;
		
		$params == array() ? $field = '*' : $field = implode(',', $params);
		$q = $db->query("SELECT {$field} FROM {$this->dbtable['ht_repeat_contract']} WHERE {$where}");
		while ($rows = $db->fetch_array($q)) {
			$result []= $rows;			
		}

		return $result;
	}
	
	public function listSetup($params = array()){
		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['ht_repeat_contract']}` a LEFT JOIN `{$this->dbtable['zs_cert']}` b ON a.zsid=b.id
							WHERE 1 $search AND (b.online = '01' OR b.online = '03' OR b.online = '04'  OR b.online = '09')";
		$sql['data'] = "SELECT a.*,b.audit_code,b.online AS z_online
							FROM `{$this->dbtable['ht_repeat_contract']}` a LEFT JOIN `{$this->dbtable['zs_cert']}` b ON a.zsid=b.id
							WHERE 1 $search AND (b.online = '01' OR b.online = '03' OR b.online = '04'  OR b.online = '09') ORDER BY a.id DESC";
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
	class list_st extends Page {

	protected function resultFilter($result) {
		$ht = $this->db->get_one("SELECT iso FROM `{$this->dbtable['ht_contract']}` WHERE id='{$result['ht_id']}' LIMIT 1");
		$result['ht_iso'] = $ht['iso'];
		
		$zs = $this->db->get_one("SELECT certNo FROM `{$this->dbtable['zs_cert']}` WHERE id='{$result['zsid']}' LIMIT 1");
		$result['certNo'] = $zs['certNo'];
		
		return $result;
	}
}
?>