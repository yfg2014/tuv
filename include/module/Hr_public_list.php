<?php

/**
 * 人员公共列表类  （注册资格 专业能力 ）
 * @2011-5-4
 */


class Hr_public_list {
	private $fields;
	public $db;
	public $error;
	public $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}

	//列表人员信息-ok
	public function list_hr_public($params = array()){

		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum	FROM {$this->dbtable[hr_information]} WHERE 1 $search";
		$sql['data'] = "SELECT * FROM {$this->dbtable[hr_information]} WHERE 1 $search  ORDER BY id DESC";
		$page = new Page($url, $sql);
		$list = $page->getPageData();
		$result['pages']	= $page->nav;
		$result['data']		= $list;
		$result['count']	= $page->count;
		return $result;
	}

}


?>