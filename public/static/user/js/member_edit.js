var img_p = document.getElementById('file_list_head_img');

$(img_p).find('a').attr('href',$('#head_img').val());
$(img_p).find('a').find('img').attr('src',$('#head_img').val());