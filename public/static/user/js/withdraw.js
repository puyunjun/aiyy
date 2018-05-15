function check_withdraw(id){
    $.ajax({
        url:'http://'+window.location.host+'/admin.php/user/broker_user/withdraw_check',
        data:{id:id},
        dataType:'JSON',
        type:'POST',
        success:function(res){
            layer.msg(res.msg,function(){
                window.location.href=history.go(-1);
            });
        }
    });
}
