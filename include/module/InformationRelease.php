<?php
/*
 * 信息发布
 */
class InformationRelease {
	
	private $db;
	public $dbtable;

	public function __construct(){
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}
	
	public function add($params = array()){
		$db = $this->db;
		DBUtil::insert_tb($db, $this->dbtable['sys_information_release'], $params);
		$id = $db->insert_id();
		
		return $id;
	}
	
	public function update($id, $params){
		$db = $this->db;

		DBUtil::update_tb($db, $this->dbtable['sys_information_release'], $params, "id='".$id."'");
		
		return $id;
	}
	
	public function query($id,$params = array()){		
		$db = $this->db;
		
		$params == array() ? $field = '*' : $field = implode(',', $params);
		$reuslt = $db->get_one("SELECT {$field} FROM {$this->dbtable['sys_information_release']} WHERE id='".$id."'");
		
		return $reuslt;
	}
	
	public function toArray($where,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$sql = "SELECT {$field} FROM `{$this->dbtable['sys_information_release']}` WHERE {$where}";
		$query = $db->query($sql);
		while ($rows = $db->fetch_array($query)) {			
			$result []= $rows;
		}

		return $result;
	}
	
	public function save($id,$params = array()){

		if ((int)$id > 0) {
			$id = $this->update($id,$params);
			LogRW::logWriter('', '修改发布信息');
		} else {
			$id = $this->add($params);
			LogRW::logWriter('', '添加发布信息');
		}
		return $id;
	}
	
	public function delete($id){
		$db = $this->db;
		
		DBUtil::Del($db,$this->dbtable['sys_information_release'],"id='$id'");
		LogRW::logWriter('', '删除发布信息');
		return true;
	}
	
	public function listInformationRelease($params = array()){
		
		$search = $params['search'];
		$url = $params['url'];
		
		$sql['count'] = "SELECT COUNT(*) AS sum 
					FROM {$this->dbtable['sys_information_release']} 
					WHERE 1 $search ";
		$sql['data'] = "SELECT *
						FROM {$this->dbtable['sys_information_release']}
						WHERE 1 $search ORDER BY id DESC";
		try {
			$page = new listInformationRelease($url,$sql);
			$list = $page->getPageData();
		} catch (Exception $e) {
			echo $e->error_msg();
		}
		$result['count'] = $page->count; 
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		return $result;
	}
}

/**
 * 
 * 扩展分页类，过滤结果
 *
 */
class listInformationRelease extends Page {
	
	function resultFilter($result){
		$result['departments'] = Cache::cache_hr_department($result['departments']);
		if ($result['num'] == '2' && $result['touserid']!=''){
			$arr = $add = array();
			$arr = explode('|',$result['touserid']);
			foreach ($arr as $v){
				$rows = $this->db->get_one("SELECT username FROM {$this->dbtable['hr_information']} WHERE user='".$v."'");
				$add []= $rows['username'];
			}
			$result['touserid'] = implode('<br/>',$add);
		}
		
		return $result;
	}
}
?>