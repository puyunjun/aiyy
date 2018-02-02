
function remove_id(attach_id,uid){

    if(confirm('是否删除,确定后不可恢复,取消则会暂时隐藏')){

        $.ajax({
            url:'http://'+window.location.host+'/admin.php/user/member/image_delete',
            data:{attach_id:attach_id,uid:uid},
            dataType:'JSON',
            type:'post',
            success:function(res){
                if(res.code === 200){
                    layer.msg(res.msg);
                }else{
                    layer.msg(res);
                }
            }
        })

    }

}