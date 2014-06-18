<?php
/*
 * 认证类型
 */
class TypeChange {
	private $db;
	private $dbtable;
	public $test;
	public $xmid;
	public $s_id;
	public $cgid;
	private $xm;
	public $value; //获取POST数据
	public $params; //过滤过后要写入数据表数据
	public $edit_params; //过滤过后要写入数据表数据
	public function __construct($xmid,$value,$cgid){
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
		$this->cgid = $cgid;
		if($xmid!=''){
			$xm = $this->db->get_one("SELECT * FROM {$this->dbtable['xm_item']} WHERE id='{$xmid}'");
			$this->xm = $xm;
			$this->xmid = $xmid;
			$this->value = $value;

			$params = array(
				'xmid' => $this->xmid,
				'base_xmid' => $xmid,
				'htxm_id' => $this->xm['htxm_id'],
				'ht_id' => $this->xm['ht_id'],
				'zuzhi_id' => $this->xm['zuzhi_id'],
				'htfrom' => $this->xm['htfrom'],
				'iso' => $this->xm['iso'],
				'product' => $this->xm['product'],
				'product_ver' => $this->xm['product_ver'],
				'audit_type' => $this->xm['audit_type'],
				'changedate_s' => $this->value['xm_change_date'], //默认变更时间
				'changedate_e' => $this->value['xm_change_date'], //默认变更时间
				'xm_change_date' => $this->value['xm_change_date'],
				'creatitem' => $this->value['creatitem'],
				'other' => $this->value['other'],
				'cw_other' => $this->value['cw_other'],
				'zd_ren' => $_SESSION['username'],
				'zd_date' => date("Y-m-d"),
			);
			$this->params = $params;

			$edit_params = array(
				'changedate_s' => $this->value['xm_change_date'], //默认变更时间
				'changedate_e' => $this->value['xm_change_date'], //默认变更时间
				'xm_change_date' => $this->value['xm_change_date'],
				'other' => $this->value['other'],
				'cw_other' => $this->value['cw_other']
			);
			$this->edit_params = $edit_params;
		}
	}
	public function query($id,$params = array()){
		$db = $this->db;
		$params == array() ? $field = '*' : $field = implode(',', $params);
		$result = $db->get_one("SELECT {$field} FROM `{$this->dbtable['xm_rzlx']}` WHERE id='{$id}' LIMIT 1");
		return $result;
	}

	public function DelType($cgid){
		$db = $this->db;
		$cgbf = array();
		//还原变更前数据
		$cg = $db->get_one("SELECT * FROM {$this->dbtable['xm_rzlx']} WHERE id='{$cgid}'");
		//判断所删除的是否最后一次变更
		$last_cg = $db->get_one("SELECT id FROM {$this->dbtable['xm_rzlx']} WHERE base_xmid='{$cg[base_xmid]}' ORDER BY id DESC LIMIT 1");
		if($last_cg['id'] != $cgid){
			Url::goto_url('', '所删除变更不是最后一次变更，请先删除最一次变更');
		}
		//是否有新增特殊审核项目，有则删
		if($cg['creatitem'] == '1'){
			DBUtil::Del($db, $this->dbtable['xm_item'], "id='{$cg[xmid]}'");
		}
		if($cg['s_id'] > '0'){
			DBUtil::Del($db, $this->dbtable['ht_sampling'], "id='{$cg[s_id]}'");
		}
		switch ($cg['renzhengleixing']) {
			case '03':
				$cgbf = array(
				'product_ver'=>$cg['product_ver_bf']
				);
				DBUtil::update_tb($db, $this->dbtable['xm_item'], $cgbf,"id='{$cg[base_xmid]}'");break;
			case '06':
				$cgbf = array(
				'changerange'=>'0',
				'renzhengfanwei'=>$cg['renzhengfanwei_bf'],
				'audit_code'=>Cache::cache_audit_code($cg['audit_code_bf'])
				);
				DBUtil::update_tb($db, $this->dbtable['xm_item'], $cgbf,"id='{$cg[base_xmid]}'");break;
			case '07':
				$cgbf = array(
				'changerange'=>'0',
				'renzhengfanwei'=>$cg['renzhengfanwei_bf'],
				'audit_code'=>Cache::cache_audit_code($cg['audit_code_bf'])
				);
				DBUtil::update_tb($db, $this->dbtable['xm_item'], $cgbf,"id='{$cg[base_xmid]}'");break;
			default: $this->ShowError();
		}
		DBUtil::Del($db, $this->dbtable['xm_rzlx'], "id='{$cgid}'");
		return true;
	}

	//保存变更
	public function AddType($params){
		$db = $this->db;
		DBUtil::insert_tb($db, $this->dbtable['xm_rzlx'], $params);
		$id = $db->insert_id();
		return $id;
	}

	//编辑变更
	public function EditType($edit_params){
		$db = $this->db;
		DBUtil::update_tb($db, $this->dbtable['xm_rzlx'], $edit_params,"id='{$this->cgid}'");
	}

	//新增特殊审核项目
	public function AddNewXmItem(){
		$db = $this->db;
		if($this->value['creatitem'] == '1'){
			$xmvalue = array(
			'htxm_id' => $this->xm['htxm_id'],
			'htfrom' => $this->xm['htfrom'],
			'ht_id' => $this->xm['ht_id'],
			'zsid' => $this->xm['zsid'],
			'zuzhi_id' => $this->xm['zuzhi_id'],
			'iso' => $this->xm['iso'],
			'audit_ver' => $this->xm['audit_ver'],
			'product' => $this->xm['product'],
			'product_ver' => $this->xm['product_ver'],
			'audit_type' => '1006',
			'auditplandate' => $this->value['xm_change_date'], //默认变更时间
			'cj_ren' => $_SESSION['username'],
			'cj_time' => date('Y-m-d')
			);
			DBUtil::insert_tb($db, $this->dbtable['xm_item'], $xmvalue);
			$this->xmid = $db->insert_id();
			$this->Opifaudit();
		}
	}
	//新增抽样
	public function AddNewSample(){
		$db = $this->db;
		if($this->value['ifchouyan'] == '1'){
			$samplevalue = array(
			'zuzhi_id' => $this->xm['zuzhi_id'],
			'ht_id' => $this->xm['ht_id'],
			'htxm_id' => $this->xm['htxm_id'],
			'htfrom'=>$this->xm['htfrom'],
			'zd_ren' => $_SESSION['username'],
			'zd_time' => date('Y-m-d')
			);
			DBUtil::insert_tb($db, $this->dbtable['ht_sampling'], $samplevalue);
			$this->s_id = $db->insert_id();
		}
	}

	//是否换证
	public function Opchangecert(){
		$this->db->query("UPDATE {$this->dbtable['xm_item']} SET
		ifchangecert = '{$this->value[ifchangecert]}'
		WHERE id='{$this->xmid}'");
	}

	//是否现场审核
	public function Opifaudit(){
		$this->value['ifaudit'] == '1' ? $online = '0' : $online = '3';
		$search = '';
		if($this->value['ifaudit'] == '1'){
			$date = date('Y-m-d');
			$search = "AND to_assess_date='{$date}' AND zl_okdate='{$date}'";
		}
		$this->db->query("UPDATE {$this->dbtable['xm_item']} SET
		online = '$online' $search
		WHERE id='{$this->xmid}'");
	}

	public function TypeSave(){

		$this->savebefore();
		//$this->AddNewXmItem(); //增加特殊审核项目$params
		//$this->AddNewSample(); //增加抽样$params
		//重新加载xmid
		$this->params['xmid'] = $this->xmid;
		$this->params['s_id'] = $this->s_id;
		//$this->Opchangecert(); //是否换证

		foreach((array)$this->value['renzhengleixing'] as $v){
			switch ($v) {
				case '03': $this->AuditVerChange($v);break;
				case '06': $this->RenzhengfanweiChange($v);break;
				case '07': $this->RenzhengfanweiChange($v);break;
				default: $this->ShowError();
			}
		}
	}

	public function AuditVerChange($renzhengleixing){
		$this->db->query("UPDATE {$this->dbtable['xm_item']} SET
		product_ver = '{$this->value[product_ver_af]}'
		WHERE id='{$this->xmid}'");
		//写入变更表前完整变更数据
		$this->params['renzhengleixing'] = $renzhengleixing;
		$this->params['product_ver_bf'] = $this->value['product_ver_bf'];
		$this->params['product_ver_af'] = $this->value['product_ver_af'];
		$this->params['audit_ver_bf'] = $this->value['audit_ver_bf'];
		$this->params['audit_ver_af'] = $this->value['audit_ver_af'];
		if($this->cgid == ''){
			$this->AddType($this->params);
		}else{
			$this->edit_params['product_ver_bf'] = $this->value['product_ver_bf'];
			$this->edit_params['product_ver_af'] = $this->value['product_ver_af'];
			$this->edit_params['audit_ver_bf'] = $this->value['audit_ver_bf'];
			$this->edit_params['audit_ver_af'] = $this->value['audit_ver_af'];
			$this->EditType($this->edit_params);
		}
	}

	public function RenzhengfanweiChange($renzhengleixing){
		//如果新增特殊审核项目则只审核变化的范围，非总范围，产品认证例外
		if($this->value['creatitem'] == '1'){
			$this->db->query("UPDATE {$this->dbtable['xm_item']} SET
			audit_code = '{$this->value[audit_code_af]}',
			renzhengfanwei = '{$this->value[renzhengfanwei_af]}',
			changerange = '{$this->value[changerange]}'
			WHERE id='{$this->xmid}'");

		}else{
			$this->db->query("UPDATE {$this->dbtable['xm_item']} SET
			audit_code = '{$this->value[audit_code_af]}',
			renzhengfanwei = '{$this->value[renzhengfanwei_af]}',
			changerange = '{$this->value[changerange]}'
			WHERE id='{$this->xmid}'");
		}
		//写入变更表前完整变更数据
		$this->params['renzhengleixing'] = $renzhengleixing;
		$this->params['audit_code_af'] = Cache::cache_audit_code($this->value['audit_code_af']);
		$this->params['audit_code_bf'] = Cache::cache_audit_code($this->value['audit_code_bf']);
		$this->params['audit_code_cg'] = Cache::cache_audit_code($this->value['audit_code_cg']);
		$this->params['renzhengfanwei_af'] = $this->value['renzhengfanwei_af'];
		$this->params['renzhengfanwei_bf'] = $this->value['renzhengfanwei_bf'];
		$this->params['renzhengfanwei_cg'] = $this->value['renzhengfanwei_cg'];
		$this->params['changerange'] = $this->value['changerange'];
		if($this->cgid == ''){
			$this->AddType($this->params);
		}else{
			$this->edit_params['audit_code_af'] = Cache::cache_audit_code($this->value['audit_code_af']);
			$this->edit_params['audit_code_bf'] = Cache::cache_audit_code($this->value['audit_code_bf']);
			$this->edit_params['audit_code_cg'] = Cache::cache_audit_code($this->value['audit_code_cg']);
			$this->edit_params['renzhengfanwei_af'] = $this->value['renzhengfanwei_af'];
			$this->edit_params['renzhengfanwei_bf'] = $this->value['renzhengfanwei_bf'];
			$this->edit_params['renzhengfanwei_cg'] = $this->value['renzhengfanwei_cg'];
			$this->edit_params['changerange'] = $this->value['changerange'];
			$this->EditType($this->edit_params);
		}
	}

	public function listRzlx($params = array()){
		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['xm_rzlx']}`
							WHERE 1 $search ";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['xm_rzlx']}`
						WHERE 1 $search ORDER BY id DESC";
		try {
			$page = new listRzlx($url, $sql);
			$list = $page->getPageData();
		} catch (Exception $e) {
			exit($e->error_msg());
		}
		$result['count'] = $page->count;
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		return $result;
	}

	public function savebefore(){
		if(in_array('03',$this->value['renzhengleixing']) and ($this->value['audit_ver_af'] == '' or $this->value['product_ver_af'] == '')){
			Error::ShowError('不存在的新标准');
		}
		//过滤专业代码格式
		$this->value['audit_code_bf'] = str_replace(';','；',$this->value['audit_code_bf']);
		$this->value['audit_code_af'] = str_replace(';','；',$this->value['audit_code_af']);
		$this->value['audit_code_cg'] = str_replace(';','；',$this->value['audit_code_cg']);

		if(in_array('03',$this->value['renzhengleixing'])){ //版本变更，无论是否新增项目，都强制原项目的product_ver
			$this->db->query("UPDATE {$this->dbtable['xm_item']} SET
			audit_ver = '{$this->value[audit_ver_af]}',product_ver = '{$this->value[product_ver_af]}'
			WHERE id='{$this->xmid}'");
		}
		if(in_array('06',$this->value['renzhengleixing']) or in_array('07',$this->value['renzhengleixing'])){ //范围变更，无论是否新增项目，都强制原项目的范围和代码
			$this->db->query("UPDATE {$this->dbtable['xm_item']} SET
			audit_code = '{$this->value[audit_code_af]}',
			renzhengfanwei = '{$this->value[renzhengfanwei_af]}',
			changerange = '{$this->value[changerange]}'
			WHERE id='{$this->xmid}'");
		}

	}

	public function ShowError(){
	}
}

class listRzlx extends Page {

	protected function resultFilter($result) {
		$rows = $this->db->get_one("SELECT renzhengfanwei FROM {$this->dbtable['xm_item']} WHERE id='{$result['xmid']}'");
		if($result['renzhengleixing'] == '03'){
			$result['xiangxi'] = "变更前：$result[product_ver_bf] 变更后：$result[product_ver_af] <br> 备注：$result[other] <br> 财务相关：$result[cw_other]";
		}
		if($result['renzhengleixing'] == '06' || $result['renzhengleixing'] == '07'){
			$result['xiangxi'] = "变更前代码：$result[audit_code_bf] <br> 变更后代码：$result[audit_code_af] <br> 变更代码：$result[audit_code_cg] <br> 变更前范围：$result[renzhengfanwei_bf] <br> 变更后范围：$result[renzhengfanwei_af] <br> 变更范围：$result[renzhengfanwei_cg] <br>  备注：$result[other] <br> 财务相关：$result[cw_other]";
		}
		$result['renzhengleixing_code'] = $result['renzhengleixing'];
		$result['renzhengleixing'] = Cache::cache_type_online($result['renzhengleixing']);
		$result['renzhengfanwei'] = $rows['renzhengfanwei'];
		return $result;
	}
}
?>