<%@page import="com.winland.common.SystemConstant"%>
<%@ page language="java" import="java.util.*" pageEncoding="utf-8"%>
<%
String path = request.getContextPath();
String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="misapplication-tap-highlight" content="no"/>
	<meta name="HandheldFriendly" content="true"/>
	<meta name="MobileOptimized" content="320"/>
	<link rel="stylesheet" type="text/css" href="../css/mui.min.css"/>
	<link rel="stylesheet" type="text/css" href="../css/base.css"/>
	<title>个人库</title>
	<style type="text/css">
		li{
			height:50px;
			padding:10px 15px;
			background:white;
			border-bottom:1px solid #dcdcdc;
		}
		.gzbox img{
			animation: player 0.6s ease-in-out infinite;
		}
		@keyframes player{
			0% {transform:scale(1);}
			80% {transform:scale(0.3);}
			100% {transform:scale(0.3);}
		}
	</style>
  </head>
  <body>
  <input type = "hidden" value="<%=SystemConstant.SYSTEM_HOST%>" id="basePath">
  <input type = "hidden" value="<%=path%>" id="path">
  
 	 <div style="background:white;box-shadow: 0 1px 6px #ccc;width:100%;position:fixed;z-index:99999;top:0;left:0;">
	  	<div style="text-align:center;padding:10px 0;">
	  		<div style="display:inline-block;width:auto;width:80%;height:30px;position:relative;border-radius:5px;position:relative;overflow:hidden;">
	  			<input type="text" placeholder="公司名称" maxlength="10" style="background:#efeff4;width:100%;height:100%;text-align:center;"/>
	  			<img class="search" src="../images/search.png" style="height:20px;position:absolute;top:5px;right:5px;">
	  		</div>
	  	</div>
	  	<div class="tapnav clearfix" style="height:40px;line-height:40px;text-align:center;">
	  		<p class="fl" id="" style="width:33.3%;height:100%;position:relative;color:#6EA5FF;">
	  			新录入
	  			<i style="position:absolute;bottom:0;left:50%;margin-left:-25px;width:50px;height:2px;background:#6EA5FF;"></i>
	  		</p>
	  		<p class="fl" id="1" style="width:33.3%;height:100%;position:relative;color:#999999;">
	  			已拜访
	  			<i style="display:none;position:absolute;bottom:0;left:50%;margin-left:-25px;width:50px;height:2px;background:#6EA5FF;"></i>
	  		</p>
	  		<p class="fl" id="2" style="width:33.3%;height:100%;position:relative;color:#999999;">
	  			已完成
	  			<i style="display:none;position:absolute;bottom:0;left:50%;margin-left:-25px;width:50px;height:2px;background:#6EA5FF;"></i>
	  		</p>
	  	</div>
  	</div>
  	
  	<!--下拉刷新容器-->
	<div id="pullrefresh" class="mui-content mui-scroll-wrapper" style="position:fixed;top:100px;bottom:0;">
		<div class="mui-scroll">
			<!--数据列表-->
			<ul class="mui-table-view mui-table-view-chevron items">
				
			</ul>
		</div>
	</div>
  	<div class="confirmbox" style="display:none;width:100%;height:100%;position:fixed;top:0;left:0;z-index:99999999;background: rgba(0,0,0,0.5);">
		<div style="width:220px;height:auto;background:white;position:absolute;top:50%;left:50%;margin-top: -100px;margin-left:-110px;text-align:center;border-radius:5px;">
			<p style="height:30px;line-height:30px;border-bottom:1px solid #4F6BBF;color:#4F6BBF;font-size:16px;">提示</p>
			<p style="text-align:left;padding:0 15px;padding-top:10px;height:50px;">你确定取消关注吗？</p>
			<div class="btn-box" style="display:block;width:200px;height:30px;line-height:30px;color:white;margin:0 auto;margin-bottom:10px;">
				<span class="closer fl" onclick="confirmTrue(this)" style="width:45%;height:100%;border-radius:3px;background:#dcdcdc;">确定</span>
				<span class="fr" onclick="confirmRem(this)" style="width:45%;height:100%;border-radius:3px;background:#4F6BBF;">取消</span>
			</div>
		</div>
	</div>
	<div class="gzbox" style="display:none;width:100%;height:100%;position:fixed;top:0;left:0;z-index:99999999;background: rgba(0,0,0,0);">
		<img src="../images/hongxin.png" style="transform:scale(0.3);width:80px;position:absolute;left:50%;margin-left:-40px;top:50%;margin-top:-40px;" />
	</div>
  </body>
</html>
<script type="text/javascript" src="../js/mui.min.js"></script>
<script type="text/javascript" src="../../jiangxuhui.js"></script>
<script type="text/javascript" src="../../jquery-3.2.1.min.js"></script>
<script type="text/javascript">

var basePath=$("#basePath").val();
var path=$("#path").val();

var taptype='';	
var keycode='';
var pagenum=0;

mui.init({
	pullRefresh: {
		container: '#pullrefresh',
		up: {
			height:50,
			auto:true,
			contentrefresh: '正在加载...',
			contentnomore:'暂无更多数据',
			callback: pullupRefresh
		}
	}
});

function pullupRefresh() {
	pagenum++;
	setTimeout(function(){
		post();
	},1000)
}

mui("#pullrefresh").on('tap', '.list', function (event) {
	this.click();
	event.stopPropagation();//阻止li事件的点击
});
mui("#pullrefresh").on('tap', '.list2', function (event) {
	this.click();
	event.stopPropagation();//阻止li事件的点击
});

function post(){
	console.log('taptype：'+taptype);
	$.post(basePath+'api/invest/queryOwnCorparationList',{
			selectType:taptype,
			searchKey:keycode,
			pageSize:30,
			pageNumber:pagenum,
		},function(res){
		console.log(res);
		if(res.data.length==0){
			var len=$('.items li').length;
			if(len==0){
				mui('#pullrefresh').pullRefresh().refresh(true);//重启
				//mui('#pullrefresh').pullRefresh().disablePullupToRefresh();//禁用
				$('.items').html('<p style="text-align:center;">----空空如也----</p>');
				$('.mui-table-view').css('background','none');
				$('ul').css('background','none');
			}
			mui('#pullrefresh').pullRefresh().endPullupToRefresh(false);
		}else{
			mui('#pullrefresh').pullRefresh().refresh(true);//重启
			//mui('#pullrefresh').pullRefresh().enablePullupToRefresh();//禁用取消
			var str='';
			$.each(res.data,function(index,item){
				var pic='',txt='';
				if(item.haveCare==1){
					pic='../images/xin.png';
					txt='已关注';
				}else{
					pic='../images/kongxin.png';
					txt='关注';
				}
				
				var pro='';
				if(item.stateId==1307||item.stateId==1306||item.stateId==0){
					pro='业务拜访';
				}
				if(item.stateId==1308){
					pro='达成意向';
				}
				if(item.stateId==1410){
					pro='核名';
				}
				if(item.stateId==1411){
					pro='提交资料';
				}
				if(item.stateId==1438){
					pro='确认资料';
				}
				if(item.stateId==1412){
					pro=item.stateId;
				}
				
				var isweb=item.isNameCheck;
				if(isweb.length==0){
					isweb='网上核名'
				}else{
					if(isweb==0){
						isweb='现场核名'
					}else{
						isweb='网上核名'
					}
				}
				
				str+='<li>'
				str+='<div class="list" data-companyname="'+item.name+'" id="'+item.id+'" data-isweb="'+isweb+'" data-state="'+item.stateId+'" data-pro="'+pro+'" onclick="todetail(this)" style="height:100%;">'
				str+='<div class="fl" style="width:50px;height:50px;border-radius:100%;border:1px solid #FAAE75;text-align:center;overflow:hidden;">'
				str+='<img class="fl" src="../images/company.png" style="width:50px;height:50px;" />'
				/* str+='<p style="margin-top:10px;margin-bottom:2px;">'
				str+='<i style="display:inline-block;width:11px;height:16px;background:url(../images/num.png);background-position:0 0;font-size:16px;"></i>'
				str+='<span style="display:inline-block;display:none;">+</span>'
				str+='<p>'
				str+='<p style="font-size:9px;color:#FAAE75;">业务次数</p>' */
				str+='</div>'
				str+='<div class="fl clearfix" style="width:auto;margin-left:10px;">'
				str+='<p style="width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">'+item.name+'</p>'
				str+='<p class="clearfix" style="width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12px;color:#999;margin-top:14px;"><span class="fl">待处理：<span style="display:'+(pro=='核名'?'inline-block':'none')+';">'+(isweb=='网上核名'?'网上':'现场')+'</span>'+pro+'</p>'
				str+='</div>'
				str+='<div class="fr clearfix" style="width:auto;">'
				str+='<p style="font-size:12px;color:#999;">'+item.insertDate.split(' ')[0]+'</p>'
				str+='</div>'
				str+='</div>'
				str+='<p class="list2" onclick="confirmfn(this)" id="'+item.id+'" class="clearfix list2" data-type="1" style="display:inline-block;position:absolute;right:15px;bottom:10px;">'
				str+='<span class="fr" style="color:#FF3A30;font-size:12px;">'+txt+'</span>'
				str+='<img class="fr" src="'+pic+'" style="height:12px;margin-top:3px;margin-right:3px;"/>'
				str+='</p>'
				str+='</li>'
			})
			$('.items').append(str);
			mui('#pullrefresh').pullRefresh().endPullupToRefresh(pagenum>1000);
		}
	},'json')
}

function todetail(obj){
	var id=$(obj).attr('id');
	localStorage.companyid=JSON.stringify(id);
	var stateid=$(obj).data('state');
	localStorage.stateid=JSON.stringify(stateid);
	var pro=$(obj).data('pro');//当前任务节点
	localStorage.pro=JSON.stringify(pro);
	var isweb=$(obj).data('isweb');
	localStorage.isweb=JSON.stringify(isweb);
	var companyname=$(obj).data('companyname');
	localStorage.companyname=JSON.stringify(companyname);
	
	if(isweb=='网上核名'){//网上核名
		var arrurl={'客户信息':'companyInfo.jsp','业务拜访':'call.jsp','达成意向':'intention.jsp','核名':'nuclearName.jsp','提交资料':'dataSub.jsp','确认资料':'prepareData.jsp','驳回':path+'/wap/investment/cbo/html/prepareData-again.jsp'};//提交资料后面新增页面“准备资料”
		var arrcpy=['客户信息','业务拜访','达成意向','核名','提交资料','确认资料','服务费收款','办理营业执照','税务登记','开户','提成收益'];
		localStorage.arrcpy=JSON.stringify(arrcpy);
	}else{
		var arrurl={'客户信息':'companyInfo.jsp','业务拜访':'call.jsp','达成意向':'intention.jsp','提交资料':'dataSub.jsp','确认资料':'prepareData.jsp','驳回':path+'/wap/investment/cbo/html/prepareData-again.jsp'};
		var arrcpy=['客户信息','业务拜访','达成意向','提交资料','确认资料','核名','服务费收款','办理营业执照','税务登记','开户','提成收益'];
		localStorage.arrcpy=JSON.stringify(arrcpy);
	}
	
	for(op in arrurl){
		if(pro==op){
			window.location.href=arrurl[pro];
			return;
		}
	}
	
	if(isweb=='现场核名'){
		window.location.href=path+'/wap/investment/cbo/html/process2.jsp';
	}else{
		window.location.href=path+'/wap/investment/cbo/html/process.jsp';
	}
}
	
	$('.tapnav p').each(function(i){
		$(this).click(function(){
			$('.tapnav p i').hide();
			$(this).find('i').show();
			$('.tapnav p').css('color','#999999');
			$(this).css('color','#6EA5FF');
			taptype=$(this).attr('id');
			pagenum=1;
			$('.items').html('');
			post();
		})
	})
	
	function confirmfn(obj){
		var txt=$(obj).find('span').text();
		var id=$(obj).attr('id');
		$('.btn-box').attr('id',id);
		if(txt=='已关注'){
			$('.confirmbox').show();
			$('.btn-box').attr('id',id);
		}else{
			$('.gzbox').show();
			$.post(basePath+'api/invest/setClaimFollow',{
				companyId:id
			},function(res){
				console.log(res);
				if(res.status==1){
					setTimeout(function(){
						$('.gzbox').hide();
						$(obj).find('img').attr('src','../images/xin.png');
						$(obj).find('span').text('已关注');
					},500)
				}else{
					alertfn(res.message);
				}
			},'json')
		} 
	}
	function confirmRem(obj){
		$('.confirmbox').hide();
	}
	function confirmTrue(obj){
		var id=$(obj).parent().attr('id');
		$.post(basePath+'api/invest/cancelClaimFollow',{
			companyId:id
		},function(res){
			console.log(res);
			if(res.status==1){
				$('.confirmbox').hide();
				for(var i=0;i<$('.list2').length;i++){
					var ID=$('.list2').eq(i).attr('id');
					if(ID==id){
						$('.list2').eq(i).find('img').attr('src','../images/kongxin.png');
						$('.list2').eq(i).find('span').text('关注');
					}
				}
			}else{
				alertfn(res.message);
			}
		},'json')
	}
	
	$('.search').click(function(){
		keycode=$(this).prev().val();
		pagenum=1;
		$('.items').html('');
		post();
	})
	
</script>