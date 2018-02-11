$('body').css({"background":"url('"+url+"/static/home/css/wapcssjsimg/images/login.jpg') no-repeat top #121820", "background-size":"100% auto"});


function sub_fun(){

    point_lng = $('#x').val();
    point_lat = $('#y').val();
    $.showLoading("正在加载...");
    var phone = $('#mobile_phone').val();

    var password = $('#mobile_password').val();
//<div style="transform:scale(1.0,1.1);font-size: 30px;">请输入正确的手机号码</div>
    if(!(/^1[34578]\d{9}$/.test(phone))){
        setTimeout(function(){
            $.hideLoading();
            layer.msg('<div style="transform:scale(1.0,1.0);font-size: 30px;">请输入正确的手机号码</div>');
        },500)
        return false;
    }
    if( password.replace(/\s+/g, "") == '' ){
        setTimeout(function(){
            $.hideLoading();
            $.toast('请输入密码', 'forbidden');
        },500)
        return false;
    }
    setTimeout(function(){
        $.ajax({
            url:'http://'+window.location.host+url+'/user/Login/index',
            type:'post',
            dataType:'JSON',
            data:{username:phone,password:password,login_addr_x:point_lng,login_addr_y:point_lat},
            success:function(re){
                $.hideLoading();

                if(re.status === false){
                    layer.msg(re.msg);
                }else{
                    $.toast('正在跳转', 'text');
                    window.location.href='http://'+window.location.host+url+'/index/Index/index'
                }
            }
        })
    },500);
    return false;
}





function get_current_locate(){
    // 百度地图API功能
    var map = new BMap.Map("allmap");
    var point = new BMap.Point(116.331398,39.897445);
    map.centerAndZoom(point,12);

    var geolocation = new BMap.Geolocation();

//获取当前坐标
    geolocation.getCurrentPosition(function(r){
        if(this.getStatus() == BMAP_STATUS_SUCCESS){
            var mk = new BMap.Marker(r.point);
            map.addOverlay(mk);
            map.panTo(r.point);
            //alert('您的位置：'+r.point.lng+','+r.point.lat);
        }
        else {
            alert('failed'+this.getStatus());
        }
    },{enableHighAccuracy: true})
//关于状态码
//BMAP_STATUS_SUCCESS	检索成功。对应数值“0”。
//BMAP_STATUS_CITY_LIST	城市列表。对应数值“1”。
//BMAP_STATUS_UNKNOWN_LOCATION	位置结果未知。对应数值“2”。
//BMAP_STATUS_UNKNOWN_ROUTE	导航结果未知。对应数值“3”。
//BMAP_STATUS_INVALID_KEY	非法密钥。对应数值“4”。
//BMAP_STATUS_INVALID_REQUEST	非法请求。对应数值“5”。
//BMAP_STATUS_PERMISSION_DENIED	没有权限。对应数值“6”。(自 1.1 新增)
//BMAP_STATUS_SERVICE_UNAVAILABLE	服务不可用。对应数值“7”。(自 1.1 新增)
//BMAP_STATUS_TIMEOUT	超时。对应数值“8”。(自 1.1 新增)

    var pointA = new BMap.Point(106.486654,29.490295);  // 创建点坐标A--大渡口区
    var pointB = new BMap.Point(106.53063501,29.54460611);  // 创建点坐标B--江北区
//获取两点距离,保留小数点后两位
    alert('从大渡口区到渝北区的距离是：'+(map.getDistance(pointA,pointB)).toFixed(2)+' 米。');
}



