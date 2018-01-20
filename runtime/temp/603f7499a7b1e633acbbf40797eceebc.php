<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:70:"D:\phpstudy\WWW\aiyy\public/../application/user\view\my_info\show.html";i:1516354505;s:54:"D:\phpstudy\WWW\aiyy\application\user\view\layout.html";i:1516357688;}*/ ?>
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
    
<link rel="stylesheet" href="__HOME_CSS__/user/my_info/index.css">


</head>
<body style="background-color:#161c26;">

<section id="top">
    <div class="top">
        <dl>
            <dt class="arrw-1"><a href="javascript:history.go(-1);"></a></dt>
            <dd>
编辑资料
</dd>
            
<dt><a onclick="$('#smt').click()">保存</a></dt>

        </dl>
    </div>
</section>

<section id="top_kong"></section>


<!--action="<?php echo url('user/my_info/mody',['id' => $id['id']]); ?>"-->
<form method="post" action="<?php echo url('user/my_info/mody',['id' => $id['id']]); ?>" enctype="multipart/form-data" id="form">
    <input type="submit" hidden  id="smt"  >
<div id="mi-xx">
    <div class="mi-xx-2">
        <input type="" value="<?php echo $id['id']; ?>"  hidden id="id">
        <ul>
            <?php if($id['id']  == 1): ?>
                <li><b>昵称</b>      <input type="" name="nickname" class="bai1" placeholder="请输入" value="<?php echo urldecode($user['nickname']); ?>"/></li>
            <?php elseif($id['id']  == 2): ?>
                <li><b>个性签名</b>  <input type="" name="autograph" class="bai1" placeholder="请输入" value="<?php echo urldecode($user['autograph']); ?>" /></li>
            <?php elseif($id['id']  == 3): ?>
                <li><b>姓名</b>      <input type="" name="real_name" class="bai1" placeholder="请输入" value="<?php echo urldecode($user['real_name']); ?>" /></li>
            <?php elseif($id['id']  == 4  and  $user['birthday'] == ''): ?>
                <li><b>生日</b>      <input type="" id="birthday" name="birthday" class="datetime-picker" placeholder="请输入" value=""/> </li>
            <?php elseif($id['id']  == 4): ?>
                 <li><b>生日</b>      <input type="" id="birthday" name="birthday" class="datetime-picker" placeholder="请输入" value="<?php echo date('Y-m-d',$user['birthday']); ?> "/> </li>
            <?php elseif($id['id']  == 5): ?>
                <li><b>地址</b>      <input type="" name="city_id"  class="city-picker" placeholder="请输入" value="<?php echo $user['city_id']; ?>" /></li>
            <?php elseif($id['id']  == 6): ?>
                <li><b>职业</b>      <input type="" name="occupation_id" class="job" placeholder="请输入" value="<?php echo $user['occupation_id']; ?>" /></li>
            <?php elseif($id['id']  == 7): ?>
                <li><b>QQ</b>        <input type="" name="qq" class="bai1" placeholder="请输入" value="<?php echo urldecode($user['qq']); ?>"/></li>
            <?php elseif($id['id']  == 8): ?>
                <li><b>手机</b>      <input type="" name="phone" class="bai1" placeholder="请输入" value="<?php echo urldecode($user['phone']); ?>"/></li>
            <?php elseif($id['id']  == 9): ?>
                <li><b>爱好</b>      <input type="" name="interest" class="like" placeholder="请输入" value="<?php echo $user['interest']; ?>" /></li>
            <?php elseif($id['id']  == 10): ?>
                <li><b>常出没地</b>  <input type="text" name="address"  class="city-picker" placeholder="请选择" value="<?php echo $user['address']; ?>" /></li>
            <?php elseif($id['id']  == 11): ?>
                <li><b>身高</b>      <input type="" name="height" class="bai1" placeholder="请输入" value="<?php echo urldecode($user['height']); ?>"/></li>
            <?php elseif($id['id']  == 12): ?>
                <li><b>三围</b>      <input type="" name="measurement" class="bai1" placeholder="请输入" value="<?php echo urldecode($user['measurement']); ?>"/></li>
            <?php elseif($id['id']  == 13): ?>
                <li><b>体重</b>      <input type="" name="weight" class="bai1" placeholder="请输入" value="<?php echo urldecode($user['weight']); ?>"/></li>
            <?php endif; ?>
        </ul>
    </div>
</div>
</form >

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

<script src="__HOME_JS__/user/common.js"></script>
<script src="__HOME_JS__/user/my_info/show.js"></script>
<script type="text/javascript">
    $(".datetime-picker").datetimePicker();
    +function($){
        $.rawCitiesData = [
            /*{
                "name":"北京",
                "code":"110000",
                "sub": [
                    {
                        "name": "北京市",
                        "code": "110000",
                        "sub":[
                            {
                                "name":"东城区",
                                "code":"110101"
                            },
                            {
                                "name":"西城区",
                                "code":"110102"
                            },
                            {
                                "name":"朝阳区",
                                "code":"110105"
                            },
                            {
                                "name":"丰台区",
                                "code":"110106"
                            },
                            {
                                "name":"石景山区",
                                "code":"110107"
                            },
                            {
                                "name":"海淀区",
                                "code":"110108"
                            },
                            {
                                "name":"门头沟区",
                                "code":"110109"
                            },
                            {
                                "name":"房山区",
                                "code":"110111"
                            },
                            {
                                "name":"通州区",
                                "code":"110112"
                            },
                            {
                                "name":"顺义区",
                                "code":"110113"
                            },
                            {
                                "name":"昌平区",
                                "code":"110114"
                            },
                            {
                                "name":"大兴区",
                                "code":"110115"
                            },
                            {
                                "name":"怀柔区",
                                "code":"110116"
                            },
                            {
                                "name":"平谷区",
                                "code":"110117"
                            },
                            {
                                "name":"密云县",
                                "code":"110228"
                            },
                            {
                                "name":"延庆县",
                                "code":"110229"
                            }
                        ]
                    }
                ]
            },*/
            {"name":"\u5317\u4eac","code":"110000","sub":[{"name":"\u5317\u4eac\u5e02","code":"110000","sub":[{"name":"\u4e1c\u57ce\u533a","code":"110101"},{"name":"\u897f\u57ce\u533a","code":"110102"},{"name":"\u671d\u9633\u533a","code":"110105"},{"name":"\u4e30\u53f0\u533a","code":"110106"},{"name":"\u77f3\u666f\u5c71\u533a","code":"110107"},{"name":"\u6d77\u6dc0\u533a","code":"110108"},{"name":"\u95e8\u5934\u6c9f\u533a","code":"110109"},{"name":"\u623f\u5c71\u533a","code":"110111"},{"name":"\u901a\u5dde\u533a","code":"110112"},{"name":"\u987a\u4e49\u533a","code":"110113"},{"name":"\u660c\u5e73\u533a","code":"110114"},{"name":"\u5927\u5174\u533a","code":"110115"},{"name":"\u6000\u67d4\u533a","code":"110116"},{"name":"\u5e73\u8c37\u533a","code":"110117"},{"name":"\u5bc6\u4e91\u53bf","code":"110228"},{"name":"\u5ef6\u5e86\u53bf","code":"110229"}]}]}
        ];
    }($);
</script>
<script src="__STATIC__/jquery-weui/js/city-picker.js"></script>
<script>
    $(".city-picker").cityPicker({
        title: "请选择地址"

    });
    $(".job").picker({
        title: "请选择您的职业",
        cols: [
            {
                textAlign: 'center',
                values: ['记者', '教师', '医护人员', '法官', '学生', '白领', '工人', '厨师', '美发师', '出租司机', '其他']
            }
        ]
    });
    $(".like").select({
        title: "您的爱好",
        multi: true,
        max: 3,
        items: [
            {
                title: "画画",
                value: 1
            },
            {
                title: "打球",
                value: 2
            },
            {
                title: "唱歌",
                value: 3
            },
            {
                title: "游泳",
                value: 4
            },
            {
                title: "健身",
                value: 5
            },
            {
                title: "睡觉",
                value: 6
            },
        ]
    });
</script>


</html>