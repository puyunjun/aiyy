<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"D:\phpstudy\WWW\aiyy\public/../application/user\view\journey\index.html";i:1516416236;s:54:"D:\phpstudy\WWW\aiyy\application\user\view\layout.html";i:1516357688;}*/ ?>
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
旅途
</dd>
            
            <dt class="arrw-3"><a href=""></a></dt>
            
        </dl>
    </div>
</section>

<section id="top_kong"></section>


<div id="lvtu">
    <div class="lvtu_lis">
        <?php if(is_array($journey) || $journey instanceof \think\Collection || $journey instanceof \think\Paginator): $i = 0; $__LIST__ = $journey;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <a href="<?php echo url('user/Journey/journey_detail',['id' => $vo['uid']]); ?>">
            <input type="text"  hidden value="<?php echo $vo['uid']; ?>">
            <div class="lt-kk">
                <div class="lt-kk-1">
                    <div class="pic"><img src="<?php echo $vo['head_img']; ?>" width="90" height="90"></div>
                    <div class="tit">
                        <div class="tit-1"><b><?php echo $vo['nickname']; ?></b> | 职业<?php echo $vo['occupation_id']; ?></div>
                        <div class="tit-2"><?php echo $vo['birthday']; ?>岁.12w.<?php echo $vo['address']; ?></div>
                    </div>
                    <div class="gz"><a href="">+  关注</a></div>
                </div>
                <div class="lt-kk-2">
                    <div class="info">
                        <ul>
                            <?php if($vo['release_object']  == 1): ?>
                            <li>・邀约对象：男</li>
                            <?php elseif($vo['release_object']  == 2): ?>
                            <li>・邀约对象：女</li>
                            <?php elseif($vo['release_object']  == 0): ?>
                            <li>・邀约对象：不限</li>
                            <?php endif; ?>
                            <li>・出行方式：<?php echo $vo['travel_tool']; ?></li>
                            <li>・费&nbsp;&nbsp;&nbsp;&nbsp;用：100</li>
                        </ul>
                        <div class="date"><?php echo date('Y-m-d H:i:s',$vo['create_time']); ?>发布</div>
                    </div>
                    <div class="gxq"><a href=""><p>感兴趣</p></a></div>
                </div>
            </div>
        </a>
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </div>

</div>

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




</html>