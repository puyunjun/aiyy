
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
var name_arr = [];  //定义上传名称数组
var sel_name;   //定义选择的名称
var p=0; //定义上传完的进程个数
var up_data = new FormData(); //定义上传的数据
var all_file = [] ;//定义有效选择的总文件
function calculate_object_name(filename,index)
{
    switch(index)
    {
        case 0:
            sel_name = 'z';//身份证正面
            break;
        case 1:
            sel_name = 'f';//身份证反面
            break;
        case 2:
            sel_name = 's';//身份证手持照
            break;
        default:
            break;
    }
    var now = Date.parse(new Date()) / 1000;
    name_arr.push('authentication/'+get_date()+'/'+now+index+sel_name+uid+'.png');

    g_object_name += 'authentication/'+get_date()+'/'+now+index+sel_name+uid+'.png';
    return '';
}

function get_uploaded_object_name(filename)
{
    return g_object_name;
}

function set_upload_param(up, filename, ret,index)
{
    if (ret == false)
    {
        ret = get_signature()
    }
    g_object_name = key;
    if (filename != '') {
        suffix = get_suffix(filename)
        calculate_object_name(filename,index)
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
$(".but_up").each(function(index,ev) {
    var but_id = $(this).attr('id');

var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : but_id,
    multi_selection: false,  //不允许上传多张
    //unique_names:true,      //生成唯一文件名
	container: document.getElementById('containers'),
	flash_swf_url : 'lib/plupload-2.1.2/js/Moxie.swf',
	silverlight_xap_url : 'lib/plupload-2.1.2/js/Moxie.xap',
    url : 'http://m.aiyueyoo.com',

    filters: {
        mime_types : [ //只允许上传图片
        { title : "Image files", extensions : "jpg,gif,png,bmp,jpeg" },
        /*{ title : "Zip files", extensions : "zip,rar" }*/
        ],
        max_file_size : '10mb', //最大只能上传10mb的文件
        prevent_duplicates : true //不允许选取重复文件
    },

	init: {
		PostInit: function() {
			document.getElementById('ossfile').innerHTML = '';
			document.getElementById(but_id+'_postfiles').onclick = function() {
            set_upload_param(uploader, '', false,index);
            return false;
			};
		},

		FilesAdded: function(up, files) {
			plupload.each(files, function(file) {
                if (up.files.length > 1) {
                    //console.log(up.files[0]);
                    uploader.removeFile(up.files[0]);
                }
                all_file[index] = up.files[0];
                //获取原生文件对象
                var fileObj = file.getNative();

                url=window.URL.createObjectURL(fileObj)  // 得到bolb对象路径，可当成普通的文件路径一样使用，赋值给src;
                var preview_obj = document.getElementById(but_id+'_preview');  //获取图片预览对象
                $(preview_obj).css('background','url('+url+')')
                //读取图片信息并赋值
                $(document.getElementById(but_id+'_info')).html('<div class="pic_list" id="' + file.id + '">' + file.name + '(' + plupload.formatSize(file.size) + ')<b></b>' +
                    '<div class="progress"><div class="progress-bar" style="width: 0%"></div></div>'
                    +'</div>')
				/*document.getElementById('ossfile').innerHTML += '<div class="pic_list" id="' + file.id + '">' + file.name + '<a class="pic_delete" data-val="' + file.id + '">删除</a><img style="height: 100px;width: 100px;" class="image_privew" src="' +url+ '"/> (' + plupload.formatSize(file.size) + ')<b></b>'
				+'<div class="progress"><div class="progress-bar" style="width: 0%"></div></div>'
				+'</div>';*/
			});
		},
		BeforeUpload: function(up, file) {
            check_object_radio();
            set_upload_param(up, file.name, true,index); //true 上传开始
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

                //获取url最后两位字符，即为选择的证件面标号和对应用户uid
                var last_code = name_arr[index].substring(name_arr[index].lastIndexOf('.')-2,name_arr[index].lastIndexOf('.'));
                if(last_code.indexOf('z') > -1){
                    up_data.append('sfz_font_img',host+'/'+key+name_arr[index]);
                }else if(last_code.indexOf('f') > -1){
                    up_data.append('sfz_back_img',host+'/'+key+name_arr[index]);
                }else if(last_code.indexOf('s') > -1){
                    up_data.append('sfz_hand_img',host+'/'+key+name_arr[index]);
                }
                up_data.append('id_card_num',$('#id_card_num').val());
                //console.log(last_code);
                //处理数据，整理好写入数据库
                p++;
                if(p === 3){
                    //三张照片已上传完写入数据库
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
                document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = 'upload to oss success, object name:' + key+name_arr[index];
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
                layer.msg('照片格式错误，可选择相机拍摄直接上传')
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
//移除文件列表的文件
    $(document).on('click', '.pic_list a.pic_delete', function () {
        $(this).parent().remove();
        var toremove = '';
        var id = $(this).attr("data-val");
        for (var i in uploader.files) {
            if (uploader.files[i].id === id) {
                toremove = i;
            }
        }
        uploader.files.splice(toremove, 1);
    });

});



//获取日期
function get_date(){
    var d = new Date();
    var str = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate();
    return str;
}

//查看数组是否有空值
function empty_check(array){
    for(var i = 0 ;i<array.length;i++)
    {
        if(array[i] == "" || typeof(array[i]) == "undefined")
        {
            return true;
        }

    }
    return false;
}