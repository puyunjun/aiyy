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

var j =0;
var all_progress = 0;  //设置总进度数量,上传完毕后进行ajax提交程序
var up_data = new FormData();   //创建表单提交项
var key_arr_obi =[];
var uploadFile = function (client) {
  var fileobj = [];

  for(var m=0;m<$('input[type=file]').length;m++){
      fileobj.push($('input[type=file]').eq(m)[0].files[0]);
  }

  console.log(fileobj)
  for(var i=0;i<fileobj.length;i++){
      var keyobj = 'authentication/'+get_date()+'/'+new Date().getTime()+i+'.png';
        key_arr_obi[i] = keyobj;
       client.multipartUpload(keyobj, fileobj[i], {
          progress: function (p) {
              return function (done) {
                  var bar = document.getElementById('progress-bar'+j);
                  if(p === 1){
                      j++;
                  }
                  bar.style.width = Math.floor(p * 100) + '%';
                  bar.innerHTML = Math.floor(p * 100) + '%';
                  done();
              };
          },
      }).then(function (res) {
          //console.log('upload success: %j', res);
          //console.log('upload success: %j', res);
          //console.log(i);
          console.log(key_arr_obi);
            var img_url = res.res.requestUrls[0].indexOf('?')>-1 ? res.res.requestUrls[0].substring(0,res.res.requestUrls[0].indexOf('?')) : res.res.requestUrls[0];
           //获取url最后一位数字，即为对应输入框id标号
           var last_num = img_url.substring(img_url.lastIndexOf('.')-1,img_url.lastIndexOf('.'));
           //console.log(last_num);
           //利用最后标识(即为第几个文件名的值)赋值
           //console.log(img_url.lastIndexOf('.'));
            up_data.append($('input[type=file]').eq(last_num).attr('name'),img_url);    //对应文件名  important
           //console.log($('input[type=file]').eq(all_progress).attr('name'));
           //console.log(img_url);
           //console.log(up_data);
          all_progress++;

          if(all_progress === fileobj.length){
              //加入身份证号码
              up_data.append('id_card_num',$('#id_card_num').val());
              $.ajax({
                  url:'http://'+window.location.host+'/user/Authentication/up_authenticate',
                  dataType:'JSON',
                  data:up_data,
                  processData: false,  // 不处理数据
                  contentType: false,   // 不设置内容类型
                  type:'post',
                  success:function(res){

                      //console.log(res);
                        if(res){
                            layer.msg('添加成功',function () {
                                //window.location.href='http://'+window.location.host+'/user/index/index';
                            });
                        }
                  }
              });
          }
      });
  }

};
window.onload = function () {
  document.getElementById('uploadFile_bt').onclick = function () {
      for(var m=0;m<$('input[type=file]').length;m++){
          if($('input[type=file]').eq(m).val() === ''){
              alert('请将照片信息添加完整');
              return false;
          }
      }
      if($('#id_card_num').val() ===''){
          alert('请填写身份证号码');
          return false;
      }
     applyTokenDo(uploadFile);
  };
};


//获取日期
function get_date(){
    var d = new Date();
    var str = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate();
    return str;
}
