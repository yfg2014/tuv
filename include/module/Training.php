<?php
/*
 * 人员培训
 */

class Training
{
	public $db;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	public function add($params){
		$db = $this->db;

		DBUtil::insert_tb($db, $this->dbtable['hr_training'], $params);
		$id = $db->insert_id();

		return $id;
	}

	public function update($id, $params){
		$db = $this->db;

		DBUtil::update_tb($db, $this->dbtable['hr_training'], $params, "id='{$id}'");

		return $id;
	}

	public function delete($id){
		$db = $this->db;
		
		DBUtil::Del($db, $this->dbtable['hr_portfolio_id'], "mix_id='{$id}'");
		DBUtil::Del($db, $this->dbtable['hr_training'], "id='{$id}'");
		LogRW::logWriter('', '删除人员培训信息');
		
		return true;
	}

	public function query($id,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$result = $db->get_one("SELECT {$field} FROM {$this->dbtable['hr_training']} WHERE id='{$id}'");

		return $result;
	}
	
	public function portfolio($id,$eid){
		$db = $this->db;
		
		$params = explode(',',$eid);
		DBUtil::Del($db, $this->dbtable['hr_portfolio_id'], "mix_id='{$id}'");
		foreach ($params as $v){
			DBUtil::insert_tb($db, $this->dbtable['hr_portfolio_id'], array('mix_id'=>$id,'hr_id'=>$v));
		}
	}
	
	public function toArray ($mix_id){
		$db = $this->db;
		
		$result = '';
		$sql = "SELECT hr_id FROM {$this->dbtable['hr_portfolio_id']} WHERE mix_id='{$mix_id}'";
		$query = $db->query($sql);
		while ($rows = $db->fetch_array($query)){
			$result []= $rows['hr_id'];
		}
		
		return $result;
	}

	public function save($id,$params,$eid){
		if ((int)$id > 0) {
			$id = $this->update($id,$params);
			$this->portfolio($id,$eid);
			LogRW::logWriter('', '修改人员培训信息');
		} elseif ((int)$id == 0) {
			$id = $this->add($params);
			$this->portfolio($id,$eid);
			LogRW::logWriter('', '添加人员培训信息');
		}

		return $id;
	}
	
	public function listElement($params = array()) {

		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['hr_training']}`
							WHERE 1 $search ";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['hr_training']}`
						WHERE 1 $search ORDER BY trainingdate DESC";
		$page = new Page($url, $sql);
		$list = $page->getPageData();
		$result['count'] = $page->count;
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		return $result;
	}
	
	public function listElementId($params = array()) {

		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['hr_portfolio_id']}`
							WHERE 1 $search ";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['hr_portfolio_id']}`
						WHERE 1 $search ORDER BY mix_id DESC";
		$page = new listElementId($url, $sql);
		$list = $page->getPageData();
		$result['count'] = $page->count;
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		return $result;
	}
}

class listElementId extends Page {

	protected function resultFilter($result) {
		$Training = new Training();
		$rows = $Training->query($result['mix_id']);
		$result['title'] = $rows['title'];
		$result['teachertraining'] = $rows['teachertraining'];
		$result['content'] = $rows['content'];
		$result['trainingdate'] = $rows['trainingdate'];
		
		$hr = $Training->db->get_one("SELECT username FROM {$Training->dbtable['hr_information']} where id='{$result['hr_id']}'");
		$result['username'] = $hr['username'];
		return $result;
	}
}
?>