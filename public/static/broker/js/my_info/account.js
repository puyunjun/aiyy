function sub_form(){
    $.ajax({
        url:'http://'+window.location.host+'/broker/account/check',
        data:$('#form').serialize(),
        dataType:'JSON',
        type:'POST',
        success:function(res){
            if(res.code === 200){
                layer.msg(res.msg);
            }
            layer.msg(res.msg);
        }
    });
    return false;
}