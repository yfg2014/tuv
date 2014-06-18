<?php
/**
 * 分页类
 * @author Tom
 *
 *
 */
class Page {
	public		$count; // 记录数量合计
	public 		$nav; // 导航
	protected 	$page; // 当前页
	protected 	$numofpage; // 合计页
	protected 	$url; // 分页URL
	protected 	$db_perpage; // 每页记录数
	protected 	$result; // 结果集数组
	protected 	$limit; // 查询限制范围
	protected 	$db; // 数据库连接类句柄
	protected 	$sql;
	protected 	$setup_htfrom;
	protected 	$setup_audit_type;
	protected 	$dbtable;
	protected   $params=array();

	function __construct($url,$sql,$params = array()) {
		$db_perpage = 0;
		global $db,$page,$db_perpage,$setup_htfrom,$dbtable;
		$this->db = $db;
		$this->dbtable = $dbtable;
		$this->sql = $sql;
		$rt = $this->getTotal();
		$numofpage = ceil($rt/$db_perpage);
		$page > $numofpage && $page = $numofpage;
		(int)$page < 1 && $page = 1;
		$this->count = $rt;
		$this->nav = $this->numofpage($rt,$page,$numofpage,$url);
		$this->limit = "LIMIT ".($page-1)*$db_perpage.",$db_perpage";
		$this->setup_htfrom = $setup_htfrom;
		$this->setup_audit_type = $setup_audit_type;
		$this->params=$params;
	}

	/**
	 * 得到总记录数
	 * @return integer $count
	 */
	function getTotal(){
		$rt = $this->db->get_one($this->sql['count']);
		if(!isset($rt['sum'])){
			exit("系统级错误，未提供合法查询参数分页");
		}
		return $rt['sum'];
	}

	function getPageData() {
		$forum_guest = array();
		$sql_guest = $this->sql['data']." $this->limit";
		$quer_guest	= $this->db->query($sql_guest);
		while($forum_guest = $this->db->fetch_array($quer_guest))
		{
		   if(count($this->params)==0)
		   {
			$tmp = $this->resultFilter($this->resultFilterBefor($forum_guest));
			}
			else
			{
			$tmp = $this->resultFilterCm($this->resultFilterBefor($forum_guest),$this->params['search']);
			}
			$tmp && $arr[] = $tmp;
		}
		return $arr;
	}
	/**
	 * 得到分页内容
	 *
	 * @param integer $count 数量合计
	 * @param integer $page 当前页
	 * @param integer $numofpage 合计页
	 * @param string $url 分页url
	 * @param integer $max
	 * @return string
	 */

	function numofpage($count,$page,$numofpage,$url,$max=null,$ajaxurl='') {
		global $tablecolor;
		$total = $numofpage;
		if (!empty($max)) {
			$max = (int)$max;
			$numofpage > $max && $numofpage = $max;
			$page > $max && $page = $max;
		}
		if ($numofpage <= 1 || !is_numeric($page)){
			return '';
		}else{
			list($url,$mao) = explode('#',$url);
			$mao && $mao = '#'.$mao;
			$pages = "<div class=\"pages\"><a href=\"{$url}page=1$mao\" class=\"b\"".($ajaxurl ? " onclick=\"return ajaxview('{$ajaxurl}page=1')\"" : '').">&laquo;</a>";
			for ($i = $page-3; $i <= $page-1; $i++) {
				if($i<1) continue;
				$pages .= "<a href=\"{$url}page=$i$mao\"".($ajaxurl ? " onclick=\"return ajaxview('{$ajaxurl}page=$i')\"" : '').">$i</a>";
			}
			$pages .= "<b>$page</b>";
			if ($page < $numofpage) {
				$flag = 0;
				for ($i = $page+1; $i <= $numofpage; $i++) {
					$pages .= "<a href=\"{$url}page=$i$mao\"".($ajaxurl ? " onclick=\"return ajaxview('{$ajaxurl}page=$i')\"" : '').">$i</a>";
					$flag++;
					if ($flag == 4) break;
				}
			}
			$pages .= "<a href=\"{$url}page=$numofpage$mao\" class=\"b\"".($ajaxurl ? " onclick=\"return ajaxview('{$ajaxurl}page=$numofpage')\"" : '').">&raquo;</a><span class=\"pagesone\"><span>Pages: $page/$total</span><input type=\"text\" size=\"3\" onkeydown=\"javascript: if(event.keyCode==13){".($ajaxurl ? "ajaxview('{$ajaxurl}page='+this.value);" : " location='{$url}page='+this.value+'{$mao}';")."return false;}\"><button onclick=\"javascript: ".($ajaxurl ? "ajaxview('{$ajaxurl}page='+this.previousSibling.value);" : " location='{$url}page='+this.previousSibling.value+'{$mao}';")."return false;\">Go</button></span></div>";
			return $pages;
		}
	}
	/**
	 * 结果预处理
	 * @param array $result
	 * @return array $result
	 */
	protected function resultFilterBefor($result){
		if($result['htfrom']){
				$result['htfrom'] = Cache::cache_htfrom($result['htfrom']);
		}
		if($result['audit_type']){
				$result['audit_type'] = Cache::cache_audit_type($result['audit_type']);
		}
		if($result['zs_online']){
				$result['zs_online'] = Cache::cache_Certification_online($result['zs_online']);
		}
		if($result['zuzhi_id']){
			$result['eiregistername'] = Cache::cache_company($result['zuzhi_id']);
		}
		if($result['ht_id']){
			$result['htcode'] = Cache::cache_htcode($result['ht_id']);
		}
		if($result['htxm_id']){
			$result['htxmcode'] = Cache::cache_htxmcode($result['htxm_id']);
		}
		if($result['finance_item']){
			$result['finance_item'] = Cache::cache_Finance_item($result['finance_item']);
		}
		if($result['sex']){
			$result['sex'] = Cache::cache_sex($result['sex']);
		}
		if($result['zaizhi']){
			$result['zaizhi'] = Cache::cache_zaizhi($result['zaizhi']);
		}
		if($result['product']){
			$result['product'] = Cache::cache_product($result['product']);
		}
		if($result['coverFields']){
			$result['coverFields'] = str_replace("\n",'<br>',$result['coverFields']);
		}
		if($result['renzhengfanwei']){
			$result['renzhengfanwei'] = str_replace("\n",'<br>',$result['renzhengfanwei']);
		}
		if($result['audit_ver']){
			$result['audit_ver'] = Cache::cache_audit_ver($result['audit_ver']);
		}
		return $result;
	}

	/**
	 * 结果过滤处理
	 * @param array $result
	 * @return array $result
	 */
	protected function resultFilter($result){
		return $result;
	}
	protected function resultFilterCm($result,$params){
		return $result;
	}
}
?>