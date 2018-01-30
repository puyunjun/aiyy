<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:78:"D:\phpstudy\WWW\aiyy\public/../application/user\view\release_detail\index.html";i:1516959785;s:54:"D:\phpstudy\WWW\aiyy\application\user\view\layout.html";i:1516959785;}*/ ?>
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
<?php echo $escort_base_info['nickname']; ?>
</dd>
            
            <dt class="arrw-3"><a href=""></a></dt>
            
        </dl>
    </div>
</section>

<section id="top_kong"></section>



<div id="yyxx">
    <div class="yyxx-1">
        <div class="yyxx-1-1"><img src="<?php echo $escort_base_info['head_img']!=null?$escort_base_info['head_img']  : '__HOME_CSS__/wapcssjsimg/images/sctx-1.png'; ?>" ></div>
        <div class="yyxx-1-2"><?php echo $escort_base_info['nickname']; ?><p></p></div>
        <div class="yyxx-1-3">重庆 <?php echo $escort_base_info['birthday']; ?>岁  <?php echo $escort_base_info['height']; ?>cm   与您距离：<script> document.write(localStorage.getItem("distances<?php echo $escort_base_info['uid']; ?>") === null ? 0 :localStorage.getItem("distances<?php echo $escort_base_info['uid']; ?>"))</script>km</div>


        <div class="yyxx-1-4">欢迎私聊</div>
    </div>
    <div class="yyxx-2">
        <?php if(empty(!$escort_base_info['photo_url']) || ((!$escort_base_info['photo_url'] instanceof \think\Collection || !$escort_base_info['photo_url'] instanceof \think\Paginator ) && !$escort_base_info['photo_url']->isEmpty())): ?>
        <div class="tt"><b>生活照</b><p>共<?php echo $escort_base_info['photo_num']; ?>张</p></div>
        <div class="lis">
            <ul>
                <?php if(is_array($escort_base_info['photo_url']) || $escort_base_info['photo_url'] instanceof \think\Collection || $escort_base_info['photo_url'] instanceof \think\Paginator): $i = 0; $__LIST__ = $escort_base_info['photo_url'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?>
                    <li <?php if($privew_privilege['privilege_photo'] == false): ?>class="on"<?php endif; ?>>
                    <?php if($privew_privilege['privilege_photo'] == true): ?>
                    <img src="<?php echo $value; ?>" >
                    <?php endif; ?>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <?php else: ?>
        <div class="tt"><b>生活照</b><p>共0张</p></div>
        暂无数据
        <?php endif; if($privew_privilege['privilege_photo'] == true): ?>
        <!--点击查看-->
        <?php else: ?>
        <div class="tishi">由于用户设置照片保护，需客户至少升级为白银会员或钱包至少拥有1000.00元余额才可以查看生活照。<br>注意：押金可以随时提取，或用于之丰富伴游费用</div>
        <?php endif; ?>
        <div class="cz"><input name="tj" class="tj" type="submit" value="会员充值"></div>
    </div>

    <div class="yyxx-3">
        <div class="tt"><b>视频</b> </div>
        <?php if(empty($escort_base_info['video_url']) || (($escort_base_info['video_url'] instanceof \think\Collection || $escort_base_info['video_url'] instanceof \think\Paginator ) && $escort_base_info['video_url']->isEmpty())): ?>
            暂无数据
        <?php else: if(is_array($escort_base_info['video_url']) || $escort_base_info['video_url'] instanceof \think\Collection || $escort_base_info['video_url'] instanceof \think\Paginator): $i = 0; $__LIST__ = $escort_base_info['video_url'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video_v): $mod = ($i % 2 );++$i;?>
            <div <?php if($privew_privilege['privilege_video'] == false): ?>class="lis on"<?php else: endif; ?>>
            <?php if($privew_privilege['privilege_video'] == true): ?>
            <img src="<?php echo $video_v; ?>" width="750" height="420">
            <?php endif; ?>
            </div>
            <?php endforeach; endif; else: echo "" ;endif; endif; ?>

    </div>
    <div class="yyxx-4">
        <div class="tt"><b>资料</b> </div>
        <div class="lis">
            <ul>
                <li><b>身高</b><p><?php echo $escort_base_info['height']; ?>cm</p></li>
                <li><b>职业</b><p><?php echo $escort_base_info['occupation_id']; ?></p></li>
                <li><b>服务</b><p>本地  全国</p></li>
                <li><b>语言</b><p>中文</p></li>
            </ul>
        </div>
    </div>

</div>


<!--{__CONTENT__}-->



<section id="foot_kong"></section>
<div id="boot1">
    <div class="boot1">
        <dl>
            <dt><a href=""><p>聊天</p></a></dt>
            <dd><a href=""><p>红包</p></a></dd>
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




</html>