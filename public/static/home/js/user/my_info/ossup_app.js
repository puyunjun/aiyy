'use strict';

var appServer = "http://"+window.location.host+"/index/Index/ossserver";
var bucket = 'puyunjun';
var region = 'oss-cn-beijing';

var urllib = OSS.urllib;
var Buffer = OSS.Buffer;
var OSS = OSS.Wrapper;
var STS = OSS.STS;

// Play without STS. NOT SAFE! Because access key id/secret are
// exposed in web page.

// var client = new OSS({
//   region: 'oss-cn-hangzhou',
//   accessKeyId: '<access-key-id>',
//   accessKeySecret: '<access-key-secret>',
//   bucket: '<bucket-name>'
// });
//
// var applyTokenDo = function (func) {
//   return func(client);
// };

var applyTokenDo = function (func) {
    var url = appServer;
    return urllib.request(url, {
        method: 'GET'
    }).then(function (result) {
        var creds = JSON.parse(result.data);
        var client = new OSS({
            region: region,
            accessKeyId: creds.AccessKeyId,
            accessKeySecret: creds.AccessKeySecret,
            stsToken: creds.SecurityToken,
            bucket: bucket
        });

        return func(client);
    });
};

var j =0;               //设置总上传进度数量初始值,根据该值选取对应id设置相应图片上传进度条
var server_num  = 0  ;   //设置总上传数量初始值

var uploadFile = function (client) {

    var fileobj = [];           //当前选择上传照片的对象集合
    var img_href = [];          //当前上传成功的照片url数组对象集合
    var current_num = 0;        //当前上传照片的个数进度
    var all_current_img_dom = [];   //当前上传成功的所有照片预览img dom对象集合
    var img_url ='';
    var last_group_num = [];        //收录当前上传成功照片名称最后一位对应码
    for(var m=0;m<document.getElementById("file").files.length;m++){
        fileobj.push(document.getElementById("file").files[m]);
    }
    var filearr =[];
    for (var y=0;y<fileobj.length;y++) {
        filearr.push(fileobj[y]); //属性
        //arr.push(object[i]); //值
    }

    //使用文件大小  size 进行排序  将其赋值给fileList
    fileobj  =  filearr.sort(compare('size'));
    //console.log(fileobj)
    //console.log(fileobj);

    for(var i=0;i<fileobj.length;i++){
        var keyobj = dir_name+'/'+get_date()+'/'+sign_id+new Date().getTime()+i+'.png';
        //console.log(fileobj[i]);
        var part_size = '';  //定义分片大小
        if(fileobj[i].size >= 1000*1024){
            //大于1M图片则分片200kb上传
            part_size = 200*1024;   //设为200kb分片上传
        }
        client.multipartUpload(keyobj, fileobj[i], {
            partSize:part_size,      //设置分片大小
            progress: function (p) {

                return function (done) {
                    var bar = document.getElementById('jqmeter-container'+j);

                    //判断当前选择器的进度是否小于服务器返回的进度，小于才赋值
                    //当前选择器没设置初始值，即为''  ——用于比较
                    if(parseFloat(bar.innerHTML=== '' ?0:bar.innerHTML)<=parseFloat(Math.floor(p * 100) + '%')){

                        //文件未传完，进度条设为当前进度   ——添加进度条样式即可
                        bar.style.width = Math.floor(p * 100) + '%';
                        bar.innerHTML = Math.floor(p * 100) + '%';
                    }
                    if(p === 1){
                        //文件已经传完，进度条直接设为100%；
                        bar.innerHTML = Math.floor(p * 100) + '%';
                        j++;
                    }
                    /*bar.style.width = Math.floor(p * 100) + '%';
                    bar.innerHTML = Math.floor(p * 100) + '%';*/
                    done();

                };
            },
        }).then(function (res) {

            //获取上传成功的url连接
             img_url = res.res.requestUrls[0].indexOf('?')>-1 ? res.res.requestUrls[0].substring(0,res.res.requestUrls[0].indexOf('?')) : res.res.requestUrls[0];
            //console.log($('#photo'+server_num));

            all_current_img_dom [current_num] = $('#photo'+server_num); //收录当前上传的所有img dom对象

            var last_num = img_url.substring(img_url.lastIndexOf('.')-1,img_url.lastIndexOf('.'));

            last_group_num[current_num] = last_num;

            $(all_current_img_dom[last_num]).attr('src',img_url);

            server_num++;
            img_href[current_num] = img_url;  //收录当前上传成功图片的url
            //console.log(img_href);
            current_num++;  //当前上传成功进度++

           if(current_num === fileobj.length){

              for(var x=0;x<all_current_img_dom.length;x++){
                    $(all_current_img_dom[last_group_num[x]]).attr('src',img_href[x]);
               }

                $.ajax({
                    url:'http://'+window.location.host+'/user/My_info/up_gr_photo',
                    dataType:'JSON',
                    data:{up_data:img_href},
                    //processData: false,  // 不处理数据
                    //contentType: false,   // 不设置内容类型
                    type:'post',
                    success:function(res){

                        if(res){

                            layer.msg(res,function () {
                                //window.location.href='http://'+window.location.host+'/user/index/index';
                            });
                        }
                    }
                });
            }


        });

    }
            //收录当前添加成功的图片url 集合
    //console.log(all_photo_object[0])
};


//删除方法
//返回promise 对象  需要再次调用得出结果   即  .then(function(value){console.log(value)})
var deleteFile = function(client){
    var result = client.deleteMulti(object_name, {
        quiet: true
            });
    return result;

}
function up_photo(){
       applyTokenDo(uploadFile);
}

//删除图片

function delete_photo(){
  return  applyTokenDo(deleteFile);
}
//获取日期
function get_date(){
    var d = new Date();
    var str = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate();
    return str;
}
