<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"D:\phpstudy\WWW\aiyy\public/../application/user\view\release\index.html";i:1516415545;s:54:"D:\phpstudy\WWW\aiyy\application\user\view\layout.html";i:1516357688;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content="target-densitydpi=750,width=750,user-scalable=no" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="applicable-device" content="mobile">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <!--uc浏览器判断到页面上文字居多时，会自动放大字体优化移动用户体验。添加以下头部可以禁用掉该优化-->
    <meta name="wap-font-scale" content="no">
    <title>爱约游</title>
    <meta name="keywords" content="约游">
    <meta name="description" content="约游">


    <!--layer组件css-->
    <link rel="stylesheet" href="__STATIC__/layui/css/layui.css">

    <!-- 引入jquery weui-->
    <link rel="stylesheet" href="__STATIC__/jquery-weui/lib/weui.min.css">

    <link rel="stylesheet" href="__STATIC__/jquery-weui/css/jquery-weui.min.css">

    <link rel="stylesheet" href="__HOME_CSS__/wapcssjsimg/cssjs/css.css">

    <link rel="stylesheet" href="__HOME_CSS__/wapcssjsimg/cssjs/swiper.min.css">
    <script type="text/javascript" src="__HOME_CSS__/wapcssjsimg/cssjs/jquery.min.js"></script>
    

    

</head>
<body style="background-color:#161c26;">

<section id="top">
    <div class="top">
        <dl>
            <dt class="arrw-1"><a href="javascript:history.go(-1);"></a></dt>
            <dd>
发布
</dd>
            
            <dt class="arrw-3"><a href=""></a></dt>
            
        </dl>
    </div>
</section>

<section id="top_kong"></section>


<form action="release" name="ff1" method="post" id="myform">
    <div id="fabu">
        <div class="fabu-1"><b>成为VIP会员</b><p>将会有18名女神约你</p></div>
        <div class="fabu-2">
            <ul>
                <!--<li><b>约游对象</b><p><input type="" value="" name="release_object"></p></li>-->
                <!--<li><b>出行方式</b><p><input type="" value="" name="travel_tool"></p></li>-->
                <!--<li><b>出行时间</b><p><input type="" value="" name="travel_start_time"></p></li>-->
                <!--<li><b>出行天数</b><p><input type="text" value="" name="travel_total_time"></p></li>-->
                <li><b>约游对象</b><p><a href="">请选择</a></p></li>
                <li><b>出行方式</b><p><a href="">请选择</a></p></li>
                <li><b>出行时间</b><p><a href="">请选择</a></p></li>
                <li><b>出行天数</b><p><input type="text" value="10" name="travel_total_time"></p></li>
            </ul>
        </div>
        <div class="fabu-cyj">
            <ul>
                <li><b>诚意金	100元</b><p class="on"><input name="cyj" type="radio" value="1" ></p></li>
            </ul>
        </div>
        <div class="fabu-3">
            <div class="fabu-3-1">支付诚意金或成为VIP会员会提高您的约会成功率请填写真实信息 </div>
            <div class="fabu-3-2"><input  id="ajaxPost" name="tj" class="tj" type="submit" value="确认发布"></div>
        </div>
    </div>
</form>


<!--{__CONTENT__}-->


<section id="foot_kong"></section>
<div id="ft_fixed">
    <div class="ft_fixed">
        <li class="hover"><a href="<?php echo url('index/Index/index'); ?>">约TA</a></li>
        <li><a href="<?php echo url('user/Journey/index'); ?>">旅途</a></li>
        <li><a href="<?php echo url('user/Release/index'); ?>">发布</a></li>
        <li><a href="<?php echo url('user/News/index'); ?>">消息</a></li>
        <li><a href="<?php echo url('user/Index/index'); ?>">我的</a></li>
    </div>
</div>



<!--<script type="text/javascript" src="/public/static/wapcssjsimg/cssjs/cnzz.js"></script>-->
</body>


<!--layui组件-->
<script src="__STATIC__/layer/layer.js"></script>
<script src="__STATIC__/layui/layui.js"></script>

<!-- 引入jquery weui js组件-->
<script src="__STATIC__/jquery-weui/js/jquery-weui.js"></script>
<script src="__STATIC__/jquery-weui/js/swiper.min.js"></script>


<!--引入jqurey-form  js组件-->
<script src="__STATIC__/jquery-form/jquery.form.min.js"></script>

<script type="text/javascript" src="__HOME_JS__/user/release/index.js"></script>
<script type="text/javascript" src="__HOME_JS__/user/release/ajax.js"></script>


</html>