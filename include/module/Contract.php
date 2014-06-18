<?php
/**
 * 合同业务模块
 * @author Tom
 *
 */
class Contract {

	private $db;
	public $error;
	public $dbtable;

	public function __construct(){
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}
	public function savebefor($ht_id,$value=array(),$func)
	{
		$db = $this->db;
		switch($func){
			case 'add':
				$rows = $db->get_one("SELECT  id FROM {$this->dbtable['ht_contract']} WHERE htcode ='".$value['htcode']."'");
				if($rows['id'] != ''){
					//$error []= '合同编号已存在';
				}
			break;
			case 'update':
				$rows = $db->get_one("SELECT id FROM {$this->dbtable['ht_contract']} WHERE htcode ='".$value['htcode']."' and id !=".$ht_id);
				if($rows['id'] != ''){
					//$error []= '合同编号已存在';
				}
			break;
			case 'del':
				$rows = $db->get_one("SELECT online FROM {$this->dbtable['ht_contract']} WHERE id =".$ht_id);
				if($rows['online'] == 3){
				$error []= '需撤销合同审批才可删除';
			  }
			break;
		}
		if(!empty($error)){
		Error::ShowError($error);
		}
	}

	public function add($params = array()){
		$this->savebefor('',$params,'add');
		$db = $this->db;
//		$params['htcode'] = $this->Htcode($params['zuzhi_id']);
		DBUtil::insert_tb($db, $this->dbtable['ht_contract'], $params);
		$id = $db->insert_id();
		$this->update($id,array('htcode'=>$id));

		return $id;
	}

	public function update($id, $params){
		$db = $this->db;
        $this->savebefor($id,$params,'update');
		DBUtil::update_tb($db, $this->dbtable['ht_contract'], $params, "id='".$id."'");

		return $id;
	}

	public function query($id,$params = array()){

		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$reuslt = $db->get_one("SELECT {$field} FROM {$this->dbtable['ht_contract']} WHERE id='".$id."'");

		return $reuslt;
	}

	public function save($id,$params = array()){

		if ((int)$id > 0) {
			unset($params['zd_ren']);
			unset($params['zd_time']);
			$id = $this->update($id,$params);
			LogRW::logWriter($params['zuzhi_id'], '合同内容修改');
		} else {
			$id = $this->add($params);
			LogRW::logWriter($params['zuzhi_id'], '合同内容添加');
		}
		return $id;
	}


	public function del($id){
		$db = $this->db;
		$this->savebefor($id,'','del');
		DBUtil::Del($db, $this->dbtable['ht_contract'], "id='{$id}'");
		//找变更任务ID
		$cg_q = $db->get_one("SELECT cg_task_id FROM {$this->dbtable['zs_change']} WHERE ht_id='$id'");
		DBUtil::Del($db,$this->dbtable['zs_change_task'],"id = '$cg_q[cg_task_id]'");
		//找关联任务ID
		$rw_q = $db->query("SELECT taskId FROM {$this->dbtable['xm_item']} WHERE ht_id='$id'");
		while($rw = $db->fetch_array($rw_q)){
			$rw['taskId']!='0' && $taskId []= $rw['taskId'];
		}
		$taskId = implode("','",$taskId);
		$table = array(
			$this->dbtable['ht_contract_item'], //关联的合同项目
			$this->dbtable['ht_repeat_contract'], //合同复评提示
			$this->dbtable['xm_item'], //审核项目总表
			$this->dbtable['zs_change'], //证书变更表
			$this->dbtable['zs_cert'], //证书表
			$this->dbtable['ht_sampling'], //抽样表
			$this->dbtable['cw_finance_item'], //财务收费
			$this->dbtable['cw_finance_list'], //财务收费明细
			$this->dbtable['cw_finance_list_ex'], //财务收费明细
			$this->dbtable['pd_evaluation_hr'] //评定人员表
		);

		DBUtil::Del($db,$table,"ht_id='$id'");
		//删于任务关联表数据
		$table = array(
			$this->dbtable['xm_auditor'], //审核员审核计划表
			$this->dbtable['xm_auditor_plan'], //审核员派人项目分配表
			$this->dbtable['xm_task'], //审核任务表
			$this->dbtable['pd_xm'] //审核任务表
			);
		if($taskId>0){
			DBUtil::Del($db,$this->dbtable['pd_evaluation_files'],"taskId IN('$taskId')");
			DBUtil::Del($db,$this->dbtable['xm_auditor'],"taskId IN('$taskId')");
			DBUtil::Del($db,$this->dbtable['xm_auditor_plan'],"taskId IN('$taskId')");
			DBUtil::Del($db,$this->dbtable['xm_task'],"id IN('$taskId')");
		}

		return true;
	}

	/*
	 * 默认生成合同编号
	 */
	public function Htcode ($zuzhi_id){
		$rows = $this->db->get_one("SELECT htcode FROM {$this->dbtable['ht_contract']} WHERE zuzhi_id='{$zuzhi_id}' ORDER BY id DESC LIMIT 1");
		if ($rows['htcode'] != ''){
			$arr = explode('-',$rows['htcode']);
			$htcode = '1'.$arr['1'] + 1;
			$result	= $arr['0']."-".substr($htcode,1,2);
		}else{
			$rows = $this->db->get_one("SELECT eientercode FROM {$this->dbtable['mk_company']} WHERE id='{$zuzhi_id}' LIMIT 1");
			$result = $rows['eientercode'].'-01';
		}

		return $result;
	}

	public function listElement($params = array()){

		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(*) AS sum
					FROM {$this->dbtable['ht_contract']}
					WHERE 1 $search ";
		$sql['data'] = "SELECT *
						FROM {$this->dbtable['ht_contract']}
						WHERE 1 $search ORDER BY id DESC";
		$page = new listContract($url,$sql);
		$list = $page->getPageData();

		$result['count'] = $page->count;
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		return $result;
	}


}

/**
 *
 * 扩展分页类，过滤结果
 * @author Tom
 *
 */
class listContract extends Page {

	function resultFilter($result){
		$ContractItem = new ContractItem();

		$rows = $ContractItem->toArray("ht_id='".$result['id']."'");
		if ($result['first'] == '1') {$result['first'] = '是';}else{$result['first']='否';}
		$daima = array();$shenhe = array();$htxmcode = array();
		$rows == '' ? $rows = array(0) : $rows;
		$result['product_num']=0;
		foreach ($rows as $v) {
			$htxmcode []= $v['htxmcode'];
			$daima []= $v['iso']."：".$v['audit_code'];
			$shenhe []= $v['iso']."：".Cache::cache_audit_type($v['audit_type']);
			if($v['kind']==2){$result['product_num'] +=1;}
		}
		$result['product_num']==0 ?$result['product_num']='':$result['product_num'];
		$result['htxmcode'] = implode('<br/>', $htxmcode);
		$result['audit_code'] = implode('<br/>', $daima);
		$result['audit_code'] = str_replace('；', '<br/>&nbsp;&nbsp;&nbsp;', $result['audit_code']);
		$result['audit_type'] = implode('<br/>', $shenhe);

		return $result;
	}
}
?>