<?php
class postmob
{
	var $mob;
	var $content;
	var $uid;
	var $pwd;
	var $badword;
	var $tmoney;
	var $errno="ok"; //1、外网连接失败；2、短信内容字数超出；3、群发手机数量超出；4、包含禁用词语；
	function neturl() //是否连接外网
	{
		$fp = fsockopen("www.baidu.com",80,$errno,$errstr,5);
		if(!$fp)
		{
		$this->errno = 1;
		return false;
		}
	}
	function outlen($content) //字符数量是否超出70个汉字，每个英文标点算一个汉字
	{
		$strl=strlen($content);
		if($strl>110)
		{
			$this->errno = 2;
			return false;
		}
	}
	function outmobnum($mob) //群发手机数量是否超出98个
	{
		$mobnum=count(explode(',',$mob));
		if($mobnum>98)
		{
			$this->errno = 3;
			return false;
		}
	}
	function badwords($content) //是否包含禁用词语
	{
		include("keyword.php");
		foreach($keywd1 as $k)
		{
			if(strpos($content,$k)!==false)
			{

				$badmsg=$k.','.$badmsg;
			}
		}
		foreach($keywd2 as $k)
		{
			if(strpos($content,$k)!==false)
			{

				$badmsg=$k.','.$badmsg;
			}
		}
		foreach($keywd3 as $k)
		{
			if(strpos($content,$k)!==false)
			{

				$badmsg=$k.','.$badmsg;
			}
		}
		foreach($keywd4 as $k)
		{
			if(strpos($content,$k)!==false)
			{

				$badmsg=$k.','.$badmsg;
			}
		}
		foreach($keywd5 as $k)
		{
			if(strpos($content,$k)!==false)
			{

				$badmsg=$k.','.$badmsg;
			}
		}
		foreach($keywd6 as $k)
		{
			if(strpos($content,$k)!==false)
			{

				$badmsg=$k.','.$badmsg;
			}
		}
		foreach($keywd7 as $k)
		{
			if(strpos($content,$k)!==false)
			{

				$badmsg=$k.','.$badmsg;
			}
		}
		//$badmsg='1';
		if($badmsg!='')
		{
			$this->errno = 4;
			$this->badword = $badmsg;
		}
	} //禁用词语END

	function postcontent($uid,$pwd,$mob,$content)
	{
		$mob = str_replace('；',',',str_replace(';',',',str_replace('，',',',$mob)));
		$this->neturl();
		$this->outlen($content);
		$this->outmobnum($mob);
		$this->badwords($content);
		if($this->errno=="ok")
		{
			mysql_query("INSERT INTO sys_mob_log (hr_id,idname,postmob,postcontent,posttime) VALUES ('$_SESSION[userid]','$_SESSION[username]','$mob','$content',now())");
			$sendurl="http://service.winic.org:8009/sys_port/gateway/?id=$uid&pwd=$pwd&to=".$mob."&content=".$content."&time=";
			$xhr=new COM("MSXML2.XMLHTTP");
			$xhr->open("GET",$sendurl,false);
			$xhr->send();
			$telmsg=  $xhr->responseText;
			$telmsg=explode('/',$telmsg);

			$backmsg=$telmsg[0];
			$this->errno=$backmsg;
			$this->tmoney=$telmsg[3];
		}
		$this->error();
	}
	function error()
	{
		if($this->errno!="ok")
		{
			switch($this->errno)//1、外网连接失败；2、短信内容字数超出；3、群发手机数量超出；4、包含禁用词语；
			{
			case '1' : $smsg='外网连接失败';break;
			case '2' : $smsg='短信内容字数超出60汉字';break;
			case '3' : $smsg='群发手机数量超出98个';break;
			case '4' : $smsg='包含禁用词语'.$this->badword;break;

			case '000' : $smsg='发送成功！';break;
			case '-01' : $smsg='当前账号余额不足！';break;
			case '-02' : $smsg='当前用户ID错误！';break;
			case '-03' : $smsg='当前密码错误！';break;
			case '-04' : $smsg='参数不够或参数内容的类型错误！';break;
			case '-05' : $smsg='手机号码格式不对！';break;
			case '-06' : $smsg='短信内容编码不对！';break;
			case '-07' : $smsg='短信内容含有敏感字符！';break;
			case "null" : $smsg='无接收数据';break;
			case '-09' : $smsg='系统维护中..';break;
			case '-10' : $smsg='手机号码数量超长！';break;
			case '-11' : $smsg='短信内容超长！';break;
			case '-12' : $smsg='其它错误！';break;
			default : $smsg='未知错误';
			}
			return $smsg;
		}
		else
		{
			return "发送成功";
		}
	}
}
?>