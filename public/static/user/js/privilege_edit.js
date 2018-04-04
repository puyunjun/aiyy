var img_p = document.getElementById('file_list_icon');

$(img_p).find('a').attr('href',$('#icon').val());
$(img_p).find('a').find('img').attr('src',$('#icon').val());