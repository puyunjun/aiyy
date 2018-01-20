<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:80:"D:\phpstudy\WWW\aiyy\public/../application/user\view\journey\journey_detail.html";i:1516416236;s:54:"D:\phpstudy\WWW\aiyy\application\user\view\layout.html";i:1516357688;}*/ ?>
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
    
<link rel="stylesheet" href="__HOME_CSS__/wapcssjsimg/cssjs/swiper.min.css">


</head>
<body style="background-color:#161c26;">

<section id="top">
    <div class="top">
        <dl>
            <dt class="arrw-1"><a href="javascript:history.go(-1);"></a></dt>
            <dd>
旅途
</dd>
            
            <dt class="arrw-3"><a href=""></a></dt>
            
        </dl>
    </div>
</section>

<section id="top_kong"></section>


<div id="lvtu-show">
    <div class="lvtu-show-pic">
        <div class="swiper-container">
            <div class="swiper-wrapper" >
                <div class="swiper-slide"><img src="__HOME_CSS__/wapcssjsimg/images/man-1.jpg" /></div>
                <div class="swiper-slide"><img src="__HOME_CSS__/wapcssjsimg/images/man-1.jpg" /></div>
                <div class="swiper-slide"><img src="__HOME_CSS__/wapcssjsimg/images/man-1.jpg" /></div>
            </div>
            <!-- Add Pagination -->
            <div class="quyu"><?php echo $journey['address']; ?></div>
            <div class="swiper-pagination swiper-pagination-black"></div>
        </div>

    </div>


    <div class="lvtu-show-lis">
        <div class="title1"><dl><dt><b><?php echo $journey['nickname']; ?></b><p>职业<?php echo $journey['occupation_id']; ?></p> <span><a href="" target="_blank">详细资料></a></span></dt><dd><?php echo $data['birthday']; ?>岁・12w</dd></dl> </div>
        <div class="video">
            <div class="video-1">视频认证</div>
            <div class="video-2"><a href=" "><img src="__HOME_CSS__/wapcssjsimg/images/video-rz.png" ></a></div>
        </div>
        <div class="lvxing">
            <div class="lvxing-1">Ta的旅行</div>
            <div class="lvxing-2">
                <div class="date"><?php echo date('Y-m-d H:i:s',$journey['create_time']); ?>发布</div>
                <div class="xx">
                    <ul>
                        <li>
                            <?php if($journey['release_object']  == 1): ?>
                            <b>邀约对象：</b><p>男</p>
                            <?php elseif($journey['release_object']  == 2): ?>
                            <b>邀约对象：</b><p>女</p>
                            <?php elseif($journey['release_object']  == 0): ?>
                            <b>邀约对象：</b><p>不限</p>
                            <?php endif; ?>
                        </li>
                        <li><b>出行方式：</b><p><?php echo $journey['travel_tool']; ?></p></li>
                        <li><b>费&nbsp;&nbsp;&nbsp;&nbsp;用：</b><p>100</p></li>
                    </ul>
                </div>
                <div class="overdate">还有<?php echo $data['days']; ?>天结束</div>
            </div>
        </div>
        <div class="lvtu-show-1-end"></div>

    </div>

</div>


<!--{__CONTENT__}-->



<section id="foot_kong"></section>
<div id="boot2">
    <div class="boot2">
        <dl>
            <dt><a href=""><p>感兴趣</p></a><a href=""><p>HI</p></a></dt>
            <dd><a href="">关注</a></dd>
        </dl>
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


<script src="__HOME_CSS__/wapcssjsimg/cssjs/swiper.min.js"></script>
<script src="__HOME_JS__/user/journey/detail.js"></script>



</html>