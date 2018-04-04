var p_obj = document.getElementsByClassName('uploader-list');
var arr = ['sfz_font_img','sfz_back_img','sfz_hand_img'];
for(var i=0;i<p_obj.length;i++){
    $(p_obj).eq(i).find('a').attr('href',$('#'+arr[i]).val());
    $(p_obj).eq(i).find('a').find('img').attr('src',$('#'+arr[i]).val());
    $("#picker_"+arr[i]).css('display','none');
}


//审核
/*
* @param string status 审核状态 y=>成功，n=>拒绝通过
* @param int id 主键id
* */
function check_sfz(id){
    var c='审核';
        var index = layer.load(1)
            $.ajax({
                url:'http://'+window.location.host+'/admin.php/user/identify/verify_sfz',
                data:{id:id},
                dataType:'JSON',
                type:'POST',
                success:function(res){
                   layer.msg(res,function(){
                       layer.close(index);
                       window.location.href=history.go(-1);
                   });
                }
            });
}

