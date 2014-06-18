<?php

/**
 * 审核任务回访记录
 * @2011-05-4
 */

class XmTaskReturnRecord {
	private $fields;
	public $db;
	public $error;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	public function list_return_record($params = array()){

		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum	FROM {$this->dbtable['xm_task_return_record']} WHERE 1 $search";
		$sql['data'] = "SELECT * FROM {$this->dbtable['xm_task_return_record']} WHERE 1 $search ORDER BY id DESC";

		$page = new listReturn_record($url, $sql);
		$list = $page->getPageData();
		$result['pages']	= $page->nav;
		$result['data']		= $list;
		$result['count']	= $page->count;
		return $result;
	}

	public function query($id,$value = array()){
		$db = $this->db;

		$value == array() ? $field = '*' : $field = implode(',',$value);
		$result = $db->get_one("SELECT {$field} FROM `{$this->dbtable['xm_task_return_record']}` WHERE id='{$id}'");
		return $result;
	}

	public function toArray($where,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$sql = "SELECT {$field} FROM {$this->dbtable['xm_task_return_record']} WHERE  {$where}";
		$query	= $db->query($sql);
		while($arr = $db->fetch_array($query)){
			$result []= $arr;
		}

		return $result;
	}

	public function add($value){
		$db = $this->db;
		
		DBUtil::insert_tb($db,$this->dbtable['xm_task_return_record'],$value);
		
		$id = $db->insert_id();
		LogRW::logWriter($id,'新增-'.Cache::cache_company($value['zuzhi_id']).'-审核任务回访记录 ID='.$id);
	}
	
	public function update($id,$params = array()){
		$db = $this->db;

		DBUtil::update_tb($db, $this->dbtable['xm_task_return_record'], $params, "id='".$id."'");
		
		return $id;
		
		LogRW::logWriter($id,'修改-'.Cache::cache_company($params['zuzhi_id']).'-审核任务回访记录 ID='.$id);
	}

	public function del($id){
		$db = $this->db;
		$rows = $db->get_one("SELECT zuzhi_id FROM `{$this->dbtable['xm_task_return_record']}` WHERE id='{$id}'"); 
		DBUtil::Del($db,$this->dbtable['xm_task_return_record'],"id='$id'");
		
		return $id;
		
		LogRW::logWriter($id,'删除-'.Cache::cache_company($rows['zuzhi_id']).'-审核任务回访记录 ID='.$id);
	}

	public function savebefore($func,$value){
	 	
	}
}
/**
 *
 * 扩展分页类，过滤结果
 *
 *
 */
class listReturn_record extends Page {
	
	protected function resultFilter($result) {
		$db = $this->db;
		
		$Task = new Task();
		$task_rows = $Task->query($result['taskId']);
		$result['htfrom'] = Cache::cache_htfrom($task_rows['htfrom']);
		$result['xmonline'] = Cache::cache_item_online($task_rows['xmonline']);
		$result['zrd'] = $task_rows['zrd'];
		$result['taskBeginDate'] = $task_rows['taskBeginDate'];
		$result['taskEndDate'] = $task_rows['taskEndDate'];
		
		$Item = new Item();
		$rows = $Item->toArray("taskId='{$result['taskId']}'",array('htxm_id','zs_if','iso','audit_ver','audit_type'));
		foreach ($rows as $v) {
			if($v['zs_if'] == '') {$v['zs_if'] = '无';}
			$zs_if []= $v['iso'].":".$v['zs_if'];
			$audit_ver []= $v['audit_ver'];
			$audit_type []= $v['iso'].":".$v['audit_type'];
			$htxm_id_t []= $v['htxm_id'];
		}
		$result['zs_if'] = implode('<br/>', $zs_if);
		$result['audit_ver'] = implode('<br/>', $audit_ver);
		$result['audit_type'] = implode('<br/>', $audit_type);
		$htxm_id_t = implode("','",$htxm_id_t);
		$htxm_q = $db->query("SELECT htxmcode FROM ht_contract_item WHERE id IN('$htxm_id_t')");
		while($htxm_t = $db->fetch_array($htxm_q)){
			$htxmcode []= $htxm_t['htxmcode'];
		}
		$result['htxmcode'] = implode('<br/>', $htxmcode);

		//if ($result['online'] == '1'){
			$empName = array();
			$Auditor = new Auditor();
			$arr = $Auditor->toArray("taskId='{$result['taskId']}'",array('id','empName'));
			foreach ($arr as $v){
				$empName []= $v['empName'].'('.$v['isoqualification'].')('.$v['isoisLeader'].')';
			}
			$result['inempName'] = implode('<br>', $empName);
		//}
				
		return $result;
	}
	
}

?>