<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/cache.inc.php');

	$url = "http://www.cnca.gov.cn/cnca/zwxx/xwdt/zxtz/default.shtml"; //目标站2

	//判断是否连网
	$fp = fsockopen("www.baidu.com",80,$errno,$errstr,5);
	if (!$fp) {
		echo "<script type=\"text/javascript\" >alert('未连接网络，无法获取最新数据');</script>";
		Url::goto_url('main_right.php');
		exit;
	} else {
		fclose($fp);
		$fcontents = file_get_contents($url);
	}


	eregi("<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"  >(.*)background=\"images/lm_bj2.jpg\"></td>", $fcontents, $regs);

	$regs[1]=str_replace("<td width=\"13\" height=\"25\" align=\"center\"  class=\"borderBotom \"><img src=\"/cnca/images09/dian.jpg\" width=\"3\" height=\"3\" /></td>","",$regs[1]);
	$regs[1]=str_replace("<td align=\"left\"","<td align=\"left\" height=\"22\"",$regs[1]);
	$regs[1]=str_replace("<td height=\"10\"></td>","",$regs[1]);
	$regs[1]=str_replace("<tr>","<tr bgcolor=\"#FFFFFF\">",$regs[1]);
	$regs[1]=str_replace("class=\"borderBotom \"><a href="," width=\"80%\" class=\"borderBotom \"><a href=",$regs[1]);
	$regs[1]=str_replace("align=\"left\" height=\"22\" class=\"borderBotom\">2011","align=\"center\">201",$regs[1]);
	$regs[1]=str_replace("href=\"/cnca/zwxx/xwdt","href=\"http://www.cnca.gov.cn/cnca/zwxx/xwdt",$regs[1]);



//实例化缓存类
	$cachedir = S_DIR.'include/setup/';	//设定缓存目录
	$filename = 'setup_news_two.tmp';		//设定缓存目录
	$lifetime = 3*60*60;	 //设定在多长时间内点击会更新
	$cache = new CacheStr($cachedir,$filename,$lifetime); //省略参数即采用缺省设置, $cache = new Cache($cachedir);
	$cache->load(); //装载缓存,缓存有效则不执行以下页面代码



include TEMP.'header.htm';
include TEMP.'setup/setup_news_one.htm';
include TEMP.'footer.htm';

	$cache->write();//写入缓存


?>