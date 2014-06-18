/* $Id : utils.js 5052 2007-02-03 10:30:13Z weberliu $ */

var Browser = new Object();

Browser.isMozilla = (typeof document.implementation != 'undefined') && (typeof document.implementation.createDocument != 'undefined') && (typeof HTMLDocument != 'undefined');
Browser.isIE = window.ActiveXObject ? true : false;
Browser.isFirefox = (navigator.userAgent.toLowerCase().indexOf("firefox") != - 1);
Browser.isSafari = (navigator.userAgent.toLowerCase().indexOf("safari") != - 1);
Browser.isOpera = (navigator.userAgent.toLowerCase().indexOf("opera") != - 1);

var Utils = new Object();

function cleanWhitespace(element)
{
  var element = element;
  for (var i = 0; i < element.childNodes.length; i++) {
   var node = element.childNodes[i];
   if (node.nodeType == 3 && !/\S/.test(node.nodeValue))
     element.removeChild(node);
   }
}

//鼠标悬停移走事件的block显示
function show_block(obj,df_wd,wd_num)
{
	if(df_wd == '' || df_wd == null){
		df_wd = "500px";
	}
	var msg = $('.'+obj).text();
	$('.'+obj).css("display","block");

	if(msg.length >50){
		$('.'+obj).css("width",df_wd);
	}else{
		$('.'+obj).css("white-space","nowrap");
	}
	var wd = $('.'+obj).width();
	if(wd_num != '' && wd_num!=null){
		wd = wd / wd_num;
	}
	$('.'+obj).css("margin-left","-"+wd);
}
function hide_block(obj)
{
	$('.'+obj).css("display","none");
}
//判断浏览器
function IsBrowser(){
	var sAgent = navigator.userAgent.toLowerCase() ;
	if ( sAgent.indexOf("msie") != -1 && sAgent.indexOf("mac") == -1 && sAgent.indexOf("opera") == -1 )
		return "msie" ;
	if ( navigator.product == "Gecko" && !( typeof(opera) == 'object' && opera.postError ) )
		return "gecko";
	if ( navigator.appName == 'Opera')
		return "opera" ;
	if ( sAgent.indexOf( 'safari' ) != -1 )
		return "safari";
	return false ;
}
//项目详细
function ShowDlog_item(ht_id,htxm_id)
{
	var option = {
	closeHTML:"<a href='#'><span style='float:right;cursor:pointer;color:red;'>关闭</span></a>",
		onClose:function(dialog){
			$("#simplemodal-container,#simplemodal-overlay").hide();$(dialog.data).empty();$.modal.close();
		}
	};
	option.containerCss = {width:710,height:410};
	$('<iframe src="index.php?m=audit&do=xm_show&ht_id=' + ht_id + '&htxm_id=' + htxm_id +'" frameborder="0" style="width:700px;height:400px;margin-bottom:5px;"></iframe>').load(function(){
	}).modal(option);
}

//修改监审最后期限
function show_edit(obj,id,zuzhi_id)
{
	if (id == '') {
		alert('操作有误');
		return;
	}
	var option = {
	closeHTML:"<a href='#'><span style='float:right;cursor:pointer;color:red;'>关闭</span></a>",
		onClose:function(dialog){
			$("#simplemodal-container,#simplemodal-overlay").hide();$(dialog.data).empty();$.modal.close();
		},
		onShow:function(dialog){ 
			$('[name="buchong"]').click(function(){
				
				var t = $('[name="jh_date"]').val();
				if(t == '' || t == '0000-00-00'){
					alert('请输入监审最后期限日期');
					return;
				}
				$.post('./dlg/in_finalItemDate.php',{finalItemDate:t,xmid:id,zuzhi_id:zuzhi_id},function(r){
					if(id == r){
						$(obj).text(t);
						alert('操作成功');
						$.modal.close();
					}else{
						alert('权限不足');
						$.modal.close();
					}
				});
			});	
		}
	};
	option.containerCss = {width:210,height:100};
	var d = $(obj).text();
	$('<div align="center"><table width="180" class="c_table"><tr bgcolor="#FFFFFF"><td align="center" height="21">监审最后期限</td></tr><tr bgcolor="#FFFFFF"><td><input type="text" name="jh_date" value="'+ d +'"></td></tr><tr bgcolor="#FFFFFF"><td><input type="button" name="buchong" value="提交"></td></tr></table></div>').modal(option);
}
