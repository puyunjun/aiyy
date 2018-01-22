var previewrWidth = 110;
var previewrHeight = 110;
var $img = $('#imgg');
$('#img').change(function() {
    var reader = new FileReader();
    reader.onload = function(e) {
        $img.prop('src', e.target.result);
    }
    reader.readAsDataURL(this.files[0]);
    console.log(this.files[0])
    $('#img').attr('value',$('#imgg').attr('src'));
    $('#imgg').load(function() {
        var size = autoSize(this.naturalWidth, this.naturalHeight);
        $(this).css({
            width: size.width,
            height: size.height,
            top: (previewrHeight - size.height) / 2,
            left: (previewrWidth - size.width) / 2
        }).show();
    });
});

function autoSize(width, height) {
    var scale = width / height;
    if (scale >= previewrWidth / previewrHeight) {
        height = previewrWidth / scale;
        width = previewrWidth;
    } else {
        width = previewrHeight * scale;
        height = previewrHeight;
    }
    return {
        width: width,
        height: height
    }
}

$('#form').submit(function () {
    //
    if(false){
        layer.msg('验证失败');
        return false;
    }
    var formElement = document.getElementById("form");
    var formData = new FormData(formElement);
    formData.append('',$('#form').serialize());
    $.ajax({
        url:'http://'+window.location.host+'/user/my_info/modify',
        data:formData,
        dataType:'JSON',
        type:'post',
        processData: false,  // 不处理数据
        contentType: false,  // 不设置内容类型
        success:function(res){
            if(res === true){
                layer.msg('保存成功')
            }else{
                layer.msg(res)
            }
        },
        error:function (e) {
            alert('服务器发生故障')
        }
    })
    return false;
});
