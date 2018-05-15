$(function() {
    /*input监听*/
    $('#account').bind('input propertyChange', function() {
        checkVal();
    });
    $('#name').bind('input propertyChange', function() {
        checkVal();
    });
    $('#name').bind('input propertyChange', function() {
        checkVal();
    });
    $('#amount').bind('input propertyChange', function() {
        amount();
    });
    var checkVal = function() {
        var name = $('#name').val();
        var account = $('#account').val();
        if(name && account) {
            $('.no-addTo').css({
                'background-color': '#fd58a4'
            });
        } else {
            $('.no-addTo').css({
                'background-color': '#f79cc7'
            });
        }
    }

    var amount = function() {
        var amount = $('#amount').val();
        if(amount) {
            $('.withdrawTo-bu').css({
                'background-color': '#fd58a4'
            });
        } else {
            $('.withdrawTo-bu').css({
                'background-color': '#f79cc7'
            });
        }
    }
});



$("#logIn").click(function() {
    var phone = $("#numbe").val();
    var password = $("#password").val();
    var verify_code = $("#verify_code").val();
    /*手机号码*/
    if(!(/^1[34578]\d{9}$/.test(phone))) {
        layer.msg('请输入正确手机号码');
        return false;
    }

    /*密码*/
    if(password.length == 0) {
        layer.msg('请输入密码')
        return false;
    }
    if(password.length < 6 || password.length >= 18) {
        alert('请输入6-18位密码');
        return false;
    }
    if(verify_code.length == 0) {
        layer.msg('请输入验证码')
        return false;
    }
    $.ajax({
        url:'http://'+window.location.host+'/broker/signin/update_ps',
        data:{mobile_phone:phone,password:password,verify_code:verify_code},
        dataType:'JSON',
        type:'POST',
        success:function(res){
            layer.msg(res.msg)
        }
    });
    return false;
})

/*
* 获取手机验证码
* */
function get_code(phone){

    $.ajax({
        url:'http://'+window.location.host+'/broker/signin/get_forget_code',
        data:{mobile_phone:phone},
        dataType:'JSON',
        type:'POST',
        success:function(res){
            layer.msg(res.msg)
        }
    });

}


