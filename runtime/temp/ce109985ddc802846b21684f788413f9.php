<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"D:\phpstudy\WWW\aiyy\public/../application/user\view\my_info\index.html";i:1516416988;s:54:"D:\phpstudy\WWW\aiyy\application\user\view\layout.html";i:1516357688;}*/ ?>
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

<link rel="stylesheet" type="text/css" href="__HOME_CSS__/wapcssjsimg/cssjs/default.css">
<link rel="stylesheet" type="text/css" href="__HOME_CSS__/wapcssjsimg/cssjs/iconfont.css">
<link rel="stylesheet" type="text/css" href="__HOME_CSS__/wapcssjsimg/cssjs/normalize.css">
<link rel="stylesheet" type="text/css" href="__HOME_CSS__/wapcssjsimg/cssjs/tupian.css">
<link rel="stylesheet" type="text/css" href="__HOME_CSS__/wapcssjsimg/cssjs/video-js.css">



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


<form method="post" action="modify" enctype="multipart/form-data" id="form">
    <input type="submit" hidden  id="smt"  >


    <div id="mi-xx">
        <div class="mi-xx-1">
            <a onclick="$('#img').click()">
                <b>头像</b>
                <p>
                    <?php if(empty($base_info['head_img']) || (($base_info['head_img'] instanceof \think\Collection || $base_info['head_img'] instanceof \think\Paginator ) && $base_info['head_img']->isEmpty())): ?>
                    <img id="imgg" src="__HOME_CSS__/wapcssjsimg/images/sctx-1.png" >
                    <?php else: ?>
                    <img id="imgg" src="<?php echo $base_info['head_img']; ?>" >
                    <?php endif; ?>
                </p>
            </a>
            <input id="img" type="file" style="display: none" name="image"/>
        </div>



        <div class="mi-xx-2">
            <ul>
                <a href="<?php echo url('user/MyInfo/show', ['id' =>'1']); ?>"><li><b>昵称</b>      <input type="" readonly  name="nickname" class="bai1" placeholder="请输入" value="<?php echo $base_info['nickname']; ?>"/></li></a>
                <li><b>约游ID</b>    <input readonly type="" name="sys_id" class="bai1" placeholder="请输入" value="<?php echo $base_info['sys_id']; ?>"/></li>
                <a href="<?php echo url('user/MyInfo/show', ['id' =>'2']); ?>"><li><b>个性签名</b>  <input type="" readonly  name="autograph" class="bai1" placeholder="请输入" value="<?php echo $base_info['autograph']; ?>" /></li></a>
                <a href="<?php echo url('user/MyInfo/show', ['id' =>'3']); ?>"><li><b>姓名</b>      <input type="" readonly  name="real_name" class="bai1" placeholder="请输入" value="<?php echo $base_info['real_name']; ?>" /></li></a>
                <li>
                    <b>性别</b>
                    <!-- <p class="bai1">女</p> -->
                    <input readonly type="" name="sex" class="bai1" placeholder="请输入" value='
                <?php switch($base_info['sex']): case "1": ?>男<?php break; case "2": ?>女<?php break; default: ?>女
                <?php endswitch; ?>' />
                </li>
                <a href="<?php echo url('user/MyInfo/show', ['id' =>'4']); ?>" ><li >
                    <b>生日</b>
                    <!-- <p class="bai1">1990-01-01</p> -->
                    <?php if(empty($base_info['birthday']) || (($base_info['birthday'] instanceof \think\Collection || $base_info['birthday'] instanceof \think\Paginator ) && $base_info['birthday']->isEmpty())): ?>
                    <input type=""   id="birthday" name="birthday"  placeholder="请输入" value=""/>
                    <?php else: ?>
                    <input type=""   id="birthday" name="birthday"  placeholder="请输入" value="<?php echo date('Y-m-d',$base_info['birthday']); ?>  "/>
                    <?php endif; ?>

                </li></a>
                <a href="<?php echo url('user/MyInfo/show', ['id' =>'5' ]); ?>">     <li><b>地址</b><input type=""  name="city_id"   placeholder="请输入" value="<?php echo $base_info['city_id']; ?>"  /></li></a>
                <a href="<?php echo url('user/MyInfo/show', ['id' =>'6' ]); ?>">     <li><b>职业</b><input type=""  name="occupation_id"  placeholder="请输入" value="<?php echo $base_info['occupation_id']; ?>" /></li></a>
                <a href="<?php echo url('user/MyInfo/show', ['id' =>'7' ]); ?>">     <li><b>QQ</b><input type="" readonly  name="qq" class="bai1" placeholder="请输入" value="<?php echo $base_info['qq']; ?>"/></li></a>
                <a href="<?php echo url('user/MyInfo/show', ['id' =>'8' ]); ?>">     <li><b>手机</b><input type="" readonly  name="phone" class="bai1" placeholder="请输入" value="<?php echo $base_info['phone']; ?>"/></li></a>
                <a href="<?php echo url('user/MyInfo/show', ['id' =>'9' ]); ?>">     <li><b>爱好</b><input type=""  name="interest"  placeholder="请输入" value="<?php echo $base_info['interest']; ?>" /></li></a>
                <a href="<?php echo url('user/MyInfo/show', ['id' =>'10']); ?>">    <li><b>常出没地</b><input type="text" name="address"   placeholder="请选择" value="<?php echo $base_info['address']; ?>" /></li></a>
            </ul>
        </div>

        <div class="mi-xx-2">
            <ul>
                <a href="<?php echo url('user/MyInfo/show', ['id' =>'11']); ?>"><li><b>身高</b><input type="" readonly  name="height" class="bai1" placeholder="请输入" value="<?php echo $base_info['height']; ?>"/></li></a>
                <a href="<?php echo url('user/MyInfo/show', ['id' =>'12']); ?>"><li><b>三围</b><input type="" readonly  name="measurement" class="bai1" placeholder="请输入" value="<?php echo $base_info['measurement']; ?>"/></li></a>
                <a href="<?php echo url('user/MyInfo/show', ['id' =>'13']); ?>"><li><b>体重</b><input type="" readonly  name="weight" class="bai1" placeholder="请输入" value="<?php echo $base_info['weight']; ?>"/></li></a>
            </ul>
        </div>    <div class="mi-xx-zp">
        <div class="mi-xx-zp-1"><b>照片</b></div>
        <div class="mi-xx-zp-2">
            <div class="container" id='test'>
                <!--    照片添加    -->
                <div class="z_photo">
                    <div class="z_file">
                        <input type="file" name="file" id="file" value="" accept="image/*" multiple onchange="imgChange('z_photo','z_file');" />
                    </div>
                </div>
                <!--  上传 取消 -->
                <div class="sc">
                    <button class="sc-push">上传</button>
                    <button class="sc-remove">取消</button>
                </div>
                <!--遮罩层-->
                <div class="z_mask">
                    <!--弹出框-->
                    <div class="z_alert">
                        <p>确定要删除这张图片吗？</p>
                        <p>
                            <span class="z_cancel">取消</span>
                            <span class="z_sure" >确定</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="photo-bc">

            </div>
        </div>
    </div>
    <div class="mi-xx-sp">
        <div class="mi-xx-sp-1"><b>视频</b></div>
        <div class="mi-xx-sp-2">
            <video id="my_video_1" class="video-js vjs-default-skin" controls preload="auto" width="289" height="180" poster="" data-setup="{}">
                <source src="__HOME_CSS__/wapcssjsimg/images/video.mp4" type="video/mp4">
            </video>
            <div class="scsp">上传视频<input type="file" name="spsc" id="spsc" value="" accept="video/*" ;" /></div>
        </div>
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

<script src="__HOME_JS__/user/common.js"></script>
<script src="__HOME_JS__/user/my_info/head.js"></script>


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

<script src="__HOME_JS__/user/fly-zomm-img.min.js"></script>
<script src="__HOME_JS__/user/jqmeter.min.js"></script>
<!-- <script src="__HOME_JS__/user/video.js"></script> -->
<script src="__HOME_JS__/user/video.min.js"></script>
<script src="__HOME_JS__/user/videojs-ie8.js"></script>


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


<script type="text/javascript">

    //px转换为rem
    (function(doc, win) {
        var docEl = doc.documentElement,
            resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
            recalc = function() {
                var clientWidth = docEl.clientWidth;
                if (!clientWidth) return;
                if (clientWidth >= 640) {
                    docEl.style.fontSize = '100px';
                } else {
                    docEl.style.fontSize = 100 * (clientWidth / 640) + 'px';
                }
            };

        if (!doc.addEventListener) return;
        win.addEventListener(resizeEvt, recalc, false);
        doc.addEventListener('DOMContentLoaded', recalc, false);
    })(document, window);

    function imgChange(obj1, obj2) {
        //获取点击的文本框
        var file = document.getElementById("file");
        //存放图片的父级元素
        var imgContainer = document.getElementsByClassName(obj1)[0];
        //获取的图片文件
        var fileList = file.files;
        //文本框的父级元素
        var input = document.getElementsByClassName(obj2)[0];
        var imgArr = [];
        //遍历获取到得图片文件
        for (var i = 0; i < fileList.length; i++) {
            var imgUrl = window.URL.createObjectURL(file.files[i]);
            imgArr.push(imgUrl);
            var img = document.createElement("img");
            img.setAttribute("src", imgArr[i]);
            img.setAttribute("style", "cursor:pointer");
            img.setAttribute("onclick","priview_img()");
            var imgAdd = document.createElement("div");
            imgAdd.setAttribute("class", "z_addImg");

            var imggb = document.createElement("i");
            imggb.setAttribute("class", "iconfont icon-cuowu");
            var jindu = document.createElement("div");
            jindu.setAttribute("class", "jqmeter-container");

            imgAdd.appendChild(img);
            imgAdd.appendChild(imggb);
            imgAdd.appendChild(jindu);
            imgContainer.appendChild(imgAdd);
        };

        imgRemove();
    };

    function imgRemove() {
        var imgI = document.getElementsByClassName("iconfont icon-cuowu");
        var imgList = document.getElementsByClassName("z_addImg");
        var mask = document.getElementsByClassName("z_mask")[0];
        var cancel = document.getElementsByClassName("z_cancel")[0];
        var sure = document.getElementsByClassName("z_sure")[0];
        var aPush = document.getElementsByClassName("sc-push")[0];
        var aRemo = document.getElementsByClassName("sc-remove")[0];


        for (var j = 0; j < imgList.length; j++) {
            imgI[j].index = j;
            imgI[j].onclick = function() {
                var t = this;
                mask.style.display = "block";
                cancel.onclick = function() {
                    mask.style.display = "none";
                };
                sure.onclick = function() {
                    mask.style.display = "none";
                    imgList[t.index].remove();
                    imgRemove();
                };
            };
        };

        aPush.onclick = function(){

            $(function(){
                $('.jqmeter-container').jQMeter({
                    goal:'$2,000',
                    raised:'$2,000',
                    width:'100%',
                    height:'20px'
                });
                $('.iconfont.icon-cuowu').hide();
            });
        }
        $(document).ready(function(){
            $(".sc-remove").click(function(){
                $(".z_addImg").remove();
            });
        });

    };



    function priview_img(){
        $('#test').FlyZommImg({
            rollSpeed:200,//切换速度
            miscellaneous:true,//是否显示底部辅助按钮
            closeBtn:true,//是否打开右上角关闭按钮
            hideClass:'hide',//不需要显示预览的 class
            imgQuality:'thumb',//图片质量类型  thumb 缩略图 默认 original 原图
            slitherCallback:function(direction,DOM){
                //左滑动回调 两个参数 第一个动向 'left,firstClick,close' 第二个 当前操作DOM
                console.log(direction,DOM);
            }
        });
    }

</script>


</html>