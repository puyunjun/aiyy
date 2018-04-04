function jump_url(obj){
    var event = event||window.event;
    if(event.preventDefault){   // !IE
        event.preventDefault();
    }else{  //IE
        event.returnValue = false;
    }
    $.ajax({
        url:'http://'+window.location.host+'/user/become_member/is_bind',
        dataType:'JSON',
        type:'POST',
        success:function (data) {
            if(data === 0){
                //未绑定
                $('.tck').css('display','block');
            }else{
                window.location.href = $(obj).attr('href');
            }
        }
    });
}
$('.tck_botm_a2').click(function(){
    var tel=$('#mobile_phone').val();
    var yzm=$('#verify').val();
    $.post('http://'+window.location.host+'/user/index/bindphone',{
        phone:tel,
        verify:yzm
    },function(res){
        if(res === true){
            layer.msg('绑定成功',{time:1500},function(){
                $('a').removeAttr('onclick');
                $('.tck').hide();
            })
        }
    },'json')
})
$('.tck_botm_a1').click(function(){
    $('.tck').hide();
})



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