<?php
/**
 * 合同项目业务模块
 * @author Tom
 */
class ContractItem {

	private $db;
	private $dbtable;
    public $error;
	public function __construct(){
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	public function add($params = array()){
		$this->savebefor('',$params,'add');
		$db = $this->db;

		DBUtil::insert_tb($db, $this->dbtable['ht_contract_item'], $params);
		$id = $db->insert_id();
		$this->update($id,array('htxmcode'=>$id));

		return $id;
	}

	public function update($id, $params){
		$this->savebefor($id,$params,'update');
		$db = $this->db;
		DBUtil::update_tb($db, $this->dbtable['ht_contract_item'], $params, "id='".$id."'");
		return $id;
	}

	public function delete($id,$ht_id){
		$db = $this->db;
		$xm = $db->get_one("SELECT id FROM {$this->dbtable['xm_item']} WHERE htxm_id='{$id}' LIMIT 1");
		$rows = $db->get_one("SELECT online,iso FROM {$this->dbtable['ht_contract']} WHERE id='{$ht_id}'");
		$result = $this->query($id,array('iso','zuzhi_id'));

		if ($rows['online'] != '3' and $xm['id'] == '') {
			$iso = explode(',', $rows['iso']);
			$num = count($iso);
			if ($num > 1){
				$rows['iso'] = str_replace(",{$result['iso']}", '', $rows['iso']);
				$rows['iso'] = str_replace("{$result['iso']},", '', $rows['iso']);
				DBUtil::Update_tb($db, $this->dbtable['ht_contract'], array('iso' => $rows['iso']), "id='".$ht_id."'");
				DBUtil::Del($db, $this->dbtable['ht_contract_item'], "id='".$id."'");
			}else{
				DBUtil::Del($db, $this->dbtable['ht_contract_item'], "id='".$id."'");
			}

			LogRW::logWriter($result['zuzhi_id'], '合同项目内容删除');
			return true;
		} else {
			return false;
		}

	}

	public function query($id,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$result = $db->get_one("SELECT {$field} FROM {$this->dbtable['ht_contract_item']} WHERE id='{$id}' LIMIT 1");
		return $result;
	}

	public function toArray($where,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$sql = "SELECT {$field} FROM {$this->dbtable['ht_contract_item']} WHERE  {$where}";
		$query	= $db->query($sql);
		while($arr = $db->fetch_array($query)){
			if($arr['kind'] == '2'){
				$arr['certifiedproducts'] = Cache::cache_product($arr['product']);
				$arr['productstandards'] = Cache::cache_product_ver($arr['product_ver']);
				$arr['productpieces'] = Cache::cache_key_part($arr['key_part']);

				$rows = $db->get_one("SELECT eiregistername FROM {$this->dbtable['mk_company']} WHERE id='{$arr['manuid']}'");
				$arr['manufacturingunits'] = $rows['eiregistername'];
				//$arr['manufacturingaddress'] = $rows['eipro_address'];

				$rows = $db->get_one("SELECT eiregistername FROM {$this->dbtable['mk_company']} WHERE id='{$arr['proid']}'");
				$arr['productionunit'] = $rows['eiregistername'];
				//$arr['productionaddress'] = $rows['eipro_address'];
			}
			$result []= $arr;
		}

		return $result;
	}

	public function save($id,$params){
		if ((int)$id > 0){
			$id = $this->update($id,$params);
		}else{
			$id = $this->add($params);
		}
		return $id;
	}

	public function ContractItemSave($id,$params){
		$db = $this->db;
		$id = $this->update($id,$params);
		$xm_item = array(
			'audit_code' => $params['audit_code'], //审核代码
			'renzhengfanwei' => $params['renzhengfanwei'], //审批范围
		);
		DBUtil::update_tb($db, $this->dbtable['xm_item'], $xm_item, "htxm_id='$id' AND (audit_type='1005' OR audit_type='1007' OR audit_type='1008') AND (online='0' OR online='1' OR online='2')");
		return $id;
	}

	public function htBack($ht_id){
		$db = $this->db;

		$params = array(
			$this->dbtable['ht_repeat_contract'], //合同复评提示
			$this->dbtable['xm_item'], //审核项目总表
			$this->dbtable['zs_change'], //证书变更表
			$this->dbtable['zs_cert'], //证书表
			$this->dbtable['xm_rzlx'], //证书表
			$this->dbtable['ht_sampling'], //抽样表
			//$this->dbtable['cw_finance_item'], //财务收费
			$this->dbtable['cw_finance_list'], //财务收费明细
			$this->dbtable['cw_finance_list_ex'], //财务收费明细
			$this->dbtable['pd_evaluation_hr'], //评定人员表
			$this->dbtable['pd_xm'], //评定项目表
		);

		$sql = $db->query("SELECT taskId FROM {$this->dbtable['xm_item']} WHERE ht_id='{$ht_id}' and taskId!='0'");
		while($rows = $db->fetch_array($sql)){
			if($rows['taskId'] > 0){
				DBUtil::Del($db, $this->dbtable['pd_evaluation_files'], "taskId='".$rows['taskId']."'");
				DBUtil::Del($db, $this->dbtable['xm_auditor_plan'], "taskId='".$rows['taskId']."'");
				DBUtil::Del($db, $this->dbtable['xm_auditor'], "taskId='".$rows['taskId']."'");
				DBUtil::Del($db, $this->dbtable['xm_task'], "id='".$rows['taskId']."'");
			}
		}

		foreach ($params as $v){
			DBUtil::Del($db, $v, "ht_id='".$ht_id."' $temp_sql ");
		}

		DBUtil::update_tb($db, $this->dbtable['ht_contract_item'], array('zsid' => '0','online' => '2'), "ht_id='".$ht_id."'");
		DBUtil::update_tb($db, $this->dbtable['ht_contract'], array('online' => '2'), "id='".$ht_id."'");
	}

	public function htApp($zuzhi_id,$ht_id){
		$db = $this->db;
		$value = array(
			'online' => '3',
			'sh_ren' => $_SESSION['username'],
			'sh_time' => date('Y-m-d')
		);

		DBUtil::update_tb($db, $this->dbtable['ht_contract_item'], array('online'=>'3'), "ht_id='".$ht_id."'");
		DBUtil::update_tb($db, $this->dbtable['ht_contract'], $value, "id='".$ht_id."'");
		LogRW::logWriter($zuzhi_id, '合同审批');
	}

	public function listElement($params = array()){

		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM {$this->dbtable['ht_contract_item']}
							WHERE 1 $search";
		$sql['data'] = "SELECT  *
					 		FROM {$this->dbtable['ht_contract_item']}
								WHERE 1  $search ORDER BY id DESC";

		$page = new listContractItem($url, $sql);
		$list = $page->getPageData();


		$result['pages'] = $page->nav;
		$result['data'] = $list;
		$result['count'] = $page->count;
		return $result;
	}
	public function savebefor($htxm_id,$value=array(),$func){
		$db = $this->db;

		switch($func){
			case 'add':

			break;
			case 'update':
				if($value['audit_code'] != ''){
					$audit_code_temp = explode('；',$value['audit_code']);
					foreach($audit_code_temp as $k=>$v){
						if($v == ''){
							unset($audit_code_temp[$k]);
						}
					}
					$audit_code_temp = implode("','",$audit_code_temp);
					$dm_q = $db->query("SELECT code,online FROM {$this->dbtable['setup_audit_code']} WHERE code IN('$audit_code_temp')");
					while($dm = $db->fetch_array($dm_q)){
						if($dm['online'] == '0'){
							$error []= $dm['code'].'代码已停用，请重新选择';
						}
					}
				}
			break;
		}
		if(!empty($error)){
		Error::ShowError($error);
		}
	}
}


class listContractItem extends Page {

	protected function resultFilter($result) {
		$db = $this->db;

		$Contract = new Contract();
		$rows = $Contract->query($result['ht_id']);
		$result['htdate'] = $rows['htdate'];
		$result['zd_ren'] = $rows['zd_ren'];
		$result['other'] = $rows['other'];
		$result['ps_other'] = $rows['ps_other'];
		$result['htonline'] = Cache::cache_ht_online($rows['online']);
		$result['audit_code'] = str_replace('；', '<br/>', $result['audit_code']);
		return $result;
	}
}
?>