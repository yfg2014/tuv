<?php

class SetupList {

	private $dbtable;

	public function __construct() {
		global $dbtable;
		$this->dbtable = $dbtable;
	}
	public function get_dizhi_list($params = array()){

		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum
					FROM `{$this->dbtable['setup_city']}` a
					LEFT JOIN `{$this->dbtable['setup_province']}` b ON a.dacode = b.code
					WHERE 1 $search AND a.online = '1'";
		$sql['data'] = "SELECT a.code,a.msg,b.msg as bmsg
						FROM `{$this->dbtable['setup_city']}` a
						LEFT JOIN `{$this->dbtable['setup_province']}` b ON a.dacode=b.code
						WHERE 1 $search AND a.online = '1' ORDER BY b.code ASC";

		$page = new listArea($url,$sql);
		$list = $page->getPageData();

		$result['pages'] = $page->nav;
		$result['data'] = $list;

		return $result;
	}

	public function get_daima_list($params = array()){

		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum
							FROM `{$this->dbtable['setup_audit_code']}`
							WHERE 1 $search AND online = 1 ";
		$sql['data'] = "SELECT code,msg,iso,mark,shangbao
						FROM `{$this->dbtable['setup_audit_code']}`
						WHERE 1 $search AND online = 1 ORDER BY code ASC ";

		try {
			$page = new Page($url, $sql);
			$list = $page->getPageData();
		} catch (Exception $e) {
			echo $e->error_msg();
			exit;
		}
		$result['pages'] = $page->nav;
		$result['data'] = $list;
		return $result;
	}

	public function get_auditor_list($params = array()){

		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum
					FROM `{$this->dbtable['hr_reg_qualification']}` a
					LEFT JOIN `{$this->dbtable['hr_information']}` b ON a.hr_id = b.id
					WHERE (a.qualification!='1001' OR a.qualification!='1006') AND a.online='1' {$search}";
		$sql['data'] = "SELECT * FROM `{$this->dbtable['hr_reg_qualification']}` a
						LEFT JOIN `{$this->dbtable['hr_information']}` b ON a.hr_id = b.id
						WHERE  a.qualification!='1001' AND a.qualification!='1006'  AND a.online='1' {$search} ORDER BY a.hr_id DESC";
		try {
			$page = new listAuditor($url, $sql);
			$list = $page->getPageData();
		} catch (Exception $e) {
			echo $e->error_msg();
			exit;
		}
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
 * 地址扩展
 */
class listArea extends Page {
	function resultFilter($forum_guest){
		$zhixiashi = array('441900','442000','110100','120100','310100','500100','820000','810000','710000','000000');
		if (substr($forum_guest['code'],4,2) != '00') {
			$temp_code = substr($forum_guest['code'],0,4).'00';
			$temp_arr = $this->db->get_one("SELECT msg FROM `{$this->dbtable['setup_city']}` WHERE code='$temp_code' LIMIT 1");
			$temp_msg = $temp_arr['msg'];
			$temp_msg == $forum_guest['msg'] && $temp_msg = '';
			$forum_guest['bmsg'] == $forum_guest['msg'] && $forum_guest['bmsg'] = '';
			$forum_guest['msg'] = $forum_guest['bmsg'].$temp_msg.$forum_guest['msg'];
		} elseif(in_array($forum_guest['code'],$zhixiashi)) {	//东莞市中山市的无县区级地址
			$temp_msg == $forum_guest['msg'] && $temp_msg = '';
			$forum_guest['bmsg'] == $forum_guest['msg'] && $forum_guest['bmsg'] = '';
			$forum_guest['msg'] = $forum_guest['bmsg'].$forum_guest['msg'];
		}else{
			$forum_guest = array();
		}
		return $forum_guest;
	}
}

/**
 *
 * 扩展分页类，过滤结果
 * @author Tom
 * 审核员扩展分页
 */
class listAuditor extends Page {
	function resultFilter($result){
		$result['qualification'] = Cache::cache_hr_reg_qualification($result['qualification']);
		return $result;
	}
}













?>