/*
* 首页js
* */

/*
* 判断会员权限相关信息
* */
var is_allow = false;
function allow_privew(){

    $.ajax({
        url:'http://'+window.location.host+'/user/Index/allow_privilege',
        dataType:'JSON',

        cache:false,

        async:false,
        success:function(res){
            if(res.status === false){
                alert(res.msg);
                is_allow = false;
            }else{
                is_allow = true;
            }
        }
    })

    return is_allow;
}


/*
* 手机获取验证码
* @param phone  电话号码
* @param object  obj 当前点击对象
* @param isForget   是否为忘记密码
* */
function get_verify(phone,obj){

    datas = {mobile_phone:phone,is_bindphone:1};
    if(!(/^1[34578]\d{9}$/.test(phone))){

        layer.msg("请输入正确的手机号码");
        return false;
    }else{
        $.ajax({
            url:'http://'+window.location.host+'/user/Signin/get_verify',  //请求短信接口方法
            type:'post',
            dataType:'JSON',
            data:datas,
            success:function(re){
                if(re.code === 200){
                    //隐藏当前对象
                    sessionStorage.setItem("key", 180);
                    layer.msg(re.msg);
                    time_conut(sessionStorage.getItem('key'),obj)
                }else if(re.code === 300){
                    sessionStorage.setItem("key", 10);
                    time_conut(sessionStorage.getItem('key'),obj)
                    layer.msg(re.msg);
                }else if(re.code === 201){
                    sessionStorage.setItem("key", 5);
                    time_conut(sessionStorage.getItem('key'),obj)
                    layer.msg(re.msg);
                }else if(re.code === 301){
                    sessionStorage.setItem("key", 10);
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
