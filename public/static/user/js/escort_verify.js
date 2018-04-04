
var img_p = document.getElementById('file_list_head_img');
$(img_p).find('a').attr('href',$('#head_img').val());
$(img_p).find('a').find('img').attr('src',$('#head_img').val());

$("#picker_head_img").css('display','none');

//审核
/*
* @param string status 审核状态 y=>成功，n=>拒绝通过
* @param int id 主键id
* */
function check_sfz(id,status){

    $.ajax({
        url:'http://'+window.location.host+'/admin.php/user/escort_verify/verify_escort',
        data:{id:id,status:status},
        dataType:'JSON',
        type:'POST',
        success:function(res){

        layer.msg('审核成功',function(){
               window.location.href=history.go(-1);
        })

        }
    });
}

