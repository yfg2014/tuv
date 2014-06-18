<?php

/**
 * 案卷
 */
class Files {

	private $db;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	public function add($params){
		$db = $this->db;

		DBUtil::insert_tb($db, $this->dbtable['pd_evaluation_files'], $params);
		$id = $db->insert_id();

		return $id;
	}

	public function update($id, $params){
		$db = $this->db;

		DBUtil::update_tb($db, $this->dbtable['pd_evaluation_files'], $params, "id='{$id}'");

		return $id;
	}

	public function delete($id){
		$db = $this->db;

		DBUtil::Del($db, $this->dbtable['pd_evaluation_files'], "id='{$id}'");

		return true;
	}

	public function query($id,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$result = $db->get_one("SELECT {$field} FROM {$this->dbtable['pd_evaluation_files']} WHERE id='{$id}'");

		return $result;
	}
	
	public function toArray($where,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$sql = $db->query("SELECT {$field} FROM {$this->dbtable['pd_evaluation_files']} WHERE {$where}");
		while ($rows = $db->fetch_array($sql)) {
			$result[] = $rows;
		}

		return $result;
	}

	public function save($id,$params){
		if ((int)$id > 0) {
			$id = $this->update($id,$params);
			LogRW::logWriter($params['zuzhi_id'], '评定问题修改');
		} elseif ((int)$id == 0) {
			$id = $this->add($params);
			LogRW::logWriter($params['zuzhi_id'], '评定问题添加');
		}

		return $id;
	}

	public function listElement($params = array()) {

		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['pd_evaluation_files']}`
							WHERE 1 $search ";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['pd_evaluation_files']}`
						WHERE 1 $search ORDER BY id DESC";
		try {
			$page = new listFiles($url, $sql);
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
class listFiles extends Page {

	protected function resultFilter($result) {
		$result['zs_if'] = Cache::setup_pd_online($result['zs_if']);
		return $result;
	}
}
?>