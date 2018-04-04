
function reply(){
    if(!$('#content').val().replace(/(^\s*)|(\s*$)/g, "")){
        layer.msg('不能回复空消息');
    }else{
        $.ajax({
            url:'http://'+window.location.host+'/admin.php/user/chat/edit',
            data:$('form').serialize(),
            dataType:'JSON',
            type:'POST',
            success:function(res){
                layer.msg(res);
            }
        });
    }

}


function history_chat(chat_sign){
    layer.open({
        type: 2,
        offset: 'auto',
        area: ['1200px', '1000px'],
        content: 'http://'+window.location.host+'/admin.php/user/chat/history_chat/chat_sign/'+chat_sign //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
    });
}