 
 
 /*切换*/
function setTab(name,cursel,n){ 
for(i=1;i<=n;i++){
var menu=document.getElementById(name+i);
var con=document.getElementById("con_"+name+"_"+i);
menu.className=i==cursel?"hover":"";
con.style.display=i==cursel?"block":"none";
}
}
//-->  

//点击弹出
$(function() {
	 var a = $("#toplist");
	 var b = $("#toplistwrap");
	$(".more").click(function() {
		a.is(":hidden") ? ($(this).addClass("moreclick"),b.slideDown(300),a.slideDown(300)) : ($(this).removeClass("moreclick"), a.slideUp(300),b.slideUp(300))
	}), $(".up-n i").click(function() {
		a.slideUp(300),b.slideUp(300),$(".more").removeClass("moreclick")
	})
	
	
	  
		//点击滑动到顶部
		$(window).scroll(function () {
		var scrollHeight = $(document).height();
		var scrollTop = $(window).scrollTop();
		var $windowHeight = $(window).innerHeight();
		scrollTop > 50 ? $("#returnTop").fadeIn(200).css("display","block") : $("#returnTop").fadeOut(200);			 
		}); 
		$('#returnTop').click(function (e) {
		e.preventDefault();
		$('html,body').animate({ scrollTop:0});
		});
		//点击滑动到顶部
		 
	
	
}); 























