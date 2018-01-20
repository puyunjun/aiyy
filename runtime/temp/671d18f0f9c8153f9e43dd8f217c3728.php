<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:69:"D:\phpstudy\WWW\aiyy\public/../application/user\view\login\index.html";i:1516357688;s:54:"D:\phpstudy\WWW\aiyy\application\user\view\layout.html";i:1516357688;}*/ ?>
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
登录
</dd>
            
<dt><a href="<?php echo url('user/Signin/index'); ?>">注册</a></dt>

        </dl>
    </div>
</section>

<section id="top_kong"></section>



<div id="login">
    <div class="login-1">
        <form action="" name="ff1" method="post" onsubmit="return sub_fun()">
            <li class="tt1"><input id="mobile_phone" name="username" type="text" class="txt1" value="" placeholder="请输入手机号"></li>
            <li class="tt1"><input id="mobile_password" name="password" type="password" class="txt1" placeholder="请输入密码（至少6位）" value=""></li>
            <li class="tt2"><input  class="tj" type="submit" value="登录"></li>
            <li class="tt3"><p><a href="">立即注册</a></p><span><a href="">忘记密码？</a></span></li>
        </form>
    </div>
    <div class="login-2">
        <div class="logo2-1">其他账号登录</div>
        <div class="logo2-2">
            <ul>
                <li><a href="<?php echo url('user/Signin/third_party_sign','',''); ?>"><img src="__HOME_CSS__/wapcssjsimg/images/login-1.png" ></a></li>
                <li><a href=""><img src="__HOME_CSS__/wapcssjsimg/images/login-2.png" ></a></li>
                <li><a href=""><img src="__HOME_CSS__/wapcssjsimg/images/login-3.png" ></a></li>
            </ul>
        </div>
    </div>

</div>
<div id="allmap" style="display: none"></div>

<!--{__CONTENT__}-->





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


<script>
    var url ='<?php echo $url_root; ?>';
    console.log(url)
</script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=r2QRGELPU7lF35I6ttxbKGgCPbFjSVNN"></script>
<script src="__HOME_JS__/user/login/index.js"></script>



</html>