<?php
/**
 * 变更
 */
class Change {
	private $db;
	private $dbtable;
	private $zsid;
	private $xmid;
	private $new_cg_id=array();
	public $zs;
	public $value; //获取POST数据
	public $params; //需更新数据
	public $error=array();
	public function __construct(){
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	//获取关联基本信息
	public function GetBaseMsg($zsid,$value){
		$value['audit_code'] = str_replace('、','；',str_replace('\\','；',str_replace('/','；',str_replace(',','；',str_replace('，','；',str_replace(';','；',$value['audit_code']))))));
		$this->value = $value;
		if((int)$zsid > 0){
			$zs = $this->db->get_one("SELECT * FROM {$this->dbtable['zs_cert']} WHERE id='{$zsid}'");
			$this->zs = $zs;
			//取最后一次审核项目的ID,仅限监督审核
			$xm = $this->db->get_one("SELECT id FROM {$this->dbtable['xm_item']}
			WHERE htxm_id='{$this->zs[htxm_id]}' AND (audit_type='1002' OR audit_type='1003' OR audit_type='1004') ORDER BY id DESC LIMIT 1");
			if($xm['id']==''){
				$this->error []= '缺少监督数据，无法执行变更';
			}
			$this->xmid = $xm['id'];
			if($value['cg_task_id'] == ''){
				$params = array(
					'xmid' => $this->xmid,
					'base_xmid' => $this->xmid,
					'htxm_id' => $this->zs['htxm_id'],
					'ht_id' => $this->zs['ht_id'],
					'zuzhi_id' => $this->zs['zuzhi_id'],
					'zsid' => $this->zs['id'],
					'iso' => $this->zs['iso'],
					'htfrom' => $this->zs['htfrom'],
					'product'=>$this->zs['product'],
					//'product_ver'=>$this->zs['product_ver'],
					'audit_ver' => $this->zs['audit_ver'],
					'changedate_s' => $this->value['zs_change_date'], //默认变更时间
					'changedate_e' => $this->value['zs_change_date'], //默认变更时间
					'zs_change_date' => $this->value['zs_change_date'],
					'creatitem' => $this->value['creatitem'],
					'other' => $this->value['other'],
					'cw_other' => $this->value['cw_other'],
					'zd_ren' => $_SESSION['username'],
					'zd_date' => date("Y-m-d")
				);
				$this->params = $params;
			}else{
				$params = array(
					'changedate_s' => $this->value['zs_change_date'], //默认变更时间
					'changedate_e' => $this->value['zs_change_date'], //默认变更时间
					'zs_change_date' => $this->value['zs_change_date'],
					'creatitem' => $this->value['creatitem'],
					'other' => $this->value['other'],
					'cw_other' => $this->value['cw_other']
				);
				$this->params = $params;
			}
		}else{
			exit('错误的证书ID');
		}
	}
	//保存变更
	public function ChangeSave($cg_task_id){
		foreach((array)$this->value['changeitem'] as $v){
			$last_cg = $this->db->get_one("SELECT id FROM {$this->dbtable['zs_change']} WHERE zsid='{$this->params['zsid']}' AND changeitem='$v' AND sp_online='0' LIMIT 1");
			if($last_cg['id'] != ''){
				$this->error []= '请先处理未审批的变更，类别为：'.Cache::cache_changeitem($v).'，ID：'.$last_cg['id'];
			}
		}
		$this->savebefor();
		foreach((array)$this->value['changeitem'] as $v){
			$field = '';
			$this->params['changeitem'] = $v;
			switch ($v) {
				case '01': $this->params['changekind'] = '1';$field='eiregistername';break;
				case '0201': $this->params['changekind'] = '1';$field='eireg_address';break;
				case '0202': $this->params['changekind'] = '1';$field='eisc_address';break;
				case '0203': $this->params['changekind'] = '1';$field='eipro_address';break;
				case '0204': $this->params['changekind'] = '1';$field='eiman_amount';break;
				case '0205': $this->params['changekind'] = '5';$field='iso_people_num';break;
				case '0206': $this->params['changekind'] = '1';$field='eifaren';break;
				case '0207': $this->params['changekind'] = '1';$field='eikind';break;
				case '0208': $this->params['changekind'] = '1';$field='zs_address';break;
				case '03_1': $this->params['changekind'] = '2';$field='audit_ver';break;
				case '06_1': $this->params['changekind'] = '2';$field='audit_code';break;
				case '06_2': $this->params['changekind'] = '2';$field='renzhengfanwei';
					$this->params['changerange'] = $this->value['changerange'];break;
				case '03': $this->params['changekind'] = '3';
					$this->params['changereason'] = $this->value['zt_changereason'];$this->params['zs_zanting_edate'] = $this->value['zs_zanting_edate'];break;
				case '04': $this->params['changekind'] = '3';break;
				case '05': $this->params['changekind'] = '3';
					$this->params['changereason'] = $this->value['zt_changereason'];break;
				case '0501': $this->params['changekind'] = '3';break;
				case '06': $this->params['changekind'] = '3';break;
				case '0602': $this->params['changekind'] = '3';break;
				case '0603': $this->params['changekind'] = '3';break;
				case '09': $this->params['changekind'] = '3';break;
				case '10': $this->params['changekind'] = '4';break;
				case '1001': $this->params['changekind'] = '4';break;
				default : exit('错误的变更类型');
			}
			switch($this->params['changekind']){
				case '1' :
					$com = $this->db->get_one("SELECT $field FROM {$this->dbtable['mk_company']} WHERE id='{$this->value[zuzhi_id]}'");
					$this->params['change_bf'] = $com[$field];
					$this->params['change_af'] = $this->value[$field];
					break;
				case '2' :
					$xm = $this->db->get_one("SELECT $field FROM {$this->dbtable['xm_item']} WHERE id='{$this->xmid}'");
					$this->params['change_bf'] = $xm[$field];
					$this->params['change_af'] = $this->value[$field];
					break;
				case '3' :
					$this->params['change_bf'] = $this->zs['online'];
					$this->params['change_af'] = $this->params['changeitem'];
					break;
				case '4' :
					$this->params['change_bf'] = $this->zs['online'];
					$this->params['change_af'] = $this->params['changeitem'];
					break;
				case '5' :
					$htxm = $this->db->get_one("SELECT $field FROM {$this->dbtable['ht_contract_item']} WHERE id='{$this->zs[htxm_id]}'");
					$this->params['change_bf'] = $this->zs['online'];
					$this->params['change_af'] = $this->params['changeitem'];
					break;
			}
			//echo"<pre>";print_r($this->params);echo"</pre>";
			if((int)$cg_task_id > 0){
				$this->EditChange($cg_task_id);
			}else{
				$this->AddChange();
			}
		}
	}
	//新增变更
	public function AddChange(){
		$db = $this->db;
		$params['online'] = '0';//是否默认所有变更发生即有效
		DBUtil::insert_tb($db, $this->dbtable['zs_change'], $this->params);
		$new_cg_id = $db->insert_id();
		$this->new_cg_id []= $new_cg_id;
		LogRW::logWriter($this->zs['zuzhi_id'],$this->zs['iso'].'：新增变更');
	}

	//修改变更
	public function EditChange($cg_task_id){
		$db = $this->db;
		$cgid_q = $db->query("SELECT id FROM {$this->dbtable['zs_change']} WHERE cg_task_id='$cg_task_id' AND changekind='{$this->params['changekind']}' AND changeitem='{$this->params['changeitem']}' AND sp_online='0'");
		while($cgid_arr = $db->fetch_array($cgid_q)){
			$cgid []= $cgid_arr['id'];
		}
		$cgid = implode("','",$cgid);
		if($cgid == ''){
			$this->error [] = '错误的变更数据，变更已删除或已审批';
		}
		$this->savebefor();
		if($cgid != ''){
			DBUtil::update_tb($db, $this->dbtable['zs_change'], $this->params, "id IN('$cgid')");
			LogRW::logWriter($this->zs['zuzhi_id'],$this->zs['iso'].'：修改变更');
		}
	}
	//变更任务生成
	public function ChangeSaveTask($cg_task_id,$zsid){
		$db = $this->db;
		if((int)$cg_task_id > 0){
			$taskvalue = array(
				'zs_change_date' => $this->params['zs_change_date']
			);
			DBUtil::update_tb($db, $this->dbtable['zs_change_task'], $taskvalue," id='$cg_task_id' ");
		}else{
			$cgid = implode("','",$this->new_cg_id);
			$taskvalue = array(
				'zuzhi_id' => $this->params['zuzhi_id'],
				'cgid' => $cgid,
				'zsid' => $zsid,
				'htfrom' => $this->params['htfrom'],
				'zs_change_date' => $this->params['zs_change_date'],
				'zd_ren' => $_SESSION['username'],
				'zd_time' => date("Y-m-d")
			);
			DBUtil::insert_tb($db, $this->dbtable['zs_change_task'], $taskvalue);
			$new_task_id = $db->insert_id();
			$db->query("UPDATE {$this->dbtable['zs_change']} SET cg_task_id='$new_task_id' WHERE id IN('$cgid')");
		}
	}
	//审批变更通过
	public function AppChangeOk($cgid,$sp_date){
		$cgid = implode("','",(array)$cgid);
		$cg_q = $this->db->query("SELECT * FROM {$this->dbtable['zs_change']} WHERE id IN('$cgid')");
		while($cgdb = $this->db->fetch_array($cg_q)){
			$cg_arr []= $cgdb;
		}
		foreach($cg_arr as $cg){
			$changekind = '';
			$field = $tempvalue = array();
			switch ($cg['changeitem']) {
				case '01': $changekind = '1';$field=array('change_af'=>'eiregistername');break;
				case '0201': $changekind = '1';$field=array('change_af'=>'eireg_address');break;
				case '0202': $changekind = '1';$field=array('change_af'=>'eisc_address');break;
				case '0203': $changekind = '1';$field=array('change_af'=>'eipro_address');break;
				case '0204': $changekind = '1';$field=array('change_af'=>'eiman_amount');break;
				case '0205': $changekind = '1';$field=array('change_af'=>'iso_people_num');break;
				case '0206': $changekind = '1';$field=array('change_af'=>'eifaren');break;
				case '0207': $changekind = '1';$field=array('change_af'=>'eikind');break;
				case '0208': $changekind = '1';$field=array('change_af'=>'zs_address');break;
				case '03_1': $changekind = '2';$field=array('change_af'=>'audit_ver');break;
				case '06_1': $changekind = '2';$field=array('change_af'=>'audit_code');break;
				case '06_2': $changekind = '2';$field=array('change_af'=>'renzhengfanwei','changerange'=>'changerange');break;
				case '03': $changekind = '3';$field=array('change_af'=>'online','zs_change_date'=>'zs_change_date','changereason'=>'changereason','zs_zanting_edate'=>'zs_zanting_edate');break;
				case '04': $changekind = '3';$field=array('change_af'=>'online');break;
				case '05': $changekind = '3';$field=array('change_af'=>'online','zs_change_date'=>'zs_change_date','changereason'=>'changereason');break;
				case '0501': $changekind = '3';$field=array('change_af'=>'online');break;
				case '06': $changekind = '3';$field=array('change_af'=>'online');break;
				case '0602': $changekind = '3';$field=array('change_af'=>'online');break;
				case '0603': $changekind = '3';$field=array('change_af'=>'online');break;
				case '09': $changekind = '3';$field=array('change_af'=>'online');break;
				case '10': $changekind = '4';break;
				case '1001': $changekind = '4';break;
				default : exit('错误的变更类型');
			}
			foreach($field as $k=>$v){
				$tempvalue += array($v=>$cg[$k]);
			}
			switch($changekind){
				case '1' :
					DBUtil::update_tb($this->db, $this->dbtable['mk_company'], $tempvalue," id='$cg[zuzhi_id]'");
					break;
				case '2' :
					DBUtil::update_tb($this->db, $this->dbtable['xm_item'], $tempvalue," id='$cg[base_xmid]'");
					if($cg['xmid'] != $cg['base_xmid']){
						DBUtil::update_tb($this->db, $this->dbtable['xm_item'], $tempvalue," id='$cg[xmid]'");
					}
					break;
				case '3' :
					DBUtil::update_tb($this->db, $this->dbtable['zs_cert'], $tempvalue," id='$cg[zsid]'");
					break;
				case '4' :
					break;
				case '5' :
					DBUtil::update_tb($this->db, $this->dbtable['ht_contract_item'], $tempvalue," id='$cg[htxm_id]'");
					break;
			}
			if($cg['changekind'] == '1'){
				$this->db->query("UPDATE {$this->dbtable['zs_change']} SET sp_online='1',sp_date='$sp_date' WHERE cg_task_id='$cg[cg_task_id]' AND changeitem='$cg[changeitem]'");
			}else{
				$this->db->query("UPDATE {$this->dbtable['zs_change']} SET sp_online='1',sp_date='$sp_date' WHERE id='$cg[id]'");
			}
			LogRW::logWriter($cg['zuzhi_id'],$cg['iso'].'：变更审批通过');
		}
	}
	//审批变更不通过
	public function AppChangeNg($cgid,$sp_date){
		$cgid = implode("','",(array)$cgid);
		$cg_q = $this->db->query("SELECT id,zuzhi_id,iso,cg_task_id,changekind,changeitem FROM {$this->dbtable['zs_change']} WHERE id IN('$cgid')");
		while($cgdb = $this->db->fetch_array($cg_q)){
			$cg_arr []= $cgdb;
		}
		foreach($cg_arr as $cg){
			if($cg['changekind'] == '1'){
				$this->db->query("UPDATE {$this->dbtable['zs_change']} SET sp_online='2',sp_date='$sp_date' WHERE cg_task_id='$cg[cg_task_id]' AND changeitem='$cg[changeitem]'");
			}else{
				$this->db->query("UPDATE {$this->dbtable['zs_change']} SET sp_online='2',sp_date='$sp_date' WHERE id='$cg[id]'");
			}
			LogRW::logWriter($cg['zuzhi_id'],$cg['iso'].'：变更审批不通过');
		}
	}
	//取证书状态变更前一次记录
	public function getcgbf($zsid,$changeitem){
		$db = $this->db;
		$cgbf = $db->get_one("SELECT zs_change_date,zs_zanting_edate,changereason FROM {$this->dbtable['zs_change']}
		WHERE zsid='{$zsid}' AND changeitem='{$changeitem}' ORDER BY id DESC LIMIT 1");
		if($cgbf == ''){
			$cgbf = array('zs_change_date'=>'','zs_zanting_edate'=>'','zs_zanting_edate'=>'','changereason'=>'');
		}
		return $cgbf;
	}
	//撤销审批
	public function ReAppChange($cgid){
		$cgid = implode("','",(array)$cgid);
		$cg_q = $this->db->query("SELECT * FROM {$this->dbtable['zs_change']} WHERE id IN('$cgid')");
		while($cgdb = $this->db->fetch_array($cg_q)){
			//判断所删除的是否最后一次变更
			$last_cg = $this->db->get_one("SELECT id FROM {$this->dbtable['zs_change']} WHERE zsid='{$cgdb['zsid']}' AND changeitem='{$cgdb['changeitem']}' ORDER BY id DESC LIMIT 1");
			if($last_cg['id'] != $cgdb['id']){
				$this->error []= '请先处理变更类别为：'.Cache::cache_changeitem($cgdb['changeitem']).' 的最一次变更，ID：'.$last_cg['id'];
			}
			$last_cg = $this->db->get_one("SELECT id FROM {$this->dbtable['zs_change']} WHERE zsid='{$cgdb['zsid']}' AND changeitem='{$cgdb['changeitem']}' AND sp_online='0' LIMIT 1");
			if($last_cg['id'] != ''){
				$this->error []= '请先处理未审批的相同变更，类别为：'.Cache::cache_changeitem($cgdb['changeitem']).'，ID：'.$last_cg['id'];
			}
			$cg_arr []= $cgdb;
		}
		$this->savebefor();
		foreach($cg_arr as $cg){
			$changekind = '';
			$field = $tempvalue = array();
			if($cg['sp_online'] == '1'){
				switch ($cg['changeitem']) {
					case '01': $changekind = '1';$field=array('change_bf'=>'eiregistername');break;
					case '0201': $changekind = '1';$field=array('change_bf'=>'eireg_address');break;
					case '0202': $changekind = '1';$field=array('change_bf'=>'eisc_address');break;
					case '0203': $changekind = '1';$field=array('change_bf'=>'eipro_address');break;
					case '0204': $changekind = '1';$field=array('change_bf'=>'eiman_amount');break;
					case '0205': $changekind = '1';$field=array('change_bf'=>'iso_people_num');break;
					case '0206': $changekind = '1';$field=array('change_bf'=>'eifaren');break;
					case '0207': $changekind = '1';$field=array('change_bf'=>'eikind');break;
					case '0208': $changekind = '1';$field=array('change_bf'=>'zs_address');break;
					case '03_1': $changekind = '2';$field=array('change_bf'=>'audit_ver');break;
					case '06_1': $changekind = '2';$field=array('change_bf'=>'audit_code');break;
					case '06_2': $changekind = '2';$field=array('change_bf'=>'renzhengfanwei');break;
					case '03': $changekind = '3';$field=array('change_bf'=>'online');break;
					case '04': $changekind = '3';$field=array('change_bf'=>'online');break;
					case '05': $changekind = '3';$field=array('change_bf'=>'online');break;
					case '0501': $changekind = '3';$field=array('change_bf'=>'online');break;
					case '06': $changekind = '3';$field=array('change_bf'=>'online');break;
					case '0602': $changekind = '3';$field=array('change_bf'=>'online');break;
					case '0603': $changekind = '3';$field=array('change_bf'=>'online');break;
					case '09': $changekind = '3';$field=array('change_bf'=>'online');break;
					case '10': $changekind = '4';break;
					case '1001': $changekind = '4';break;
					default : exit('错误的变更类型');
				}
				foreach($field as $k=>$v){
					$tempvalue += array($v=>$cg[$k]);
				}
				switch($changekind){
					case '1' :
						DBUtil::update_tb($this->db, $this->dbtable['mk_company'], $tempvalue," id='$cg[zuzhi_id]'");
						break;
					case '2' :
						DBUtil::update_tb($this->db, $this->dbtable['xm_item'], $tempvalue," id='$cg[base_xmid]'");
						if($cg['xmid'] != $cg['base_xmid']){
							DBUtil::update_tb($this->db, $this->dbtable['xm_item'], $tempvalue," id='$cg[xmid]'");
						}
						break;
					case '3' :
						$tempvalue += $this->getcgbf($zsid,$cg['change_bf']);
						DBUtil::update_tb($this->db, $this->dbtable['zs_cert'], $tempvalue," id='$cg[zsid]'");
						break;
					case '4' :
						break;
					case '5' :
						DBUtil::update_tb($this->db, $this->dbtable['ht_contract_item'], $tempvalue," id='$cg[htxm_id]'");
						break;
				}
			}
			if($cg['changekind'] == '1'){
				$this->db->query("UPDATE {$this->dbtable['zs_change']} SET sp_online='0',sp_date='0000-00-00' WHERE cg_task_id='$cg[cg_task_id]' AND changeitem='$cg[changeitem]'");
			}else{
				$this->db->query("UPDATE {$this->dbtable['zs_change']} SET sp_online='0',sp_date='0000-00-00' WHERE id='$cg[id]'");
			}
			LogRW::logWriter($cg['zuzhi_id'],$cg['iso'].'：撤销变更审批-'.$cg['sp_online']);
		}
	}
	//删除变更
	public function DelChange($cgid){
		if((int)$cgid>'0'){
			$cg_task = $this->db->get_one("SELECT cg_task_id,AllProjRegiID,changeitem,changekind,sp_online FROM zs_change WHERE id='$cgid'");
			$changeitem = $cg_task['changeitem'];
			$cg_task_id = $cg_task['cg_task_id'];
			$changekind = $cg_task['changekind'];
			if($cg_task['AllProjRegiID'] != ''){
				$this->error[]= '旧变更数据，不能删除';
			}
			if($cg_task['sp_online'] != '0'){
				$this->error[]= '已审批变更数据，不能删除';
			}
			$this->savebefor();
			if($changekind == '1'){
				$cg_q = $this->db->query("SELECT id FROM zs_change WHERE cg_task_id='$cg_task[cg_task_id]' AND cg_task_id!='0' AND changeitem='$changeitem'");
				while($cg = $this->db->fetch_array($cg_q)){
					$cgid_arr []= $cg['id'];
				}
			}else{
				$cgid_arr []= $cgid;
			}
			$cgid_str = implode("','",$cgid_arr);
			DBUtil::Del($this->db, $this->dbtable['zs_change'], "id IN('$cgid_str')");
			$cg = $this->db->get_one("SELECT id FROM {$this->dbtable['zs_change']} WHERE cg_task_id='$cg_task_id' LIMIT 1");
			if($cg['id'] == ''){
				DBUtil::Del($this->db, $this->dbtable['zs_change_task'], "id='$cg_task_id'");
			}
			LogRW::logWriter($cg['zuzhi_id'],'删除变更信息');
		}
	}

	public function listChange($params = array()){
		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['zs_change']}`
							WHERE 1 $search ";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['zs_change']}`
						WHERE 1 $search ORDER BY id DESC";

		$page = new listChange($url, $sql);
		$list = $page->getPageData();

		$result['count'] = $page->count;
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		return $result;
	}

	public function savebefor(){
		if(!empty($this->error)){
			Error::ShowError($this->error);
		}
	}
}

class listChange extends Page {
	protected function resultFilter($result) {
		$rows = $this->db->get_one("SELECT certNo,coverFields FROM {$this->dbtable['zs_cert']} WHERE id='{$result['zsid']}'");
		$result['certNo'] = $rows['certNo'];
		$result['product'] =Cache::cache_product($result['product']);
		$result['coverFields'] =$rows['coverFields'];
		return $result;
	}
}
?>