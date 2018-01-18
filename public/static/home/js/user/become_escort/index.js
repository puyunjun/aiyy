
function sub_fun(){

    $.showLoading("正在加载...");

    var phone = $('#mobile_phone').val();

    if(!(/^1[34578]\d{9}$/.test(phone))) {

        $.hideLoading();
        $.toast("请输入正确的手机号", "forbidden");

        return false;
    }

    var real_name = $('#real_name').val();

    if(!real_name){
        $.hideLoading();
        $.toast('请输入姓名', 'forbidden');
        return false;
    }
    var sfzh =$('#sfzh').val();
    if (!sfzh){
        $.hideLoading();
        $.toast('请输入省份证号', 'forbidden');
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
            url:'http://'+window.location.host+'/user/become_escort/ajax',
            //url:'/user/become_escort/ajax',
            type:'post',
            dataType:'JSON',
            data:{verify_code:verify_code},
            success:function(re){
                $.hideLoading();
                if(re.status === false){
                    layer.msg(re.msg)
                }else if(re.status === true){
                    layer.msg(re.msg,function(){
                        window.location.href='http://'+window.location.host+'/user/index/index';
                    })
                    //$.toast('正在跳转', 'text');
                    //window.location.href='http://'+window.location.host+url+'/user/Login/index'
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
            //url:"{:url('user/BecomeEscort/get_verify')}",
            url:'http://'+window.location.host+'/user/become_escort/get_verify',  //请求短信接口方法
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