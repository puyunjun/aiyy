/*
* 首页js
* */

/*
* 判断会员权限相关信息
* */
var is_allow = false;
function allow_privew(){

    $.ajax({
        url:'http://'+window.location.host+'/user/Index/allow_privilege',
        dataType:'JSON',

        cache:false,

        async:false,
        success:function(res){
            if(res.status === false){
                alert(res.msg);
                is_allow = false;
            }else{
                is_allow = true;
            }
        }
    })

    return is_allow;
}