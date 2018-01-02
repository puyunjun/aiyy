/*
* 首页js
* */

/*
* 判断会员权限相关信息
* */
var is_allow = true;
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
                is_allow = false;
            }
        }
    })

    return is_allow;
}