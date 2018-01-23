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