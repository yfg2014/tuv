<?php

/**
 * 证书业务模块
 * @author Tom
 *
 */
class Certificate {

	private $db;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	/**
	 * OverLoad登记证书
	 * (non-PHPdoc)
	 * @see CertificationDao::add()
	 */
	public function add($params) {
		$db = $this->db;
		DBUtil::insert_tb($db, $this->dbtable['zs_cert'], $params);
		$id = $db->insert_id();

		return $id;
	}

	/**
	 * 更新证书
	 * (non-PHPdoc)
	 * @see CertificationDao::update()
	 */
	public function update($id,$params) {
		$db = $this->db;

		DBUtil::update_tb($db, $this->dbtable['zs_cert'], $params, "id='{$id}'");

		return $id;
	}

	/**
	 * 删除证书
	 * (non-PHPdoc)
	 * @see CertificationDao::delete()
	 */
	public function delete($id) {
		$db = $this->db;

		$rows = $this->query($id,array('zuzhi_id','htxm_id','iso','audit_type','certNo'));
		DBUtil::Del($db, $this->dbtable['zs_cert'], "id='{$id}'");
		DBUtil::Del($db, $this->dbtable['zs_change'], "zsid='{$id}'");
		$result = $db->get_one("SELECT id FROM `{$this->dbtable['zs_cert']}` WHERE htxm_id='{$rows['htxm_id']}' ORDER BY id DESC LIMIT 1");
		$result == '' ? $zsid = '0' : $zsid = $result['id'];
		DBUtil::update_tb($db, $this->dbtable['xm_item'], array('zsid' => $zsid,'zsprintdate' => '0000-00-00'), "zsid='{$id}'");
		DBUtil::update_tb($db, $this->dbtable['pd_xm'], array('zsid' => $zsid,'zsprintdate' => '0000-00-00'), "zsid='{$id}'");
		DBUtil::update_tb($db, $this->dbtable['ht_contract_item'], array('zsid' => $zsid), "zsid='{$id}'");
		DBUtil::update_tb($db, $this->dbtable['ht_repeat_contract'], array('zsid' => $zsid), "zsid='{$id}'");
		LogRW::logWriter($rows['zuzhi_id'],'删除证书'.$rows['iso'].' '.Cache::cache_audit_type($rows['audit_type']).' '.$rows['certNo']);

		return true;
	}

	/**
	 * 按照id查询证书
	 * (non-PHPdoc)
	 * @see CertificationDao::query()
	 */
	public function query($id, $params=array()) {
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$result = $db->get_one("SELECT {$field} FROM `{$this->dbtable['zs_cert']}` WHERE id='{$id}' LIMIT 1");

		return $result;
	}

	public function toArray($where,$params = array()){
		$db = $this->db;

		$params == array() ? $field = '*' : $field = implode(',', $params);
		$q = $db->query("SELECT {$field} FROM {$this->dbtable['zs_cert']} WHERE  {$where}");
		while ($rows = $db->fetch_array($q)){
			$result[] = $rows;
		}

		return $result;
	}

	/**
	 * 证书到期
	 * (non-PHPdoc)
	 * @see Certificate::maturity()
	 * return int
	 */
	public function maturity($date) {
		$db = $this->db;

		$rows = $db->get_one("SELECT COUNT(*) AS sum FROM `{$this->dbtable['zs_cert']}` WHERE certEnd<='{$date}' AND certEnd!='0000-00-00' AND zsprintdate!='0000-00-00' AND (online='01' OR online='04' OR online='07' OR online='08')");
		DBUtil::update_tb($db, $this->dbtable['zs_cert'], array('online' => '02'), "certEnd<='{$date}' AND certEnd!='0000-00-00' AND zsprintdate!='0000-00-00' AND (online='01' OR online='04' OR online='07'  OR  online='08')");
		DBUtil::update_tb($db, $this->dbtable['ht_repeat_contract'], array('zs_online' => '02'), "zsFinallyDate<='{$date}' AND (zs_online='01' OR zs_online='04' OR zs_online='07'  OR zs_online='08')");
		$result = $rows['sum'];

		return $result;
	}

	public function CertificatNumber($certid,$audit_ver,$number,$re_views) {
		$a_tmp = date("y");
		$b_tmp = '****';
		if ($number > 0 and $number <= 50) {
			$c_tmp = 'S';
		} elseif ($number > 50 and $number <= 1000) {
			$c_tmp = 'M';
		} elseif ($number > 1000) {
			$c_tmp = 'L';
		} else {
			$c_tmp = '';
		}
		$result = $certid.$a_tmp.$audit_ver.$b_tmp."R".$re_views.$c_tmp;

		return $result;
	}

	public function listCertification($params = array()) {
		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['zs_cert']}`
							WHERE 1 $search ";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['zs_cert']}`
						WHERE 1 $search ORDER BY id DESC";
		try {
			$page = new listCertification($url, $sql);
			$list = $page->getPageData();
		} catch (Exception $e) {
			exit($e->error_msg());
		}
		$result['count'] = $page->count;
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		return $result;
	}

		public function postCertification($params = array()) {
		$search = $params['search'];
		$url = $params['url'];

		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['zs_cert']}`
							WHERE 1 $search ";
		$sql['data'] = "SELECT *
					 	FROM `{$this->dbtable['zs_cert']}`
						WHERE 1 $search ORDER BY zsprintdate DESC";
		try {
			$page = new listCertification($url, $sql);
			$list = $page->getPageData();
		} catch (Exception $e) {
			exit($e->error_msg());
		}
		$result['count'] = $page->count;
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		return $result;
	}

	public function save($id,$params) {
		if ((int)$id > 0){
			$id = $this->update($id,$params);
		} else {
			$id = $this->add($params);
		}

		return $id;
	}

}

class listCertification extends Page {

	protected function resultFilter($result) {
		$result['zs_change_date'] = Cache::cache_time_value($result['zs_change_date']);
		$result['mark'] = Cache::cache_mark($result['mark']);
		$result['status'] = Cache::cache_Certification_online($result['online']);
		$pd = $this->db->get_one("SELECT approvaldate FROM {$this->dbtable['pd_xm']} WHERE id='{$result['pdid']}'");
		$result['approvaldate'] = $pd['approvaldate'];
		return $result;
	}
}
?>