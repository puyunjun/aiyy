
accessid = ''
accesskey = ''
host = ''
policyBase64 = ''
signature = ''
callbackbody = ''
filename = ''
key = ''
expire = 0
g_object_name = ''
g_object_name_type = ''
now = timestamp = Date.parse(new Date()) / 1000;

function send_request()
{
    var xmlhttp = null;
    if (window.XMLHttpRequest)
    {
        xmlhttp=new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    if (xmlhttp!=null)
    {
        serverUrl = 'http://'+window.location.host+'/user/Authentication/get_oss_up';
        xmlhttp.open( "GET", serverUrl, false );
        xmlhttp.send( null );
        return xmlhttp.responseText
    }
    else
    {
        alert("Your browser does not support XMLHTTP.");
    }
};

function check_object_radio() {
    var tt = document.getElementsByName('myradio');
    for (var i = 0; i < tt.length ; i++ )
    {
        if(tt[i].checked)
        {
            g_object_name_type = tt[i].value;
            break;
        }
    }
}

function get_signature()
{
    //可以判断当前expire是否超过了当前时间,如果超过了当前时间,就重新取一下.3s 做为缓冲
    now = timestamp = Date.parse(new Date()) / 1000;
    if (expire < now + 3)
    {
        body = send_request()
        var obj = eval ("(" + body + ")");
        host = obj['host']
        policyBase64 = obj['policy']
        accessid = obj['accessid']
        signature = obj['signature']
        expire = parseInt(obj['expire'])
        callbackbody = obj['callback']
        key = obj['dir']
        return true;
    }
    return false;
};

function random_string(len) {
    len = len || 32;
    var chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
    var maxPos = chars.length;
    var pwd = '';
    for (i = 0; i < len; i++) {
        pwd += chars.charAt(Math.floor(Math.random() * maxPos));
    }
    return pwd;
}

function get_suffix(filename) {
    pos = filename.lastIndexOf('.')
    suffix = ''
    if (pos != -1) {
        suffix = filename.substring(pos)
    }
    return suffix;
}

function calculate_object_name(filename,type)
{

    var now = Date.parse(new Date()) / 1000;

    //g_object_name += 'grphoto/'+get_date()+'/'+now+'.png';

    if (g_object_name_type == 'local_name')
    {
        g_object_name += "${filename}"
    }
    else
    {
        suffix = get_suffix(filename)
        if(type === 'video'){
            g_object_name = key + 'grvideo/'+get_date()+'/'+random_string(10)+ suffix;
        }else{
            g_object_name = key + 'grphoto/'+get_date()+'/'+random_string(10)+ suffix;
        }
    }
    return ''
}

function get_uploaded_object_name(filename)
{
    //直接返回名称;
    return g_object_name;

}

function set_upload_param(up, filename, ret,type)
{
    if (ret == false)
    {
        ret = get_signature()
    }
    g_object_name = key;
    if (filename != '') {
        suffix = get_suffix(filename)
        calculate_object_name(filename,type)
    }
    new_multipart_params = {
        'key' : g_object_name,
        'policy': policyBase64,
        'OSSAccessKeyId': accessid,
        'success_action_status' : '200', //让服务端返回200,不然，默认会返回204
        'callback' : callbackbody,
        'signature': signature,
    };

    up.setOption({
        'url': host,
        'multipart_params': new_multipart_params
    });

    up.start();
}
var p=0; //设置上传完成总进度初始值
var up_data = {}; //设置收录上传数据数据
var index_name ;   //设置上传id索引名
var uploader = new plupload.Uploader({
    runtimes : 'html5,flash,silverlight,html4',
    browse_button : 'selectfiles',
    //multi_selection: false,
    unique_names:true,
    container: 'containers',
    flash_swf_url : 'lib/plupload-2.1.2/js/Moxie.swf',
    silverlight_xap_url : 'lib/plupload-2.1.2/js/Moxie.xap',
    url : 'http://oss.aiyueyoo.com',

    filters: {
        mime_types : [ //只允许上传图片和zip,rar文件
            { title : "Image files", extensions : "jpg,gif,png,jpeg,bmp" },
            /*{ title : "Zip files", extensions : "zip,rar" }*/
        ],
        max_file_size : '10mb', //最大只能上传10mb的文件
        prevent_duplicates : false //允许选取重复文件
    },

    init: {
        PostInit: function() {
            document.getElementById('ossfile').innerHTML = '';
        },

        FilesAdded: function(up, files) {
            plupload.each(files, function(file) {
                //获取原生文件对象
                var fileObj = file.getNative();

                url=window.URL.createObjectURL(fileObj)  // 得到bolb对象路径，可当成普通的文件路径一样使用，赋值给src;

                /*document.getElementById('preview').innerHTML += '<div class="pic_list" id="' + file.id + '">' + file.name + '<a class="pic_delete" data-val="' + file.id + '">删除</a><img class="image_privew" src="' +url+ '"/> (' + plupload.formatSize(file.size) + ')<b></b>'
                    +'<div class="progress"><div class="progress-bar" style="width: 0%"></div></div>'
                    +'</div>';*/
                document.getElementById('preview').innerHTML += '<div class="z_addImg" id="' + file.id + '">' +
                    '<img src="'+url+'" avg_id=""  style="cursor:pointer"><b></b>' +
                    '<div class="progress"><div class="progress-bar" style="width: 0%"></div></div>' +
                    '</div>';
                preview_imgs();
            });
            //加载点击预览图片函数
           // preview();
            set_upload_param(uploader, '', false);
        },
        BeforeUpload: function(up, file) {
            check_object_radio();
            set_upload_param(up, file.name, true);
        },

        UploadProgress: function(up, file) {
            var d = document.getElementById(file.id);
            d.getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
            var prog = d.getElementsByTagName('div')[0];
            var progBar = prog.getElementsByTagName('div')[0]
            progBar.style.width= 2*file.percent+'px';
            progBar.setAttribute('aria-valuenow', file.percent);
        },

        FileUploaded: function(up, file, info) {
            if (info.status == 200)
            {
                //收录上传数据
                //up_data.push(host+'/'+get_uploaded_object_name(file.name)) ;
                up_data[file.id] = host+'/'+get_uploaded_object_name(file.name);
                p++;
                console.log(up_data)
                if(p===up.files.length){
                    //添加数据
                    $.ajax({
                        url:'http://'+window.location.host+'/user/my_info/up_gr_photo',
                        dataType:'JSON',
                        data:up_data,
                        //processData: false,  // 不处理数据
                        //contentType: false,   // 不设置内容类型
                        type:'post',
                        success:function(res){
                            if(res.code==200){
                                up_data={};
                                index_name = res.id_data;
                                $.each(index_name,function(index){
                                    //赋值id
                                    $(document.getElementById(index)).find('img').attr('avg_id',index_name[index]);
                                })
                                layer.msg(res.msg,function () {
                                    //window.location.href='http://'+window.location.host+'/user/index/index';
                                });
                            }
                        }
                    });

                }
                //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = host+'/'+get_uploaded_object_name(file.name);
                $(document.getElementById(file.id)).find('img').attr('src',host+'/'+get_uploaded_object_name(file.name));
            }
            else
            {
                document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = info.response;
            }
        },

        Error: function(up, err) {
            if (err.code == -600) {
                document.getElementById('console').appendChild(document.createTextNode("\n选择的文件太大了,可以根据应用情况，在upload.js 设置一下上传的最大大小"));
            }
            else if (err.code == -601) {
                layer.msg('图片格式不正确');
                //document.getElementById('console').appendChild(document.createTextNode("\n选择的文件后缀不对,可以根据应用情况，在upload.js进行设置可允许的上传文件类型"));
            }
            else if (err.code == -602) {
                document.getElementById('console').appendChild(document.createTextNode("\n这个文件已经上传过一遍了"));
            }
            else
            {
                document.getElementById('console').appendChild(document.createTextNode("\nError xml:" + err.response));
            }
        }
    }
});

uploader.init();


var uploader_video = new plupload.Uploader({
    runtimes : 'html5,flash,silverlight,html4',
    browse_button : 'selects',
    //multi_selection: false,
    unique_names:true,
    container: document.getElementById('containers'),
    flash_swf_url : 'lib/plupload-2.1.2/js/Moxie.swf',
    silverlight_xap_url : 'lib/plupload-2.1.2/js/Moxie.xap',
    url : 'http://oss.aiyueyoo.com',

    filters: {
        mime_types : [ //只允许上传视频文件
            {title: "Video files", extensions: "mpg,m4v,mp4,flv,3gp,mov,avi,rmvb,mkv,wmv"}
            /*{ title : "Zip files", extensions : "zip,rar" }*/
        ],
        max_file_size : '200mb', //最大只能上传10mb的文件
        prevent_duplicates : false //允许选取重复文件
    },

    init: {
        PostInit: function() {
            document.getElementById('ossfile').innerHTML = '';
        },

        FilesAdded: function(up, files) {
            plupload.each(files, function(file) {
                //获取原生文件对象
                var fileObj = file.getNative();

                console.log(file);
                //url=window.URL.createObjectURL(fileObj)  // 得到bolb对象路径，可当成普通的文件路径一样使用，赋值给src;

                document.getElementById('preview').innerHTML += '<div class="pic_list" id="' + file.id + '">' + file.name + '<a class="pic_delete" data-val="' + file.id + '">删除</a>(' + plupload.formatSize(file.size) + ')<b></b>'
                    +'<div class="progress"><div class="progress-bar" style="width: 0%"></div></div>'
                    +'</div>';
            });
            //加载点击预览图片函数
            // preview();
            set_upload_param(uploader_video, '', false,'video');
        },
        BeforeUpload: function(up, file) {
            check_object_radio();
            set_upload_param(up, file.name, true,'video');
        },

        UploadProgress: function(up, file) {
            var d = document.getElementById(file.id);
            d.getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
            var prog = d.getElementsByTagName('div')[0];
            var progBar = prog.getElementsByTagName('div')[0]
            progBar.style.width= 2*file.percent+'px';
            progBar.setAttribute('aria-valuenow', file.percent);
        },

        FileUploaded: function(up, file, info) {
            if (info.status == 200)
            {
                //收录上传数据
                up_data[p]= host+'/'+get_uploaded_object_name(file.name);
                p++;
                if(p===up.files.length){
                    //添加数据
                    $.ajax({
                        url:'http://'+window.location.host+'/user/my_info/up_gr_video',
                        dataType:'JSON',
                        data:{up_data:up_data},
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
                //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = 'upload to oss success, object name:' + get_uploaded_object_name(file.name);
            }
            else
            {
                document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = info.response;
            }
        },

        Error: function(up, err) {
            if (err.code == -600) {
                document.getElementById('console').appendChild(document.createTextNode("\n选择的文件太大了,可以根据应用情况，在upload.js 设置一下上传的最大大小"));
            }
            else if (err.code == -601) {
                layer.msg('视频文件格式不正确');
                //document.getElementById('console').appendChild(document.createTextNode("\n选择的文件后缀不对,可以根据应用情况，在upload.js进行设置可允许的上传文件类型"));
            }
            else if (err.code == -602) {
                document.getElementById('console').appendChild(document.createTextNode("\n这个文件已经上传过一遍了"));
            }
            else
            {
                document.getElementById('console').appendChild(document.createTextNode("\nError xml:" + err.response));
            }
        }
    }
});

uploader_video.init();


