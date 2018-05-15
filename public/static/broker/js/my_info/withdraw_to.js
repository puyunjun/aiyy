$('#all_money').click(function(){

    var all_money= $('#account_money').html();
    $('#amount').val(all_money);
});

$('#withdrawTo-bu').click(function(){

    if(parseFloat($('#amount').val())>parseFloat($('#account_money').html())){
        layer.msg('金额不足')
        //没有金额时
        return false;
    }
    if(!parseFloat($('#amount').val())){
        //没有金额时
        return false;
    }else{
       $.ajax({
           url:'http://'+window.location.host+'/broker/account/withdraw_to',
           data:{money:parseFloat($('#amount').val())},
           dataType:'JSON',
           type:'POST',
           success:function(res){
               layer.msg(res.msg)
           }
       })
    }
})