<HEAD>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8">
<META HTTP-EQUIV=Pragma CONTENT=no-cache>
<META HTTP-EQUIV=Cache-Control CONTENT=no-cache>
<META HTTP-EQUIV=Cache-Control CONTENT=no-store>
<META HTTP-EQUIV=Expires CONTENT=0>
<title>邮件群发</title>
<link rel="stylesheet" href="../frontEnd/css/scss.css" />
</HEAD>

<base target="_self">

<?php
set_time_limit(600);
include("../include/globals.php");
include("../include/module/Hr_information.php");
include("../include/fckeditor/fckeditor.php") ;
include("../include/phpmailer/class.phpmailer.php");

//判断是否连接网络
$fp = fsockopen("www.baidu.com",80,$errno,$errstr,5);
if (!$fp) {
	echo "<script type=\"text/javascript\" >alert('未连接网络，无法短信');</script>";
	exit;
}

//获取邮箱信息
	$id = $_GET['id'];
	$ren	= new hr_information();
	$value	= $ren->query($id);

if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$",$value['email']))
{
	echo "你的邮箱格式不正确,正确格式为 aaa@xx.com，请设置邮箱</font></a>";exit;
}

//实例化 FCK
	$oFCKeditor = new FCKeditor('email_message') ;
	$oFCKeditor->BasePath	= "../include/fckeditor/" ;
	//oFCKeditor->Value	="" ;
	$oFCKeditor->Width = '95%' ;
	$oFCKeditor->Height = '230' ;
	$oFCKeditor->ToolbarSet = "Basic";

//post信息
$oksave = $_POST['oksave'];


if ($oksave!="")
{
	$email_to = $_POST['email_to'];  //收件邮箱
	$email_subject = $_POST['email_subject'];		//标题
	$email_message = str_replace("\\","",$_POST['email_message']);  //内容

    //引用mail类

	//配置邮件发送 PHPMailer类
	$mail = new PHPMailer();

	// 设置使用 SMTP
	$mail->IsSMTP();
	$mail->Host = $value['m_server'];
	$mail->SMTPAuth = true;
	$mail->Username = $value['email'];
	$mail->Password = $value['m_password'];

	$mail->From = $value['email'];
	$mail->FromName = $value['email'];

	//收信邮箱
	$address_email=explode(',',$email_to);
	foreach($address_email as $m){
		$mail->AddAddress("$m", "");
	}
	$mail->MsgHTML($email_message);

	$mail->WordWrap = 50;

	// 设置邮件格式为 HTML
	$mail->IsHTML(true);
	// 标题
	$mail->Subject = $email_subject;
	// 内容
	$mail->Body  = $email_message;
	if(!$mail->Send()){
		echo "<br/><font color=red size=+1>&nbsp;发送失败!</font><br/><br/>";
		echo "<font color=red size=+1>&nbsp;失败原因: 企业联系人邮箱或密码错误！</font><a href=\"in_mail.php?id={$id}\">&nbsp;&nbsp;返回</a>";
		exit;
	}
	else{
		$email_to = str_replace(",","&nbsp;<br/>",$email_to);
		echo "<font color=red size=+1>您的邮件已成功发送到:<br/>{$email_to}</font><br/>";
		exit;
	}
}

?>
<table width="450" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#0066FF">
	<!--<form method="post" name="winput" action="">-->
	<form method="post" action="" >
	<tr bgcolor="#FFFFFF">
		<td style="padding:5px 0;">
			&nbsp;<font color="#003399">信息设置 -&gt;邮件群发</font><br>
		</td>
	</tr>

	<tr bgcolor="#FFFFFF">
		<td height="24" style="padding:5px 0;">
		&nbsp;<font color=blue>收件地址：</font>&nbsp;<INPUT TYPE="text" NAME="email_to" value="" style="width:320;height:20;">
		</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td height="24"  style="padding:5px 0;">
		&nbsp;<font color=blue>标题：</font>&nbsp;<INPUT TYPE="text" NAME="email_subject" value=""  style="width:320;height:20;">
		</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td height="24"  style="padding:5px 0;">
		&nbsp;<font color=blue>邮件内容：</font><br>
		&nbsp;<?php $oFCKeditor->Create();?>
		</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td  align="center" bgcolor="#F2F2F2">
		<input type="hidden" name="oksave"  value="oksave" />
		<input type="submit" value=" 发送 " /> &nbsp;&nbsp; <INPUT TYPE="reset" value=" 重写 "  >
		</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td style="padding:5px 0;">
			&nbsp;<font color="#999999">企业联系人邮箱：<?php echo $value['email'];?></font><br/>
			&nbsp;<font color="#999999">说明：发送邮件到多个邮箱时，请用半角逗号‘,’分割邮件地址</font><br>
			&nbsp;<font color="#999999">例如：aaa@xx.com,bbb@xx.com,ccc@xx.com</font>
		</td>
	</tr>
	</form>
</table>