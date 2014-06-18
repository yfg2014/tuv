<?php
class Demand {

	private $db;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	public function add($params){
		$db = $this->db;

		DBUtil::insert_tb($db, $this->dbtable['sys_demand'], $params);
		$id = $db->insert_id();

		return $id;
	}

	public function update($id, $params){
		$db = $this->db;

		DBUtil::update_tb($db, $this->dbtable['sys_demand'], $params, "id='{$id}'");

		return $id;
	}

	public function delete($id){
		$db = $this->db;

		DBUtil::Del($db, $this->dbtable['sys_demand'], "id='{$id}'");

		return true;
	}

	public function query($id,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$result = $db->get_one("SELECT {$field} FROM {$this->dbtable['sys_demand']} WHERE id='{$id}'");

		return $result;
	}

	public function toArray($where,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$sql = $db->query("SELECT {$field} FROM {$this->dbtable['sys_demand']} WHERE {$where}");
		while ($rows = $db->fetch_array($sql)) {
			$result[] = $rows;
		}

		return $result;
	}

	public function save($id,$params){
		if ((int)$id > 0) {
			$id = $this->update($id,$params);
			LogRW::logWriter('', '需求信息修改');
		} elseif ((int)$id == 0) {
			$id = $this->add($params);
			LogRW::logWriter('', '需求信息添加');
		}

		return $id;
	}

	public function listElement($params = array()) {

		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['sys_demand']}`
							WHERE 1 $search ";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['sys_demand']}`
						WHERE 1 $search ORDER BY id DESC";
		try {
			$page = new listElement($url, $sql);
			$list = $page->getPageData();
		} catch (Exception $e) {
			exit($e->error_msg());
		}
		$result['count'] = $page->count;
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		return $result;
	}
}

/**
 *
 * 扩展分页类，过滤结果，追加列
 * @author Tom
 *
 */
class listElement extends Page {

	protected function resultFilter($result) {

		$result['menu'] = Cache::cache_menu($result['menu']);

		return $result;
	}
}
?>