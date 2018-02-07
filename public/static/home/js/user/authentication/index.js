
var previewrWidth = 110;
var previewrHeight = 110;
var $img = $('#imgView');
var $img1 = $('#imgView1');
var $img2 = $('#imgView2');
$('#uploadFile').change(function() {
    priview_img(this,$img);
    /*var reader = new FileReader();
    reader.onload = function(e) {
        $img.css('background', 'url('+e.target.result+')');
    }
    reader.readAsDataURL(this.files[0]);
    $('#imgView').load(function() {
        var size = autoSize(this.naturalWidth, this.naturalHeight);
        $(this).css({
            width: size.width,
            height: size.height,
            top: (previewrHeight - size.height) / 2,
            left: (previewrWidth - size.width) / 2
        }).show();
    });*/
});

$('#uploadFile1').change(function() {
    priview_img(this,$img1);
});
$('#uploadFile2').change(function() {
    priview_img(this,$img2);
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

function priview_img(file_obj,priview_obj){
    var reader = new FileReader();
    reader.onload = function(e) {
        priview_obj.css('background', 'url('+e.target.result+')');
    }
    reader.readAsDataURL(file_obj.files[0]);
    /*if(file_obj.files[0].type !== 'image/jpeg'){
        alert(file_obj.files[0].type);
    }*/
    //$('#img').attr('value',$('#imgg').attr('src'));
    $(priview_obj).load(function() {
        var size = autoSize(this.naturalWidth, this.naturalHeight);
        $(this).css({
            width: size.width,
            height: size.height,
            top: (previewrHeight - size.height) / 2,
            left: (previewrWidth - size.width) / 2
        }).show();
    });
}
