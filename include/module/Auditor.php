<?php

/**
 * 任务计划中安排审核人员
 * @author Tom
 *
 */
class Auditor {
	private $db;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	public function add($params) {
		$this->savebefore();
		$db = $this->db;

		DBUtil::insert_tb($db, $this->dbtable['xm_auditor'], $params);
		$id = $db->insert_id();
		return $id;
	}

	public function query($id,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$result = $db->get_one("SELECT {$field} FROM `{$this->dbtable['xm_auditor']}` WHERE id='{$id}' LIMIT 1");
		if ($result['id'] != ''){
			$AuditorPlanId = array();$iso = array();$isLeader = array();$role = array();$witness = array();$qualification = array();$audit_code = array();$isorole = array();$isoqualification = array();$isoaudit_code = array();$isoisLeader = array();
			$sql = "SELECT * FROM `{$this->dbtable['xm_auditor_plan']}` WHERE auditorId='{$result['id']}'";
			$query = $db->query($sql);
			while ($row = $db->fetch_array($query)) {
				$AuditorPlanId []= $row['id'];
				$iso []= $row['iso'];
				$isLeader []= $row['isLeader'];
				$role []= $row['role'];
				$witness  []= $row['witness'];
				$qualification []= $row['qualification'];
				$isoisLeader []= $row['iso'].":".Cache::cache_iswhether($row['isLeader']);
				$isorole []= $row['iso'].":".$row['role'];
				$isoqualification []= $row['iso'].":".Cache::cache_hr_reg_qualification($row['qualification']);
				if ($row['audit_code'] != '') {
					$audit_code []= $row['audit_code'];
					$isoaudit_code []= $row['iso'].":".$row['audit_code'];
				}
			}
			$result['auditorPlanId'] = implode(',', $AuditorPlanId);
			$result['iso'] = implode(',', $iso);
			$result['isLeader'] = implode(',', $isLeader);
			$result['role'] = implode(',', $role);
			$result['witness'] = implode(',', $witness);
			$result['audit_code'] = implode(',', $audit_code);
			$result['qualification'] = implode(',', $qualification);
			$result['isoisLeader'] = implode(',', $isoisLeader);
			$result['isoqualification'] = implode(',', $isoqualification);
			$result['isoaudit_code'] = implode(',', $isoaudit_code);
		}

		return $result;
	}

	public function toArray($where,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$sql = "SELECT {$field} FROM `{$this->dbtable['xm_auditor']}` WHERE {$where}";
		$query = $db->query($sql);
		while ($rows = $db->fetch_array($query)) {
			if ($rows['id'] != ''){
				$AuditorPlanId = array();$iso = array();$isLeader = array();$role = array();$witness = array();$qualification = array();$audit_code = array();$isorole = array();$isoqualification = array();$isoaudit_code = array();$isoisLeader = array();$evaluate = array();
				$sql2 = "SELECT * FROM `{$this->dbtable['xm_auditor_plan']}` WHERE auditorId='{$rows['id']}'";
				$query2 = $db->query($sql2);
				while ($row = $db->fetch_array($query2)) {
					$AuditorPlanId []= $row['id'];
					$iso []= $row['iso'];
					$isLeader []= $row['isLeader'];
					$role []= $row['role'];
					$witness  []= $row['witness'];
					$evaluate  []= $row['evaluate'];
					$qualification []= $row['qualification'];
					$isoisLeader []= $row['iso'].":".Cache::cache_iswhether($row['isLeader']);
					$isorole []= $row['iso'].":".$row['role'];
					$isoqualification []= $row['iso'].":".Cache::cache_hr_reg_qualification($row['qualification']);
					$audit_code []= $row['audit_code'];
					if ($row['audit_code'] != '') {
						$isoaudit_code []= $row['iso'].":".$row['audit_code'];
					}
				}
				$rows['auditorPlanId'] = implode(',', $AuditorPlanId);
				$rows['iso'] = implode(',', $iso);
				$rows['isLeader'] = implode(',', $isLeader);
				$rows['role'] = implode(',', $role);
				$rows['witness'] = implode(',', $witness);
				$rows['evaluate'] = implode(',', $evaluate);
				$rows['audit_code'] = implode(',', $audit_code);
				$rows['qualification'] = implode(',', $qualification);
				$rows['isoisLeader'] = implode(',', $isoisLeader);
				$rows['isoqualification'] = implode(',', $isoqualification);
				$rows['isoaudit_code'] = implode(',', $isoaudit_code);
			}
			$result []= $rows;
		}

		return $result;
	}

	public function update($id,$params) {
		$this->savebefore();
		$db = $this->db;

		DBUtil::update_tb($db, $this->dbtable['xm_auditor'], $params, "id={$id}");
		return $id;
	}

	public function save($id,$params) {
		if ((int)$id > 0) {
			$auditor = $this->update($id,$params);
		} elseif ((int)$id == 0) {
			$auditor = $this->add($params);
		}

		return $auditor;
	}

	public function delete($id) {
		$this->savebefore();
		$db = $this->db;

		$rows = $this->query($id ,array('taskId'));
		$rows = $db->get_one("SELECT xmonline FROM {$this->dbtable['xm_task']} WHERE id='{$rows['taskId']}'");
		if ($rows['xmonline'] == '3'){
			return false;
		}else{
			DBUtil::Del($db, $this->dbtable['xm_auditor'], "id='{$id}'");
			DBUtil::Del($db, $this->dbtable['xm_auditor_plan'], "auditorId='{$id}'");
			return true;
		}
	}

	public function listAuditor($params = array()) {

		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['xm_auditor']}`
							WHERE 1 $search ";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['xm_auditor']}`
						WHERE 1 $search ORDER BY taskEndDate DESC,taskId DESC";
		$page = new listAuditor($url, $sql);
		$list = $page->getPageData();
		$result['count'] = $page->count;
		$result['pages'] = $page->nav;
		$result['data'] = $list;

		return $result;
	}

	public function savebefore(){
	}
	/*
	 * 劳务费发放
	 */
	public function approval($auditorId,$params = array()){
		switch ($params['op']) {
			case '1' :
				$params = array('online'=> '1','paydate'=>$params['paydate']);
				foreach ($auditorId as $v){
					$this->update($v, $params);
				}
				LogRW::logWriter($zuzhi_id,'劳务费发放审批');
				break;
			case '2' :
				$params = array('online'=> '0','paydate'=>$params['paydate']);
				foreach ($auditorId as $v){
					$this->update($v, $params);
				}
				LogRW::logWriter($zuzhi_id,'劳务费撤销审批');
				break;
		}
	}

}

/**
 *
 * 扩展分页类，过滤结果，追加列
 * @author Tom
 *
 */
class listAuditor extends Page {

	protected function resultFilter($result) {
		$db = $this->db;
		$result['taskBeginHalfDate'] = Cache::cache_time_online($result['taskBeginHalfDate']);
		$result['taskEndHalfDate'] = Cache::cache_time_online($result['taskEndHalfDate']);
		$result['online'] = Cache::cache_labor_costs_online($result['online']);
		$result['zd_time'] = Cache::cache_time_value($result['zd_time']);
		$result['paydate'] = Cache::cache_time_value($result['paydate']);
		if($result['taskId'] != 0){
			$Item = new Item();
			$arr = $Item->toArray("taskId='{$result['taskId']}'",array('id','audit_ver','iso','audit_type','htxm_id','describes'));
			foreach ($arr as $v){
				$audit_ver []= $v['audit_ver'];
				$audit_type []= $v['audit_ver']."：".$v['audit_type'];
				$htxm_id_t []= $v['htxm_id'];
				$describes []= $v['describes'];
				//查评定结果
				$pd = $db->get_one("SELECT zs_if,approvaldate FROM pd_xm WHERE xmid='$v[id]'");
				switch($pd['zs_if']){
					case '0' : $pd['zs_if'] = '未评定';break;
					case '1' : $pd['zs_if'] = '<font color=green>通过</font>'; 
							   $pd['approvaldate'] != '0000-00-00' && $result['approvaldate'] = $pd['approvaldate'];
							   break;
					case '2' : $pd['zs_if'] = '<font color=orange>待定</font>';break;
					case '3' : $pd['zs_if'] = '<font color=red>不通过</font>';break;
					default:$pd['zs_if']='';
				}
				$zs_if []= $v['audit_ver'].":".$pd['zs_if'];
			}
			$result['audit_ver'] = implode(',', $audit_ver);
			$result['audit_type'] = implode('<br/>', $audit_type);
			$result['describes'] = implode(',', array_unique($describes));

			$result['zs_if'] = implode('<br/>', $zs_if);
			$htxm_id_t = implode("','",$htxm_id_t);
			$htxmcode = array();
			$htxm_q = $db->query("SELECT htxmcode,kind FROM ht_contract_item WHERE id IN('$htxm_id_t')");
			$num = 0;
			while($htxm_t = $db->fetch_array($htxm_q)){
				$htxmcode []= $htxm_t['htxmcode'];
				if($htxm_t['kind'] == '2')
				{
					$num = $num + 1;
				}
			}
			$result['htxmcode'] = implode('<br/>', $htxmcode);
			$result['cp'] = $num;

			$rw = $db->get_one("SELECT jinxianchang,xmonline FROM `xm_task` WHERE `id` ='{$result['taskId']}'");
			$result['jinxianchang'] = $rw['jinxianchang']=='0' ? '否' : '是';
			$result['xmonline'] = Cache::cache_item_online($rw['xmonline']);
		}


		$Auditor = new Auditor();
		$rows = $Auditor->query($result['id']);
		$result['iso'] = $rows['iso'];
		$result['isLeader'] = $rows['isLeader'];
		$result['role'] = $rows['role'];
		$result['witness'] = $rows['witness'];
		$result['audit_code'] = $rows['audit_code'];
		$result['qualification'] = $rows['qualification'];
		$result['isoisLeader'] = $rows['isoisLeader'];
		$result['isoqualification'] = $rows['isoqualification'];
		$result['isoaudit_code'] = $rows['isoaudit_code'];

		return $result;
	}
	public function savebefore(){
	}
}
?>