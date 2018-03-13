$('#form').submit(function () {
    var formElement = document.getElementById("form");
    var id= document.getElementById("id").value
    var formData = new FormData(formElement);
    formData.append('',$('#form').serialize());
    $.ajax({
        url:'http://'+window.location.host+'/user/my_info/mody/id/'+id,
        data:formData,
        dataType:'JSON',
        type:'post',
        processData: false,  // 不处理数据
        contentType: false,  // 不设置内容类型
        success:function(res){
            if(res === true){
                layer.msg('保存成功')
                window.location.href = "http://"+window.location.host+"/user/my_info/index.html";
            }else{
                layer.msg(res)
                //window.location.href='http://www.aiyy.com/user/my_info/show/id/'+id+'.html'
            }
        },
        error:function (e) {
            alert('服务器发生故障')
        }
    })
    return false;
});


/*
* 手机获取验证码
* @param phone  电话号码
* @param object  obj 当前点击对象
* @param isForget   是否为忘记密码
* */
function get_verify(phone,obj){
    datas = {mobile_phone:phone};
    if(!(/^1[34578]\d{9}$/.test(phone))){
        layer.msg("请输入正确的手机号码");
        return false;
    }else{
        $.ajax({
            url:'http://'+window.location.host+'/user/Signin/get_verify',  //请求短信接口方法
            type:'post',
            dataType:'JSON',
            data:datas,
            success:function(re){
                if(re.code === 200){
                    //隐藏当前对象
                    sessionStorage.setItem("key", 180);
                    layer.msg(re.msg);
                    time_conut(sessionStorage.getItem('key'),obj)
                }else if(re.code === 300){
                    sessionStorage.setItem("key", 10);
                    time_conut(sessionStorage.getItem('key'),obj)
                    layer.msg(re.msg);
                }else if(re.code === 201){
                    sessionStorage.setItem("key", 5);
                    time_conut(sessionStorage.getItem('key'),obj)
                    layer.msg(re.msg);
                }else if(re.code === 301){
                    sessionStorage.setItem("key", 10);
                    time_conut(sessionStorage.getItem('key'),obj)
                    layer.msg(re.msg);
                }
            }
        })
    }
}

//计时函数
var attr = $("#get_verify_bt").attr("onclick");  //先储存点击事件
function time_conut(index,obj){

    if(index > 0){
        index--;
        setTimeout(function(){
            $(obj).html('等待'+index+'秒重新获取');
            //规定时间内清除绑定事件
            $("#get_verify_bt").attr("onclick", "");
            time_conut(index,obj);
        },1000);
        sessionStorage.setItem("key", index);
    }else{
        $(obj).html('获取验证码');

        //重新赋值绑定事件
        $("#get_verify_bt").attr("onclick", attr);

        sessionStorage.removeItem("key");
    }
}

//判断当前时间是否等待完毕
if(sessionStorage.getItem('key')>0){

    time_conut(sessionStorage.getItem('key'),$('#get_verify_bt'))
}
