<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:69:"D:\phpstudy\WWW\aiyy\public/../application/user\view\index\index.html";i:1516959785;s:54:"D:\phpstudy\WWW\aiyy\application\user\view\layout.html";i:1516959785;}*/ ?>
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


<div id="mine">
    <div class="mine-1">
        <a href="<?php echo url('user/MyInfo/index'); ?>">
            <dl>
                <dt>
                    <?php if($user_base_info['head_img'] != ''): ?>
                    <img src="<?php echo $user_base_info['head_img']; ?>" >
                    <?php else: ?>
                    <img src="__HOME_CSS__/wapcssjsimg/images/sctx-1.png" >
                    <?php endif; ?>
                </dt>
                <dd>
                    <b>
                        <?php if($user_base_info['nickname'] != ''): ?>
                        <?php echo $user_base_info['nickname']; else: ?>
                        昵称
                        <?php endif; switch($user_base_info['sex']): case "1": ?><img src="__HOME_CSS__/wapcssjsimg/images/sex2.png" ><?php break; case "2": ?><img src="__HOME_CSS__/wapcssjsimg/images/sexw.png" ><?php break; default: endswitch; ?>

                        <img src="__HOME_CSS__/wapcssjsimg/images/v.png" >
                    </b>
                    <p>约游ID：<?php echo $user_base_info['sys_id']; ?></p>
                </dd>
            </dl>
        </a>
    </div>
    <div class="mine-2">
        <ul>
            <li><b><?php if($user_base_info['video_num'] == null): ?>0<?php else: ?><?php echo $user_base_info['video_num']; endif; ?></b><p>视频</p></li>
            <li><b><?php if($user_base_info['img_num'] == null): ?>0<?php else: ?><?php echo $user_base_info['img_num']; endif; ?></b><p>照片</p></li>
            <li><b><?php echo $user_base_info['attention_num']; ?></b><p>关注</p></li>
            <li><b><?php echo $user_base_info['attentioned_num']; ?></b><p>粉丝</p></li>
        </ul>
    </div>
    <div class="mine-xx">
        <ul><li class="xx1"><a href="<?php echo url('user/Wallet/index'); ?>">钱包</a></li></ul>
        <ul>
            <li class="xx2"><a href="<?php echo url('user/BecomeEscort/index'); ?>">成为伴游</a></li>
            <li class="xx3"><a href="<?php echo url('user/Authentication/index'); ?>">个人认证</a></li>
            <li class="xx4"><a href="<?php echo url('user/BecomeMember/member_list'); ?>">升级会员</a></li>
            <li class="xx5"><a href="">会员特权</a></li>
        </ul>
        <ul>
            <li class="xx6"><a href="">消息管理</a></li>
            <li class="xx7"><a href="">联系客服</a></li>
        </ul>
        <ul>
            <li class="xx8"><a href="">黑名单</a></li>
            <li class="xx9"><a href="">帮助</a></li>
            <li class="xx10"><a href="">投诉建议</a></li>
        </ul>
    </div>
    <div class="mine-exit">
        <ul>
            <li><a href="<?php echo url('user/Login/login_out'); ?>">退出登录</a></li>
        </ul>
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

<script>
    <?php if($param_data){ ?>
        alert("<?php  echo $param_data;  ?>");
    <?php } ?>

</script>


</html>