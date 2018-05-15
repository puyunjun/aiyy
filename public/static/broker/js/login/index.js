$("#logIn").click(function() {
    var phone = $("#numbe").val();
    var password = $("#password").val()
    /*手机号码*/
    if(password.length == 0) {
        layer.msg('请输入手机号码')
        return false;
    }
    if(!(/^1[34578]\d{9}$/.test(phone))) {
        layer.msg('请输入正确手机号码');
        return false;
    }
    /*密码*/
    if(password.length<6 || password.length>=18){
        layer.msg('请输入6-18位密码');
        return false;
    }

    $.ajax({
        url:'http://'+window.location.host+'/broker/login/check',
        data:{mobile_phone:phone,password:password},
        dataType:'JSON',
        type:'POST',
        success:function(res){
            if(res.code === 200){
                layer.msg(res.msg,function(){
                    window.location.href = 'http://'+window.location.host+'/broker/index/index';
                });
            }else{
                layer.msg(res.msg);
            }
        }
    });
    return false;
})