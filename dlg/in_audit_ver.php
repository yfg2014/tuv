<?php
include '../include/globals.php';
include '../include/setup/setup_audit_ver.php';

GrepUtil::InitGP(array('js','iso'));

if(isset($js) && $js == 'ok'){
header("Content-Type:text/html;charset=UTF-8");
echo <<<EOT
function get_bz(obj,width,height,callback){
		$('<div style="width:' + width + 'px;height:' + height + 'px;" id="mod"></div>').modal({
			escClose:true,
			close:true,
			closeHTML:"<a href='#'><span style='float:right;cursor:pointer;color:red;'>关闭</span></a>",
			onShow:function(dialog){
				$.post('./dlg/in_audit_ver.php',{iso:obj.iso},function(r){
					var oo = $.parseJSON(r);
					var tb = '<table width="260" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#0066FF" style="margin-top:5px;"><tr bgcolor="#F2F2F2"><td width="34" align="center"><font color="#003399">选择</font></td><td width="40" height="20"  align="center"><font color="#003399">编码</font></td><td width="217" align="center"><font color="#003399">标准</font></td></tr>';					
					for(var i = 0;i < oo.length;i++){
						tb += '<tr bgcolor="#EAF4EA"><td align="center"><input src="./frontEnd/images/button_edit.gif" type="image" /></td><td align="center">' + oo[i].code +'</td><td>' + oo[i].msg +'</td></tr>';							
					}
					tb += '</table>';
					$(tb).find('input').click(function(){callback({o1:this,o2:obj.pos})}).end().appendTo('#mod');
				});
			},
			containerCss:{width : width + "px",height : height + "px"},
			onClose: function (dialog) {
				$("#simplemodal-container,#simplemodal-overlay").hide();
				$.modal.close();
			},
			minHeight:width,
			minWidth:height
		});
}


EOT;
exit;
}
// 输出js部分结束

$arr = array();
foreach($setup_audit_ver as $v){
	if($v['iso'] == $iso){
		$arr[] = array('code' => $v['code'],'msg' => $v['msg']);
	}
}
echo get_magic_quotes_gpc() ? stripslashes(json_encode($arr)) : json_encode($arr);
?>