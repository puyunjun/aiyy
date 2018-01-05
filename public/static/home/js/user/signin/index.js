
$('body').css({"background":"url('"+url+"/static/home/css/wapcssjsimg/images/login.jpg') no-repeat top #121820", "background-size":"100% auto"});

function sub_fun(){

    $.showLoading("正在加载...");

    var phone = $('#mobile_phone').val();

    if(!(/^1[34578]\d{9}$/.test(phone))) {

        $.hideLoading();
        $.toast("请输入正确的手机号", "forbidden");

        return false;
    }

    var sex=$('input:radio[name="sex"]:checked').val();

    if(!sex){
        $.hideLoading();
        $.toast('请选择性别', 'forbidden');
        return false;
    }
    if(!/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/.test($('#password').val())){
        $.hideLoading();
        $.toast('请按提示格式输入密码', 'forbidden');
        return false;
    }
    if($('#password').val().length<6 || $('#password').val().length>16){
        $.hideLoading();
        $.toast('密码长度在6到16位之间', 'forbidden');
        return false;
    }
    var verify_code = $('#check_verify').val();

    if(verify_code.replace(/\s+/g, "") == ''){
        $.hideLoading();
        $.toast('输入验证码', 'forbidden');
        return false;
    }
    setTimeout(function(){
        $.ajax({
            url:'http://'+window.location.host+url+'/user/Signin/index',
            type:'post',
            dataType:'JSON',
            data:{username:phone,verify_code:verify_code,password:$('#password').val(),sex:sex},
            success:function(re){

                $.hideLoading();
                if(re.status === false){
                    layer.msg(re.msg)
                }else if(re.status === true){
                    layer.msg(re.msg)
                    $.toast('正在跳转', 'text');
                    window.location.href='http://'+window.location.host+url+'/user/Login/index'
                }else if(re.code === 202){
                    layer.msg(re.msg)
                }

            },
            error:function(e){
                $.hideLoading();
                layer.msg('服务器发生错误')
            }
        })
    },500);
    return false;
}


/*
* 手机获取验证码
* @param phone  电话号码
* @param object  obj 当前点击对象
* @param isForget   是否为忘记密码
* */
function get_verify(phone,obj,isForget){

    if(isForget){
        var datas = {mobile_phone:phone,forget:1}
    }else{
        datas = {mobile_phone:phone};
    }
    if(!(/^1[34578]\d{9}$/.test(phone))){

        $.hideLoading();

        $.toast("请输入正确的手机号码", "forbidden");
        return false;
    }else{
        $.showLoading("短信发送中，请保持手机通畅");
        $.ajax({
            url:'http://'+window.location.host+url+'/user/Signin/get_verify',  //请求短信接口方法
            type:'post',
            dataType:'JSON',
            data:datas,
            success:function(re){
                $.hideLoading();
                if(re.code === 200){
                    //隐藏当前对象
                    sessionStorage.setItem("key", 180);
                    layer.msg(re.msg);
                    time_conut(sessionStorage.getItem('key'),obj)
                }else if(re.code === 300){
                    sessionStorage.setItem("key", 10);
                    layer.msg(re.msg);
                    time_conut(sessionStorage.getItem('key'),obj)
                    layer.msg(re.msg);
                }else if(re.code === 201){
                    sessionStorage.setItem("key", 5);
                    layer.msg(re.msg);
                    time_conut(sessionStorage.getItem('key'),obj)
                    layer.msg(re.msg);
                }
            }
        })
    }
}

//计时函数

function time_conut(index,obj){
    if(index > 0){
        index--;
        setTimeout(function(){
            $(obj).html('等待'+index+'秒重新获取');
            $(obj).attr('disabled',true);
            time_conut(index,obj);
        },1000);
        sessionStorage.setItem("key", index);
    }else{
        $(obj).html('获取验证码');
        $(obj).removeAttr('disabled');
        sessionStorage.removeItem("key");
    }
}

//判断当前时间是否等待完毕
if(sessionStorage.getItem('key')>0){

    time_conut(sessionStorage.getItem('key'),$('#get_verify_bt'))
}


//window.location.href = 'http://'+window.location.host+url+'/api/Login/index';

