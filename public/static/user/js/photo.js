var p_obj = document.getElementsByClassName('uploader-list');
var arr = ['sfz_font_img','sfz_back_img','sfz_hand_img'];
for(var i=0;i<p_obj.length;i++){
    $(p_obj).eq(i).find('a').attr('href',$('#'+arr[i]).val());
    $(p_obj).eq(i).find('a').find('img').attr('src',$('#'+arr[i]).val());
    $("#picker_"+arr[i]).css('display','none');
}


