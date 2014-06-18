<?php
/**
 * 任务计划
 * @author Tom
 *
 */
class Task {

	private $db;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	public function add($params){
		$db = $this->db;
		//取htfrom
		$qy = $db->get_one("SELECT htfrom FROM {$this->dbtable['mk_company']} WHERE id='$params[zuzhi_id]'");
		$params['htfrom'] = $qy['htfrom'];
		DBUtil::insert_tb($db, $this->dbtable['xm_task'], $params);
		$id = $db->insert_id();
		return $id;
	}

	public function delete($taskId){
		$db = $this->db;
		DBUtil::update_tb($db, $this->dbtable['xm_item'], array('online'=>'0','taskId'=>'0'), "taskId='{$taskId}'");
		DBUtil::Del($db, $this->dbtable['xm_task'], "id='".$taskId."'");
		DBUtil::Del($db, $this->dbtable['xm_auditor'], "taskId='".$taskId."'");
		DBUtil::Del($db, $this->dbtable['xm_auditor_plan'], "taskId='".$taskId."'");
		return true;
	}

	public function update($id, $params){
		$db = $this->db;

		DBUtil::update_tb($db, $this->dbtable['xm_task'], $params, "id='{$id}'");
		if($params['taskBeginDate']!='' and $params['taskBeginHalfDate']!='' and $params['taskEndDate']!='' and $params['taskEndHalfDate']!=''){
			$value = array(
				'taskBeginDate' => $params['taskBeginDate'],
				'taskBeginHalfDate' => $params['taskBeginHalfDate'],
				'taskEndDate' => $params['taskEndDate'],
				'taskEndHalfDate' => $params['taskEndHalfDate'],
				'actualtaskBeginDate' => $params['taskBeginDate'],
				'actualtaskBeginHalfDate' => $params['taskBeginHalfDate'],
				'actualtaskEndDate' => $params['taskEndDate'],
				'actualtaskEndHalfDate' => $params['taskEndHalfDate']
			);
			DBUtil::update_tb($db, $this->dbtable['xm_item'], $value, "taskId='{$id}'");
			
			$value = array(
				'taskBeginDate' => $params['taskBeginDate'],
				'taskBeginHalfDate' => $params['taskBeginHalfDate'],
				'taskEndDate' => $params['taskEndDate'],
				'taskEndHalfDate' => $params['taskEndHalfDate'],
			);
			DBUtil::update_tb($db, $this->dbtable['xm_auditor'], $value, "taskId='{$id}'");			
		}
		return $id;
	}

	public function query($id,$params = array()){
		$db = $this->db;

		$params == array() ? $feild= '*' : $feild = implode(',',$params);
		$result = $db->get_one("SELECT {$feild} FROM {$this->dbtable['xm_task']} WHERE id='{$id}' LIMIT 1");

		return $result;
	}

	public function save($id,$params){
		if ((int)$id > 0) {
			$taskId = $this->update($id,$params);
			LogRW::logWriter($params['zuzhi_id'], '审核开始结束日期安排修改');
		} elseif ((int)$id == 0) {
			$params['xmonline'] = '1';
			$taskId = $this->add($params);
			LogRW::logWriter($params['zuzhi_id'], '审核开始结束日期安排增加');
		}
		return $taskId;
	}

	/*
	 * 项目审批
	 */
	public function approval(array $params,$sp_date){
		$db = $this->db;

		foreach ($params as $v){
			$value = array('xmonline' => '3','sp_date'=>$sp_date,'sp_ren'=>$_SESSION['username']);
			$this->update($v,$value);
			$value = array('online' => '3');
			DBUtil::update_tb($db, $this->dbtable['xm_item'], $value, "taskId='{$v}'");
		}
	}

	/*
	 * 项目撤销审批
	 */
	public function approval_back($taskId){
		$db = $this->db;

		$value = array('xmonline' => '2');
		$this->update($taskId,$value);
		$value = array('online' => '2');
		DBUtil::update_tb($db, $this->dbtable['xm_item'], $value, "taskId='{$taskId}'");
	}


	public function listTask($params,$content = 1){

		$search = $params['search'];
		$url = $params['url'];

		if ($content == 1){
			$sql['count'] = "SELECT COUNT(*) AS sum
								FROM `{$this->dbtable['xm_task']}`
								WHERE 1 $search ";
			$sql['data'] = "SELECT *
						 	FROM `{$this->dbtable['xm_task']}`
							WHERE 1 $search ORDER BY id DESC";
		}else{
			$sql['count'] = "SELECT COUNT(distinct(a.id)) AS sum
							FROM `{$this->dbtable['xm_task']}` a LEFT JOIN `{$this->dbtable['xm_item']}`
							b ON a.id=b.taskId WHERE 1 $search ";
			$sql['data'] = "SELECT distinct(a.id),a.*
					 		FROM `{$this->dbtable['xm_task']}` a LEFT JOIN `{$this->dbtable['xm_item']}`
							b ON a.id=b.taskId WHERE 1 $search ORDER BY id DESC";
		}

		$page = new listTask($url, $sql);
		$list = $page->getPageData();
		$result['count'] = $page->count;
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		return $result;
	}

}

class listTask extends Page {

	protected function resultFilter($result) {
		$db = $this->db;
		$result['jinxianchang'] = Cache::cache_iswhether($result['jinxianchang']);
		$result['status'] = Cache::cache_task_online($result['online']);
    	$result['taskBeginHalfDate'] = Cache::cache_time_online($result['taskBeginHalfDate']);
    	$result['taskEndHalfDate'] = Cache::cache_time_online($result['taskEndHalfDate']);
    	$result['xmonline'] = Cache::cache_item_online($result['xmonline']);
		$zs_if = $audit_ver = $audit_type = $htxm_id_t = $htxmcode = array();
		$Item = new Item();
		$rows = $Item->toArray("taskId='{$result['id']}'",array('htxm_id','zs_if','iso','audit_ver','audit_type'));
		foreach ($rows as $v) {
			if($v['zs_if'] == '') {$v['zs_if'] = '无';}
			$zs_if []= $v['iso'].":".$v['zs_if'];
			$audit_ver []= Cache::cache_audit_ver($v['audit_ver']);
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
			$arr = $Auditor->toArray("taskId='{$result['id']}'",array('id','empName'));
			foreach ($arr as $v){
				$empName []= $v['empName'].'('.$v['isoqualification'].')('.$v['isoisLeader'].')';
			}
			$result['inempName'] = implode('<br>', $empName);
		//}
		
		$getRecord = $db->get_one("SELECT if_record FROM xm_task_return_record WHERE taskId='$result[id]' LIMIT 1");
		$result['if_record'] = $getRecord['if_record'];
		
		return $result;
	}
}
?>