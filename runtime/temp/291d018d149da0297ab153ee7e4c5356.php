<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:77:"D:\phpstudy\WWW\aiyy\public/../application/user\view\become_escort\index.html";i:1516357688;s:54:"D:\phpstudy\WWW\aiyy\application\user\view\layout.html";i:1516357688;}*/ ?>
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
我的
</dd>
            
            <dt class="arrw-3"><a href=""></a></dt>
            
        </dl>
    </div>
</section>

<section id="top_kong"></section>


<div id="cwby">
    <div class="cwby-1">
        <form action="" name="ff1" method="post" onsubmit="return sub_fun()">
            <ul>
                <li class="tt1"><b>姓名</b><p><input id="real_name" name="sjh" type="text" class="txt1" value="<?php echo $data['real_name']; ?>" placeholder="请输入"></p></li>
                <li class="tt1"><b>身份证号</b><p><input id="sfzh" name="sfzh" type="text" class="txt1" value="<?php echo $type['id_card_num']; ?>" placeholder="请输入"></p></li>
                <li class="tt1"><b>性别</b><p><select name="sex" class="select1" readonly="readonly">
                    <?php if($data['sex'] == 1): ?>
                    <option value="男">男</option>
                    <?php else: ?>
                    <option value="女">女</option>
                    <?php endif; ?>
                </select> </p></li>
            </ul>
            <ul>
                <li class="tt1"><b>手机号</b><p><input id="mobile_phone" name="sjh" type="text" class="txt1" value="<?php echo $data['phone']; ?>" placeholder="请输入"></p></li>
                <li class="tt2"><b>验证码</b><p><input id="check_verify"  name="check_verify" type="text" class="txt1" value="" placeholder="请输入验证码">
                    <a onclick="get_verify($('#mobile_phone').val(),this,'<?php echo $forgetPass; ?>');">获取验证码</a></p></li>
            </ul>
            <ul>
                <li class="tt3"><input name="tj" class="tj" type="submit" value="签约伴游"></li>
                <li class="tishi1"><input type="radio" name="zhidu" value="0" id="zhidu"><a href="">《爱约游伴游规章制度》</a></li>
            </ul>
        </form>
    </div>
</div>

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

<script src="__HOME_JS__/user/become_escort/index.js"></script>


</html>