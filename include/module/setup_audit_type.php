<?php
/**
 * 审核类型
 * @2011-4-27
 *
 *
 */

class setup_audit_type{
	private $db;
	private $dbtable;

	public function __construct() {
		global $db,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
	}
	//使用状态
	public function showonline($online){
		$online == '1' ? $online = '启用' : $online = '<font color=red>停用</font>';
		return $online;
	}

	//生成配置文件
	public function file_setup(){
		$db = $this->db;
		$rst = DBUtil::GetArr($db,$this->dbtable['setup_audit_type'],'',' 1 ORDER BY isdesc ASC');
		$cache = "<?php\n";
		$cache .= "\$setup_audit_type=array(\n";
		foreach($rst as $v){
			$cache .= "\t'$v[code]' =>array(
			\n\t\t'code'=>'$v[code]',
			\n\t\t'msg'=>'$v[msg]',
			\n\t\t'online'=>'$v[online]'),\n";
		}
		$cache .= "\n);\n?>";
		File::writeFile(SET_DIR.'setup_audit_type.php', $cache);
	}

	//列表审核类型信息
	public function list_setup($params = array()){
		$search = $params['search'];
		$url = $params['url'];
		$sql['count'] = "SELECT COUNT(*) AS sum	FROM `{$this->dbtable[setup_audit_type]}` WHERE 1 $search";
		$sql['data'] = "SELECT * FROM `{$this->dbtable[setup_audit_type]}` WHERE 1 $search  ORDER BY isdesc ASC";
		$page = new Page($url, $sql);
		$result['data'] = $page->getPageData();
		$result['pages'] = $page->nav;
		$result['count'] = $page->count;
		return $result;
	}

	//获取单个审核类型
	public function get_setup($id,$fields=array()){
		$db = $this->db;
		$rst = DBUtil::GetOne($db, $this->dbtable['setup_audit_type'], '', "id='$id'");
		return $rst;
	}

	//新增审核类型
	public function add_setup($value){
		$db = $this->db;
		DBUtil::insert_tb($db, $this->dbtable['setup_audit_type'], $value);
		$this->file_setup();
		return true;
	}

	//修改审核类型
	public function edit_setup($id, $value){
		$db = $this->db;
		DBUtil::update_tb($db, $this->dbtable['setup_audit_type'], $value, "id='$id'");
		$this->file_setup();
		return true;
	}

	//删除审核类型
	public function del_setup($id){
		$db = $this->db;
		DBUtil::Del($db, $this->dbtable['setup_audit_type'], "id='$id'");
		$this->file_setup();
		return true;
	}
}

?>