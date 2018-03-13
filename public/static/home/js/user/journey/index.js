$('#attention').click(function(){
        var  uid =this.getAttribute('value');

        $.ajax({
           url:'http://'+window.location.host+'/user/journey/attention',
           data:{uid:uid},
           dataType:'JSON',
           type:'POST',
            success:function(res){
                   layer.msg(res);
            }
        });
});