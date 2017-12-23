$('body').css({"background":"url('"+url+"/static/home/css/wapcssjsimg/images/login.jpg') no-repeat top #121820", "background-size":"100% auto"});

function sub_fun(){

    $.showLoading("正在加载...");
    var phone = $('#mobile_phone').val();

    var password = $('#mobile_password').val();
//<div style="transform:scale(1.0,1.1);font-size: 30px;">请输入正确的手机号码</div>
    if(!(/^1[34578]\d{9}$/.test(phone))){
        setTimeout(function(){
            $.hideLoading();
            layer.msg('<div style="transform:scale(1.0,1.0);font-size: 30px;">请输入正确的手机号码</div>');
        },500)
        return false;
    }
    if( password.replace(/\s+/g, "") == '' ){
        setTimeout(function(){
            $.hideLoading();
            $.toast('请输入密码', 'forbidden');
        },500)
        return false;
    }
    setTimeout(function(){
        $.ajax({
            url:'http://'+window.location.host+url+'/user/Login/index',
            type:'post',
            dataType:'JSON',
            data:{username:phone,password:password},
            success:function(re){
                $.hideLoading();

                if(re.status === false){
                    layer.msg(re.msg,{icon:2});
                }else{
                    $.toast('正在跳转', 'text');
                    window.location.href='http://'+window.location.host+url+'/index/Index/index'
                }
            }
        })
    },500);
    return false;
}
