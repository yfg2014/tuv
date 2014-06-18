<?php

/**
 * 企业基本信息类
 * @author Tom
 */


class details_finance {
	private $fields;
	public $db;
	public $error;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	//列表企业信息
	public function listElement($params = array()){
		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum	FROM {$this->dbtable[cw_finance_list]} WHERE 1 $search";
		$sql['data'] = "SELECT * FROM {$this->dbtable[cw_finance_list]} WHERE 1 $search  ORDER BY id DESC";
		$page = new list_details_finance($url, $sql);
		$list = $page->getPageData();
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		$result['count'] = $page->count;
		return $result;
	}

}
/**
 *
 * 扩展分页类，过滤结果，追加列
 * @author Tom
 *
 */
class list_details_finance extends Page {
	protected function resultFilter($result) {
		$db = $this->db;
		$details = new details_finance();
		$ex_q = $db->query("SELECT xmid FROM {$details->dbtable['cw_finance_list_ex']} WHERE cwid='$result[id]'");
		while($ex = $db->fetch_array($ex_q)){
			$xmid []= $ex['xmid'];
		}
		$xmid = implode("','",$xmid);
		$audit_type = array();
		$xm_q = $db->query("SELECT iso,audit_type,cw_online FROM {$details->dbtable['xm_item']} WHERE id IN('$xmid')");
		while($xm = $db->fetch_array($xm_q)){
			$xm['cw_online'] == '1' ? $xm['cw_online'] = ' (交完)' : $xm['cw_online'] = '';
			$audit_type []= $xm['iso'].': '.Cache::cache_audit_type($xm['audit_type']).$xm['cw_online'];
		}
		$result['audit_type'] = implode('<br>',$audit_type);
		//取合同项目号
		$htxmcode = array();
		$htxm_sql = $db->query("SELECT htxmcode FROM {$details->dbtable[ht_contract_item]} WHERE ht_id='$result[ht_id]'");
		while($htxm = $db->fetch_array($htxm_sql)){
			$htxmcode []= $htxm['htxmcode'];
		}
		$result['htxmcode'] = implode('<br>',$htxmcode);
		return $result;
	}
}

?>