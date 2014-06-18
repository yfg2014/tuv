<?php
!defined('IN_SUPU') && exit('Forbidden');
GrepUtil::InitGP(array('item','ok'));

//申请变更类别***************
if($ok=='ok')
{
$cache = "<?php\r\n";
$cache .= "\$setup_hr_sv_menber=array(\r\n";
foreach($item as $v)
{
	if($v!='')
	{
		$hr = $db->get_one("SELECT id FROM hr_information WHERE username='$v'");
		if($hr['id'] != '')
		{
			$cache .= "\"$hr[id]\"=>\"$v\",\r\n";
		}
		else
		{
			$error_name .= $v.'&nbsp;';
		}
	}
}
$cache .= "\r\n);\r\n?>";
File::writeFile(SET_DIR.'/setup_hr_sv_menber.php', $cache);

}
include(SET_DIR."setup_hr_sv_menber.php");
include T_DIR.'header.htm';
if($error_name != '')
{
	echo '<br><Br><p align=center>错误的人员姓名'.$error_name.'</p>';
}

?>
<script language="JavaScript">
function addfile()
	{
         var str='<br><input name="item[]" type="text" style="width: 100; height: 18" value="">';
		 document.getElementById("mycard").insertAdjacentHTML("beforeEnd",str);
    }
</script>
<table>
	<tr>
		<td height="10">&nbsp;</td>
	</tr>
</table>
<table width="450" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#0066FF">
 <form method="post" name="input" action="" OnSubmit="return  submitcheck();">
            <tr bgcolor="#FFFFFF">
              <td height="24" colspan="2" ><font color="#003399">信息设置 -&gt; 客服责任人 </font></td>
            </tr>
            <tr bgcolor="#F2F2F2">
              <td height="24" align="right" width="100"><font color="#003399">客服责任人姓名：</font></td>

              <td  id="mycard">

<?php
if($setup_hr_sv_menber!='')
{
foreach($setup_hr_sv_menber as $value)
{
?>
			  <input name="item[]" type="text" style="width: 100; height: 18" value="<?php echo $value;?>"><br>
<?php
}}
else
{?>
<input name="item[]" type="text" style="width: 100; height: 18" value="<?php echo $value;?>">
<?php
}
?>
			  </td>
            </tr>


            <tr bgcolor="#F2F2F2">
              <td height="24" colspan="2" align="right"><div align="center">
		<input type="hidden" name="ok" value="ok">

		<input type="button" value=" 增 加 " onclick="addfile()" />
		<input type="submit" value=" 确 定 " name="submit"></div></td>
            </tr>
  </form>
</table>
<?php
include T_DIR.'footer.htm';
?>