
$('#myform').submit(function () {
    //
    if(false){
        layer.msg('验证失败');
        return false;
    }
    var formElement = document.getElementById("myform");
    var formData = new FormData(formElement);
    formData.append('',$('#myform').serialize());
    $.ajax({
        url:'http://'+window.location.host+'/user/release/release',
        data:formData,
        dataType:'JSON',
        type:'post',
        processData: false,  // 不处理数据
        contentType: false,  // 不设置内容类型
        success:function(res){
            if(res === true){
                layer.msg('保存成功')
                window.location.href="http://"+window.location.host+"/user/journey/index.html";
            }else{
                layer.msg(res)
                //window.location.href="http://www.aiyy.com/user/release/index.html"
            }
        },
        error:function (e) {
            alert('服务器发生故障')
        }
    })
    return false;
});1