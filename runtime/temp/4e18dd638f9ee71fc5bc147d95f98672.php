<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:66:"D:\phpstudy\WWW\aiyy\public/../application/user\view\news\vip.html";i:1517196313;s:54:"D:\phpstudy\WWW\aiyy\application\user\view\layout.html";i:1516959785;}*/ ?>
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
会员升级消息助手
</dd>
            
            <dt class="arrw-3"><a href=""></a></dt>
            
        </dl>
    </div>
</section>

<section id="top_kong"></section>


<div id="xiaoxi">
    <div class="xiaoxi-1">
        <div class="lis">
        <?php if(is_array($user) || $user instanceof \think\Collection || $user instanceof \think\Paginator): $i = 0; $__LIST__ = $user;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <a>
                <dl><img src="__HOME_CSS__/wapcssjsimg/images/xiaoxi-1.png" class="falai" >
                <dt>
                <b><?php echo $vo['recharge_type']; ?></b>

                <p><?php echo date(' h:i',$vo['create_time']); ?></p>
                </dt>
                <dd>充值金额：<?php echo $vo['total_money']; ?></dd>
                </dl>
            </a>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
</div>

<!--{__CONTENT__}-->


<section id="foot_kong"></section>
<div id="ft_fixed">
    <div class="ft_fixed">
        <li class="hover"><a href="<?php echo url('index/Index/index'); ?>">约TA</a></li>
        <li><a href="<?php echo url('user/Journey/index'); ?>">旅途</a></li>
        <li><a href="<?php echo url('user/Release/index'); ?>">发布</a></li>
        <li><a href="<?php echo url('user/News/index'); ?><?php echo isset($all_news_num['url_sql'])?$all_news_num['url_sql'] : ''; ?>">消息（<?php echo isset($all_news_num['all_num'])?$all_news_num['all_num'] : 0; ?>）</a></li>
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

    <script type="text/javascript" src="__HOME_JS__/user/common.js"></script>


</html>