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
function check_sfz(status,id){
    var c,code ;
    if(status === 'y'){
        c = '通过审核';
        code = 1;   //保存数据库状态
    }else if(status === 'n'){
        c ='拒绝通过';
        code = 2;
    }
    layer.confirm('确定执行'+c+'?',function(index){
            $.ajax({
                url:'http://'+window.location.host+'/admin.php/user/identify/verify_sfz',
                data:{id:id,status:code},
                dataType:'JSON',
                type:'POST',
                success:function(res){
                   layer.msg(res,function(){
                       window.location.href=history.go(-1);
                   });
                }
            });
        layer.close(index);
    });
}

