$('.attention').click(function(){
        var  uid =this.getAttribute('value');
        var obj = this;
        $.ajax({
           url:'http://'+window.location.host+'/user/journey/attention',
           data:{uid:uid},
           dataType:'JSON',
           type:'POST',
            success:function(res){
                   layer.msg(res);
                   $(obj).css('background-image',"url(http://"+window.location.host+"/static/home/css/wapcssjsimg/images/ygz.png)");
                   $(obj).attr('class','');
                   $(obj).unbind('click');
            }
        });
});