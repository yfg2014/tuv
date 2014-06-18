<?php
/**
 * 项目业务模块
 * @author Tom
 *
 */
class Item {

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
		$xm = $db->get_one("SELECT id FROM {$this->dbtable['xm_item']} WHERE htxm_id='$params[htxm_id]' AND audit_type='$params[audit_type]'");
		if($xm['id'] == ''){
			DBUtil::insert_tb($db, $this->dbtable['xm_item'], $params);
			$id = $db->insert_id();
			return $id;
		}
	}

	public function update($id,$params) {
		$this->savebefore();
		$db = $this->db;
		DBUtil::update_tb($db, $this->dbtable['xm_item'], $params, "id='{$id}'");
		return $id;
	}

	public function delete($id) {
		$this->savebefore();
		$db = $this->db;
		$rows = $this->query($id);

		DBUtil::Del($db, $this->dbtable['xm_item'], "id='".$id."'");
		DBUtil::Del($db, $this->dbtable['pd_xm'], "xmid='".$id."'");
		LogRW::logWriter($rows['zuzhi_id'], '项目删除：'.Cache::cache_audit_type($rows['audit_type']));
		return true;
	}

	/*
	 * @监督项目撤销
	 */
	public function revoked ($xmid,$zuzhi_id){
		$this->update($xmid, array('online' => '5'));
		LogRW::logWriter($zuzhi_id, '监督项目撤销');
		return true;
	}

	public function query($id,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$result = $db->get_one("SELECT {$field} FROM `{$this->dbtable['xm_item']}` WHERE id='{$id}' LIMIT 1");

		return $result;
	}

	public function toArray($where,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$sql = $db->query("SELECT {$field} FROM {$this->dbtable['xm_item']} WHERE {$where}");
		while ($rows = $db->fetch_array($sql)) {
			if ($rows['htxm_id'] != '')$form = $db->get_one("SELECT mark FROM `{$this->dbtable['ht_contract_item']}` WHERE id='{$rows['htxm_id']}' LIMIT 1");
			$rows['mark'] = $form['mark'];
			$rows['eiregistername'] = Cache::cache_company($rows['zuzhi_id']);
			$rows['audit_type'] = Cache::cache_audit_type($rows['audit_type']);
			$rows['assessmentdate'] = Cache::cache_time_value($rows['assessmentdate']);
			$rows['approvaldate'] = Cache::cache_time_value($rows['approvaldate']);
			$result[] = $rows;
		}

		return $result;
	}

	/*
	 * 材料回收
	 */
	public function recovery($xmid,$params){
		$db = $this->db;
		foreach($xmid as $v){
			$params['zl_okman']= $_SESSION['username'];
			$this->update($xmid,$params);
			LogRW::logWriter($rows['zuzhi_id'], '项目资料回收');
		}
		return true;
	}


	public function listElement($params = array()) {

		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['xm_item']}`
							WHERE 1 $search ";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['xm_item']}`
						WHERE 1 $search ORDER BY id DESC";
		$page = new listXm($url, $sql);
		$list = $page->getPageData();
		$result['count'] = $page->count;
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		return $result;
	}
	//列表应暂停项目
	public function listElementStop($params = array()) {

		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['xm_item']}`
							WHERE 1 $search ";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['xm_item']}`
						WHERE 1 $search ORDER BY id DESC";
		$page = new liststop($url, $sql);
		$list = $page->getPageData();
		$result['count'] = $page->count;
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		return $result;
	}

	//体系列表维护项目
	public function listElementSv($params = array()) {

		$search = $params['search'];
		$url = $params['url'];

		/*$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['xm_item']}` WHERE 1 AND (htxm_id IN(SELECT htxm_id FROM {$this->dbtable['zs_cert']} WHERE (online = '01' OR online = '03' OR online = '04')) OR zsid='0') $search";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['xm_item']}`
						WHERE 1 AND (htxm_id IN(SELECT htxm_id FROM {$this->dbtable['zs_cert']} WHERE (online = '01' OR online = '03' OR online = '04')) OR zsid='0') $search ORDER BY finalItemDate ASC";*/
		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['xm_item']}` WHERE 1 $search";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['xm_item']}`
						WHERE 1 $search ORDER BY finalItemDate ASC";
		$page = new list_sv($url, $sql);
		$list = $page->getPageData();
		$result['count'] = $page->count;
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		return $result;
	}
	//产品体系列表维护项目
	public function listElementCom($params = array()) {

		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(DISTINCT zuzhi_id,finalItemDate) AS sum
							FROM `{$this->dbtable['xm_item']}` WHERE 1 $search  ";
		$sql['data'] = "SELECT DISTINCT zuzhi_id,finalItemDate FROM `{$this->dbtable['xm_item']}` WHERE 1 $search  ";
		$page = new list_cm($url, $sql,$params);
		$list = $page->getPageData();
		$result['count'] = $page->count;
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		return $result;
	}

	//证明邮寄
	public function listElementPost($params = array()) {

		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['xm_item']}`
							WHERE 1 $search ";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['xm_item']}`
						WHERE 1 $search  ORDER BY assessmentdate DESC";
		$page = new list_post($url, $sql);
		$list = $page->getPageData();
		$result['count'] = $page->count;
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		return $result;
	}
	public function savebefore(){
	}

}
/**
 *
 * 扩展分页类，过滤结果，追加列
 * @author Tom
 *
 */
class listXm extends Page {

	protected function resultFilter($result) {
		$result['status'] = Cache::cache_item_online($result['online']);
		$result['auditplandate'] = Cache::cache_time_value($result['auditplandate']);
		$result['finalItemDate'] = Cache::cache_time_value($result['finalItemDate']);
		$result['assessmentdate'] = Cache::cache_time_value($result['assessmentdate']);
		$result['taskEndDate'] = Cache::cache_time_value($result['taskEndDate']);
		$result['taskBeginHalfDate'] = Cache::cache_time_online($result['taskBeginHalfDate']);
		$result['taskEndHalfDate'] = Cache::cache_time_online($result['taskEndHalfDate']);
		$result['zsprintdate'] = Cache::cache_time_value($result['zsprintdate']);
		$result['zs_if'] = Cache::setup_pd_online($result['zs_if']);
		$result['taskBeginDate'] = Cache::cache_time_value($result['taskBeginDate']);
		$result['actualtaskBeginHalfDate'] = Cache::cache_time_online($result['actualtaskBeginHalfDate']);
		$result['actualtaskEndHalfDate'] = Cache::cache_time_online($result['actualtaskEndHalfDate']);
		$result['zl_okdate'] = Cache::cache_time_value($result['zl_okdate']);
		$result['archivedate'] = Cache::cache_time_value($result['archivedate']);
		$result['xm_yanqi'] = Cache::cache_iswhether($result['xm_yanqi']);

		if ($result['taskId'] != '0'){
			$rows = $this->db->get_one("SELECT auditorId FROM `{$this->dbtable['xm_auditor_plan']}` WHERE taskId='{$result['taskId']}' AND iso='{$result['iso']}' AND isLeader='1' LIMIT 1");
			$rows = $this->db->get_one("SELECT empName FROM `{$this->dbtable['xm_auditor']}` WHERE id='{$rows['auditorId']}' LIMIT 1");
			$result['empName'] = $rows['empName'];
		}
		return $result;
	}
}
//应暂停
class liststop extends Page {

	protected function resultFilter($result) {
		$result['status'] = Cache::cache_item_online($result['online']);
		$result['auditplandate'] = Cache::cache_time_value($result['auditplandate']);
		$result['finalItemDate'] = Cache::cache_time_value($result['finalItemDate']);

		if ($result['zsid'] != '0'){
			$rows = $this->db->get_one("SELECT certNo,certEnd FROM `{$this->dbtable['zs_cert']}` WHERE id='{$result['zsid']}' LIMIT 1");
			$result['certNo'] = $rows['certNo'];
			$result['certEnd'] = $rows['certEnd'];
		}
		return $result;
	}
}

//体系维护列表
class list_sv extends Page {

	protected function resultFilter($result) {
		$result['status'] = Cache::cache_item_online($result['online']);
		$result['auditplandate'] = Cache::cache_time_value($result['auditplandate']);
		$result['finalItemDate'] = Cache::cache_time_value($result['finalItemDate']);
		$result['zsonline'] = Cache::cache_Certification_online($result['zsonline']);
		$certNo_arr = $certEnd_arr = $zsonline_arr = array();
		if ($result['htxm_id'] > '0'){
			$z_rows = $this->db->query("SELECT certNo,certStart,certEnd,online FROM `{$this->dbtable['zs_cert']}` WHERE htxm_id='{$result['htxm_id']}' AND (online='01' OR online='03' OR online='04') LIMIT 1");
			while($z_arr = $this->db->fetch_array($z_rows)){
				$certNo_arr []= $z_arr['certNo'].'&nbsp;'.Cache::cache_Certification_online($z_arr['online']);
				$certEnd_arr []= $z_arr['certEnd'];
				//$zsonline_arr []= $z_arr['online'];
			}
		}
		$result['certNo'] = implode('<br>',$certNo_arr);
		$result['certEnd'] = implode('<br>',$certEnd_arr);
		//$result['zsonline'] = implode('<br>',$zsonline_arr);
		$rows1 = $this->db->get_one("SELECT eiarea FROM `{$this->dbtable['mk_company']}` WHERE id='{$result['zuzhi_id']}' LIMIT 1");
		$result['eiarea'] = $rows1['eiarea'];
		$ht = $this->db->get_one("SELECT iso FROM `{$this->dbtable['ht_contract']}` WHERE id='{$result['ht_id']}' LIMIT 1");
		$result['ht_iso'] = $ht['iso'];
		return $result;
	}
}

//产品体系维护列表
class list_cm extends Page {

	protected function resultFilterCm($result, $search) {
		$result['finalItemDate']='';
		$result['htcode']='';
		$result['htxmcode']='';
		$result['ht_iso']='';
		$result['productNum'] = '';
		$result['productName'] = '';

		$db = $this->db;
		$sql = $db->query("SELECT * FROM `{$this->dbtable['xm_item']}` WHERE 1  $search  and zuzhi_id='{$result['zuzhi_id']}' ");
		while ($rows = $db->fetch_array($sql)) {
			$result['status'] = Cache::cache_item_online($rows['online']);
			if($rows['iso']!='' ) $result['ht_iso'] .=$rows['iso'].";";
			if($rows['product']!='' ) {
				$result['productNum'] +=1;
				$result['productName'] .=Cache::cache_product($rows['product'])."<br>";
			}
			if($rows['finalItemDate']!=''&& $rows['finalItemDate']!='0000-00-00'){
				$result['finalItemDate'] .= $rows['finalItemDate'].";";
			}
			if($rows['ht_id'] != '0')  {
				$result['htcode'] .= Cache::cache_htcode($rows['ht_id']).";";
			}
			if($rows['htxm_id']!='0') {
				$result['htxmcode'] .= Cache::cache_htxmcode($rows['htxm_id']).";";
			}
		}
		$result['finalItemDate']=implode("<br>",array_unique(explode(";",$result['finalItemDate'])));
		$result['htcode']=implode("<br>",array_unique(explode(";",$result['htcode'])));
		$result['htxmcode']=implode("<br>",array_unique(explode(";",$result['htxmcode'])));
		$result['ht_iso']=implode("<br>",array_unique(explode(";",$result['ht_iso'])));

		return $result;
		}
	}

//证明邮寄
class list_post extends Page {

	protected function resultFilter($result) {
		$result['status'] = Cache::cache_item_online($result['online']);
		$result['auditplandate'] = Cache::cache_time_value($result['auditplandate']);
		$result['finalItemDate'] = Cache::cache_time_value($result['finalItemDate']);
		//$result['zsonline'] = Cache::cache_Certification_online($result['zsonline']);
		if ($result['zsid'] != '0'){
			$rows = $this->db->get_one("SELECT certNo,certStart,certEnd,online FROM `{$this->dbtable['zs_cert']}` WHERE id='{$result['zsid']}' LIMIT 1");
			$result['certNo'] = $rows['certNo'];
			$result['certStart'] = $rows['certStart'];
			$result['certEnd'] = $rows['certEnd'];
			$result['zsonline'] = Cache::cache_Certification_online($rows['online']);
		}
		return $result;
	}
}
?>