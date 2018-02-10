'use strict';

var appServer = "http://"+window.location.host+"/index/Index/ossserver";
var bucket = 'aiyueyoo';
var region = 'oss-cn-shenzhen';

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
//获取用户uid
var uid = $('#uid').val();
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

var progress = function (p) {
    return function (done) {
        var bar = document.getElementById('progress-bar');
        bar.style.width = Math.floor(p * 100) + '%';
        bar.innerHTML = Math.floor(p * 100) + '%';
        done();
    }
};

var uploadFile = function (client) {
    var file = document.getElementById('file').files[0];
    var key = 'video/'+get_date()+'/'+uid+new Date().getTime()+'.mp4';
    console.log(file.name + ' => ' + key);
     $('#object-key-file').val(key);
    return client.multipartUpload(key, file, {
        progress: progress
    }).then(function (res) {
        console.log('upload success: %j', res);

       var  img_url = res.res.requestUrls[0].indexOf('?')>-1 ? res.res.requestUrls[0].substring(0,res.res.requestUrls[0].indexOf('?')) : res.res.requestUrls[0];
        //return listFiles(client);
        $.ajax({
            url:'http://'+window.location.host+'/admin.php/user/member/up_gr_video',
            dataType:'JSON',
            data:{up_data:img_url,uid:uid},
            //processData: false,  // 不处理数据
            //contentType: false,   // 不设置内容类型
            type:'post',
            success:function(res){

                if(res){

                    layer.msg(res)
                }
            }
        });
    });
};

var uploadContent = function (client) {
    var content = document.getElementById('file-content').value.trim();
    var key = document.getElementById('object-key-content').value.trim() || 'object';
    console.log('content => ' + key);

    return client.put(key, new Buffer(content)).then(function (res) {
        return listFiles(client);
    });
};

var listFiles = function (client) {
    var table = document.getElementById('list-files-table');
    console.log('list files');

    return client.list({
        'max-keys': 100
    }).then(function (result) {
        var objects = result.objects.sort(function (a, b) {
            var ta = new Date(a.lastModified);
            var tb = new Date(b.lastModified);
            if (ta > tb) return -1;
            if (ta < tb) return 1;
            return 0;
        });

        var numRows = table.rows.length;
        for (var i = 1; i < numRows; i ++) {
            table.deleteRow(table.rows.length - 1);
        }

        for (var i = 0; i < Math.min(3, objects.length); i ++) {
            var row = table.insertRow(table.rows.length);
            row.insertCell(0).innerHTML = objects[i].name;
            row.insertCell(1).innerHTML = objects[i].size;
            row.insertCell(2).innerHTML = objects[i].lastModified;
        }
    });
};

var downloadFile = function (client) {
    var object = document.getElementById('dl-object-key').value.trim();
    var filename = document.getElementById('dl-file-name').value.trim();
    console.log(object + ' => ' + filename);

    var result = client.signatureUrl(object, {
        response: {
            'content-disposition': 'attachment; filename="' + filename + '"'
        }
    });
    window.location = result;

    return result;
};

window.onload = function () {
    document.getElementById('file-button').onclick = function () {
        applyTokenDo(uploadFile);
    };

    /*document.getElementById('content-button').onclick = function () {
        applyTokenDo(uploadContent);
    }

    document.getElementById('list-files-button').onclick = function () {
        applyTokenDo(listFiles);
    }

    document.getElementById('dl-button').onclick = function () {
        applyTokenDo(downloadFile);
    }

    applyTokenDo(listFiles);*/
};

//获取日期
function get_date(){
    var d = new Date();
    var str = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate();
    return str;
}
