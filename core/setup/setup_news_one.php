<?php
!defined('IN_SUPU') && exit ('Forbidden');
include (S_DIR . 'include/cache.inc.php');

	$url = "http://www.ccaa.org.cn/ccaa/tzgg/tz/default.shtml"; //采集的目标站1

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

	//截取数据
	eregi("<table width=\"540\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"word_hui\">(.*)<table width=\"540\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"word_hui\">", $fcontents, $regs);

	//过滤和替换
	$regs[1] = str_replace("<td width=\"27\" align=\"center\">·</td>", "", $regs[1]);
	$regs[1] = str_replace(" width=\"440\"", " height=\"22\" width=\"80%\"", $regs[1]);
	$regs[1] = str_replace(" width=\"73\"", " width=\"20%\" align=\"center\"", $regs[1]);
	$regs[1] = str_replace("<tr>", "<tr bgcolor=\"#FFFFFF\">", $regs[1]);
	$regs[1] = str_replace("><a class=hui href=\"", "><a class=hui href=\"http://www.ccaa.org.cn", $regs[1]);

	//实例化缓存类
	$cachedir = S_DIR . 'include/setup/'; //设定缓存目录
	$filename = 'setup_news_one.tmp'; //设定缓存目录文件
	$lifetime = 3*60*60; //设定在多长时间内点击会更新
	$cache = new CacheStr($cachedir, $filename, $lifetime); //省略参数即采用缺省设置, $cache = new Cache($cachedir);
	$cache->load(); //装载缓存,缓存有效则不执行以下页面代码

	include TEMP . 'header.htm';
	include TEMP . 'setup/setup_news_one.htm';
	include TEMP . 'footer.htm';

	$cache->write(); //写入缓存
?>