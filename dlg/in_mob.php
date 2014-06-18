<HEAD>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8">
<META HTTP-EQUIV=Pragma CONTENT=no-cache>
<META HTTP-EQUIV=Cache-Control CONTENT=no-cache>
<META HTTP-EQUIV=Cache-Control CONTENT=no-store>
<META HTTP-EQUIV=Expires CONTENT=0>
<title>手机短信群发</title>
<LINK href="../frontEnd/css/scss.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/javascript" src="../frontEnd/js/jquery-1.4.2.min.js"></script>
<script language="JavaScript">
	$(function(){
		var _area=$('textarea#content');
		var _info=_area.next();
		var _submit=_info.find('#button');
		var _max=108;
		var _val,_cur,_count,_warn;
		_submit.attr('disabled',true);
		_area.bind('keyup change',function(){
		_submit.attr('disabled',false);
		if(_info.find('span').size()<1){
		_info.append('<span>&nbsp;你还可以输入<em>'+ _max +'</em>个字符</span>');
		}
		_val=$(this).val();
		_cur=_val.replace(/[\u0391-\uFFE5]/g,'aa').length;
		_count=_info.find('em');
		_warn=_info.find('font');

		if(_cur==0){
		_count.text(_max);
		_submit.attr('disabled',true);
		}else if(_cur<_max){
		_count.text(_max-_cur);
		_warn.text('不区分中英文字符数');
		}else{
		_count.text(0);
		_warn.text('不可再输入!');
		$(this).val(_val.substring(0,_max));
	}
	});
	});
function checkform(){
	var mob;
	var txtstr;
	var txtstrlen;
	mob = $("#mob").val();
	txtstr = $("#content").val();
	txtstrlen = txtstr.replace(/[\u0391-\uFFE5]/g,"aa").length;
	if(txtstrlen > 108){
		alert( "短信内容长度超出108！");
		return false;
	}else if(mob.length == 0){
		alert( "手机号码不能为空！");
		return false;
	}else if(txtstr.length < 1){
		alert( "短信内容不为空！");
		return false;
	}else{
		document.forms[0].submit();
	}

}
</script>
</HEAD>

<style type="text/css">
<!--
body {	background-color: #D5D5D5;}
body {	margin-left:0.1cm;}
-->
</style>
<base target="_self">

<?php
include('../include/class_mob.php');
include("../include/keyword.php");

$oksave = $_POST['oksave'];


if ($oksave!="")
{
    $mob = $_POST['mob'];
	$content = iconv("utf-8","gbk",$_POST['content']);

	$uid="supusoft";		//分配账号
	$pwd="123456";			//密码
	//$mob='13815259105';
	//$content="111";
	$p=new postmob();
	$p->postcontent($uid,$pwd,$mob,$content);

	$smsg=$p->error(); //返回信息
	$tmoney=$p->tmoney; //余额
?>
<table width="450" height="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#0066FF">
<tr bgcolor="#FFFFFF">
<td style="padding:5px 0;" valign="top">
<font color="#003399">&nbsp;信息设置 -&gt;手机短信群发，信息返回状态：</font>
<br><br>
&nbsp;<font color=blue><?php echo $mob.$smsg.'<br/>&nbsp;余额'.$tmoney;?><a href="in_mob.php">&nbsp;&nbsp;返回</a></font><br/>
</td>
</tr>
</table>
<?php }else{ ?>
<table width="470" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#0066FF">
<form method="post" name="winput" action="">
<tr bgcolor="#FFFFFF">
<td style="padding:5px 0;">
<font color="#003399">&nbsp;信息设置 -&gt;手机短信群发</font>
</td>
<tr bgcolor="#FFFFFF">
<td height="24" style="padding:5px 0;">
&nbsp;<font color=blue>手机号码：</font><br>
&nbsp;<INPUT TYPE="text" NAME="mob" id="mob" value="" style="width:360;height:20;">
</td>
</tr>
<tr bgcolor="#FFFFFF">
<td height="250"  style="padding:5px 0;">
<input type="hidden" name="oksave"  value="oksave" />
&nbsp;<font color=blue>短信内容：</font><br><br>
&nbsp;<textarea name="content" id="content" style="width:400;height:200;"></textarea>
<p>&nbsp;<input type="button" id="button" value=" 发送 " onclick="checkform()" /></p></td>
</tr>
</form>
<tr bgcolor="#FFFFFF">
	<td height="40" style="padding:5px 0;">
	&nbsp;<font color="#999999">说明：发送短信到多个手机时，请用半角逗号‘,’分割手机号码</font>
	<br>
	&nbsp;<font color="#999999">例如：13122229999,13188887777,13566667777</font>
	</td>
</tr>
</table>
<?php } ?>
