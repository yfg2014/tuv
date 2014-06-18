<?php
class TopSearch
{
	public $search_arr=array();
	public $db;
	public $SearchName;
	public $SearchUrl;
	public $SearchHtml;
	public $SearchSql;
	function __construct($search_arr=array()) {
		global $db;
		$this->db = $db;
		$this->search_arr = $search_arr;
		$this->SearchName();
		$this->SearchUrl();
		$this->SearchHtml();
		$this->SearchSql();
	}

	//返回搜索字段数组
	public function SearchName(){
		foreach($this->search_arr as $k=>$v){
			$SearchName []= $v['name'];
		}
		array_push($SearchName,'m','do','page');
		$this->SearchName = GrepUtil::InitGP($SearchName);
	}

	//返回搜索URL翻页地址
	public function SearchUrl(){
		foreach($this->search_arr as $k=>$v){
			if($v['name'] != '' and $this->SearchName[$v['name']]!=''){
				$SearchUrl = $SearchUrl.$v['name'].'='.urlencode($this->SearchName[$v['name']]).'&';
			}
		}
		$this->SearchUrl = $SearchUrl;
	}
	//返回搜索HTML表单项
	public function SearchHtml()	{
		foreach($this->search_arr as $k=>$v){
			$v['msg'] = '  <span class="top_search">'.$v['msg'].'</span>';
			switch($v['kind']){
			case 'text' : $show_search_str .= $this->searchkind($v['kind'],$v['name'],$v['msg'],$v['width']);break;
			case 'days' : $show_search_str .= $this->searchkind($v['kind'],$v['name'],$v['msg'],$v['width']);break;
			case 'select' : $show_search_str .= $this->searchkind($v['kind'],$v['name'],$v['msg'],$v['width'],$v['arr']);break;
			case 'date1' : $show_search_str .= $this->searchkind($v['kind'],$v['name'],$v['msg'],$v['width']);break;
			case 'date2' : $show_search_str .= $this->searchkind($v['kind'],$v['name'],$v['msg'],$v['width']);break;
			case 'htdate1' : $show_search_str .= $this->searchkind($v['kind'],$v['name'],$v['msg'],$v['width']);break;
			case 'htdate2' : $show_search_str .= $this->searchkind($v['kind'],$v['name'],$v['msg'],$v['width']);break;
			case 't_date': $show_search_str.=$this->searcht_date($v['name'],$v['msg'],$v['width']);break;
			case 'company' : $show_search_str .= $this->search_conpamy($v['name'],$v['msg'],$v['width']);break;
			case 'htcode' : $show_search_str .= $this->search_htcode($v['name'],$v['msg'],$v['width']);break;
			case 'htxmcode' : $show_search_str .= $this->search_htxmcode($v['name'],$v['msg'],$v['width']);break;
			case 'username' : $show_search_str .= $this->search_username($v['name'],$v['msg'],$v['width']);break;
			case 'htfrom': $show_search_str .= $this->search_htfrom($v['name'],$v['msg'],$v['width'],$v['arr']);break;
			case 'province': $show_search_str .= $this->search_province($v['name'],$v['msg'],$v['width'],$v['arr']);break;
			case 'worktype': $show_search_str .= $this->search_worktype($v['name'],$v['msg'],$v['width'],$v['arr']);break;
			case 'certNo' : $show_search_str .= $this->search_certNo($v['name'],$v['msg'],$v['width']);break;
			case 'zd_ren' : $show_search_str .= $this->search_zd_ren($v['name'],$v['msg'],$v['width']);break;
			case 'eiarea': $show_search_str .= $this->search_eiarea($v['name'],$v['msg'],$v['width'],$v['arr']);break;
			case 'product' : $show_search_str .= $this->search_product($v['name'],$v['msg'],$v['width']);break;

			case 'br' : $show_search_str .= $this->searchkind($v['kind']);break;
			}
		}
		$this->SearchHtml = $show_search_str;
	}
	//搜索HTML表单控件类型
	public function searchkind($kind,$name='',$msg='',$width='',$arr=array()){
		switch($kind){
			case 'text' : $search_str = $msg.'：<input type="text" name="'.$name.'" style="width:'.$width.'" >';break;
			case 'days' : $search_str = $msg.'：<input type="text" name="'.$name.'" style="width:'.$width.'" >';break;
			case 'select' :
			$search_str = $msg.'：<select name="'.$name.'" style="width:'.$width.'"><option value=""></option>';
			foreach($arr as $k=>$v){
				if(is_array($v)){
					if($v['online'] == '1'){
						$v = $v['msg'];
						$search_str .= '<option value="'.$k.'">'.$v.'</option>';
					}
				}else{
					$search_str .= '<option value="'.$k.'">'.$v.'</option>';
				}
			}
			$search_str .= '</select>';
			break;
			case 'date1' : $search_str = $msg.'：<input type="text" name="'.$name.'" style="width:'.$width.'" onFocus="return showCalendar(this, \'y-mm-dd\');">';break;
			case 'date2' :
			$search_str = '至<input type="text" name="'.$name.'" style="width:'.$width.'" onFocus="return showCalendar(this, \'y-mm-dd\');">';break;
			case 'htdate1' : $search_str = $msg.'：<input type="text" name="'.$name.'" style="width:'.$width.'" onFocus="return showCalendar(this, \'y-mm-dd\');">';break;
			case 'htdate2' :
			$search_str = '至<input type="text" name="'.$name.'" style="width:'.$width.'" onFocus="return showCalendar(this, \'y-mm-dd\');">';break;
			case 'br' : $search_str = '<br />';break;
			default : $search_str = '';
		}
		return $search_str;
	}

	//======================================//
	//返回搜索SQL字符串
	public function SearchSql(){
		foreach($this->search_arr as $v){
			$p_value = urldecode($this->SearchName[$v['name']]);
			if($p_value!=''){
				//$p_value = Char_cv($p_value); //此处调用外部字符过滤函数，位置include/fun_common.php
				switch($v['kind']){
					case 'company' : $search_str .= $this->search_company_sql($v['sql_field'],$p_value);break;
					case 'htcode' : $search_str .= $this->search_htcode_sql($v['sql_field'],$p_value);break;
					case 'htxmcode' : $search_str .= $this->search_htxmcode_sql($v['sql_field'],$p_value);break;
					case 'username':  $search_str .= $this->search_username_sql($v['sql_field'],$p_value);break;
					case 'htfrom':  $search_str .= $this->search_htfrom_sql($v['sql_field'],$p_value);break;
					case 'province':  $search_str .= $this->search_province_sql($v['sql_field'],$p_value);break;
					case 't_date':  $search_str .= $this->search_sql_kind($v['sql_field'],$v['sql_kind'],$p_value);break;
					case 'worktype': $search_str .= $this->search_worktype_sql($v['sql_field'],$p_value);break;
					case 'certNo' : $search_str .= $this->search_certNo_sql($v['sql_field'],$p_value);break;
					case 'zd_ren' : $search_str .= $this->search_zd_ren_sql($v['sql_field'],$p_value);break;
					case 'htdate1' : $search_str .= $this->search_htdate1_sql($v['sql_field'],$p_value);break;
					case 'htdate2' : $search_str .= $this->search_htdate2_sql($v['sql_field'],$p_value);break;
					case 'eiarea':  $search_str .= $this->search_eiarea_sql($v['sql_field'],$p_value);break;
					case 'product' : $search_str .= $this->search_product_sql($v['sql_field'],$p_value);break;
					case 'days' : $search_str .= $this->search_days_sql($v['sql_field'],$p_value);break;
					default : $search_str .= $this->search_sql_kind($v['sql_field'],$v['sql_kind'],$p_value);

				}
			}

		}
		$this->SearchSql = $search_str;
	}

	//搜索方法
	public function search_sql_kind($sql_field,$sql_kind,$p_value){
		switch($sql_kind){
			case '%like' : $temp=$temp." and ".$sql_field." like'%$p_value'";break;
			case 'like%' : $temp=$temp." and ".$sql_field." like'$p_value%'";break;
			case '%like%' : $temp=$temp." and ".$sql_field." like'%$p_value%'";break;
			case 'in' : $temp=$temp." and ".$sql_field." in('$p_value')";break;
			case 'not in' : $temp=$temp." and ".$sql_field."not in('$p_value')";break;
			case '' : $temp=$temp;break;
			default :  $temp=$temp." and ".$sql_field.' '.$sql_kind." '$p_value'";
		}

		return $temp;
	}

	/***********   特殊搜索项单独构造  ************/
	
	//搜索天数
	function search_days_sql($sql_field,$p_value){
		$date = date("Y-m-d",strtotime(date("Y-m-d"))+(24*3600*$p_value));
		return " AND $sql_field>='".date("Y-m-d")."' AND $sql_field<='$date'";
	}

	//搜索企业名称
	function search_conpamy($name,$msg,$width){
		return $this->searchkind('text',$name,$msg,$width);
	}
	//企业搜索
	public function search_company_sql($sql_field,$p_value)	{
		$qy_id = array();
		$sql = "SELECT id FROM mk_company WHERE eiregistername LIKE '%$p_value%' LIMIT 10";
		$query = $this->db->query($sql);
		while($qy = $this->db->fetch_array($query)){
			$qy_id [] = $qy['id'];
		}
		$p_value = implode("','",$qy_id);
		$sql_kind = 'in';
		return $this->search_sql_kind($sql_field,$sql_kind,$p_value);
	}
		//搜索产品名称
	function search_product($name,$msg,$width){
		return $this->searchkind('text',$name,$msg,$width);
	}
	//产品搜索
	public function search_product_sql($sql_field,$p_value)	{
		$pr_id = array();
		$sql = "SELECT code FROM setup_product WHERE msg  LIKE '%$p_value%' LIMIT 20";
		$query = $this->db->query($sql);
		while($pr = $this->db->fetch_array($query)){
			$pr_id [] = $pr['code'];
		}
		$p_value = implode("','",$pr_id);
		$p_value == '' && $p_value='0';
		$sql_kind = 'in';
		return $this->search_sql_kind($sql_field,$sql_kind,$p_value);
	}

	//搜索合同号
	function search_htcode($name,$msg,$width){
		return $this->searchkind('text',$name,$msg,$width);
	}
	//合同号搜索
	public function search_htcode_sql($sql_field,$p_value)	{
		$sql = "SELECT id FROM ht_contract WHERE htcode='$p_value' LIMIT 1";
		$rst = $this->db->get_one($sql);
		$p_value = $rst['id'];
		$sql_kind = '=';
		return $this->search_sql_kind($sql_field,$sql_kind,$p_value);
	}
	//搜索合同项目号
	function search_htxmcode($name,$msg,$width){
		return $this->searchkind('text',$name,$msg,$width);
	}
	//合同项目号搜索
	public function search_htxmcode_sql($sql_field,$p_value){
		$arr = array();
		switch ($sql_field){
			case 'id':$field = 'ht_id';break;
			default:$field = 'id';
		}
		$sql = "SELECT {$field} FROM ht_contract_item WHERE htxmcode LIKE '%{$p_value}%'";
		$query = $this->db->query($sql);
		while ($rows = $this->db->fetch_array($query)){
			$arr []= $rows[$field];
		}
		$p_value = implode("','",$arr);
		$sql_kind = 'in';
		return $this->search_sql_kind($sql_field,$sql_kind,$p_value);
	}
	//人员姓名
	function search_username($name,$msg,$width){
		return $this->searchkind('text',$name,$msg,$width);
	}
	//人员姓名搜索
	public function search_username_sql($sql_field,$p_value)	{
		$qy_id = array();
		$sql = "SELECT id FROM hr_information  WHERE username  like '%$p_value%' LIMIT 10";
		$query = $this->db->query($sql);
		while($hr = $this->db->fetch_array($query)){
			$hr_id [] = $hr['id'];
		}
		$p_value = implode("','",$hr_id);
		$sql_kind = 'in';
		return $this->search_sql_kind($sql_field,$sql_kind,$p_value);
	}
	//人员来源
	function search_htfrom($name,$msg,$width,$p_value){
		return $this->searchkind('select',$name,$msg,$width,$p_value);
	}
	//人员来源搜索
	public function search_htfrom_sql($sql_field,$p_value)	{
		$qy_id = array();
		$sql = "SELECT id FROM hr_information  WHERE htfrom  = '$p_value'";
		$query = $this->db->query($sql);
		while($hr = $this->db->fetch_array($query)){
			$hr_id [] = $hr['id'];
		}
		$p_value = implode("','",$hr_id);
		$sql_kind = 'in';
		return $this->search_sql_kind($sql_field,$sql_kind,$p_value);
	}
	//人员地区
	function search_province($name,$msg,$width,$p_value){
		return $this->searchkind('select',$name,$msg,$width,$p_value);
	}
	//人员地区
	public function search_province_sql($sql_field,$p_value)	{
		$qy_id = array();
		$sql = "SELECT id FROM hr_information  WHERE province  = '$p_value'";
		$query = $this->db->query($sql);
		while($hr = $this->db->fetch_array($query)){
			$hr_id [] = $hr['id'];
		}
		$p_value = implode("','",$hr_id);
		$sql_kind = 'in';
		return $this->search_sql_kind($sql_field,$sql_kind,$p_value);
	}
		//企业地区
	function search_eiarea($name,$msg,$width,$p_value){
		return $this->searchkind('select',$name,$msg,$width,$p_value);
	}
		//企业地区
	public function search_eiarea_sql($sql_field,$p_value)	{
		$qy_id = array();
		$sql = "SELECT id FROM mk_company  WHERE eiarea_code  = '$p_value'";

		$query = $this->db->query($sql);
		while($cm = $this->db->fetch_array($query)){
			$cm_id [] = $cm['id'];
		}
		$p_value = implode("','",$cm_id);
		$sql_kind = 'in';
		return $this->search_sql_kind($sql_field,$sql_kind,$p_value);
	}
	//提醒日期
	public function searcht_date($name,$msg,$width)
	{
	   $data_arr = array( );
	   for($dvalue=-6;$dvalue<=6;$dvalue++)
	   {
	   	$data_now=date("Y-m",mktime(0,0,0,date("m")+$dvalue,date("d"),date("Y")));

	   	$data_arr[$data_now]=$data_now;
	   }
		return $this->searchkind('select',$name,$msg,$width,$data_arr);
	}
	//专兼职
	function search_worktype($name,$msg,$width,$p_value){
		return $this->searchkind('select',$name,$msg,$width,$p_value);
	}
	//认证变更的审核类型 监察类型
	function search_audit_type1($name,$msg,$width,$p_value){
	    foreach($p_value as $key => $value )
		{
		   if($key!='1002' && $key!='1003' &&  $key!='1004')
		   {
		      unset($p_value[$key]);

		   }
		}
		return $this->searchkind('select',$name,$msg,$width,$p_value);
	}
	//专兼职搜索
	public function search_worktype_sql($sql_field,$p_value)	{
		$qy_id = array();
		$sql = "SELECT id FROM hr_information WHERE worktype = '$p_value'";
		$query = $this->db->query($sql);
		while($hr = $this->db->fetch_array($query)){
			$hr_id [] = $hr['id'];
		}
		$p_value = implode("','",$hr_id);
		$sql_kind = 'in';
		return $this->search_sql_kind($sql_field,$sql_kind,$p_value);
	}

	//搜索证书号
	function search_certNo($name,$msg,$width){
		return $this->searchkind('text',$name,$msg,$width);
	}
	//证书号搜索
	public function search_certNo_sql($sql_field,$p_value)	{
		$sql = "SELECT id FROM zs_cert  WHERE certNo LIKE '%".$p_value."%' LIMIT 50";
		$query = $this->db->query($sql);
		while($zs_cert = $this->db->fetch_array($query)){
			$zs_id [] = $zs_cert['id'];
		}
		$p_value = implode("','",$zs_id);
		$sql_kind = 'in';
		return $this->search_sql_kind($sql_field,$sql_kind,$p_value);
	}

	//合同受理人
		function search_zd_ren($name,$msg,$width){
		return $this->searchkind('text',$name,$msg,$width);
	}
		//证书号搜索
	public function search_zd_ren_sql($sql_field,$p_value)	{
		$sql = "SELECT id FROM ht_contract  WHERE zd_ren LIKE '%".$p_value."%' ";
		$query = $this->db->query($sql);
		while($ht = $this->db->fetch_array($query)){
			$ht_id [] = $ht['id'];
		}
		$p_value = implode("','",$ht_id);
		$sql_kind = 'in';
		return $this->search_sql_kind($sql_field,$sql_kind,$p_value);
	}
			//证书号搜索
	public function search_htdate1_sql($sql_field,$p_value)	{
		$sql = "SELECT id FROM ht_contract  WHERE htdate >= '".$p_value."' ";
		$query = $this->db->query($sql);
		while($ht = $this->db->fetch_array($query)){
			$ht_id [] = $ht['id'];
		}
		$p_value = implode("','",$ht_id);
		$sql_kind = 'in';
		return $this->search_sql_kind($sql_field,$sql_kind,$p_value);
	}
				//证书号搜索
	public function search_htdate2_sql($sql_field,$p_value)	{
		$sql = "SELECT id FROM ht_contract  WHERE htdate <= '".$p_value."' ";
		$query = $this->db->query($sql);
		while($ht = $this->db->fetch_array($query)){
			$ht_id [] = $ht['id'];
		}
		$p_value = implode("','",$ht_id);
		$sql_kind = 'in';
		return $this->search_sql_kind($sql_field,$sql_kind,$p_value);
	}

}
?>