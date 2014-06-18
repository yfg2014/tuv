<?php
class PublishContent {

	private $db;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	public function add($params){
		$db = $this->db;

		DBUtil::insert_tb($db, $this->dbtable['sys_publish_content'], $params);
		$id = $db->insert_id();

		return $id;
	}

	public function update($id, $params){
		$db = $this->db;

		DBUtil::update_tb($db, $this->dbtable['sys_publish_content'], $params, "id='{$id}'");

		return $id;
	}

	public function delete($id){
		$db = $this->db;

		DBUtil::Del($db, $this->dbtable['sys_publish_content'], "id='{$id}'");

		return true;
	}

	public function query($id,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$result = $db->get_one("SELECT {$field} FROM {$this->dbtable['sys_publish_content']} WHERE id='{$id}'");

		return $result;
	}
	
	public function toArray($where,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$sql = $db->query("SELECT {$field} FROM {$this->dbtable['sys_publish_content']} WHERE {$where}");
		while ($rows = $db->fetch_array($sql)) {
			$result[] = $rows;
		}

		return $result;
	}

	public function save($id,$params){
		if ((int)$id > 0) {
			$id = $this->update($id,$params);
			LogRW::logWriter('', '需求信息回复修改');
		} elseif ((int)$id == 0) {
			$id = $this->add($params);
			LogRW::logWriter('', '需求信息回复添加');
		}

		return $id;
	}

	public function listElement($params = array()) {

		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['sys_publish_content']}`
							WHERE 1 $search ";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['sys_publish_content']}`
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
		return $result;
	}
}
?>
