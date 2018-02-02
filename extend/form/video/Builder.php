<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\2\1 0001
 * Time: 16:42
 */

namespace form\video;


class Builder
{
    /**
     * 取色器
     * @param string $name 表单项名
     * @param string $title 标题
     * @param string $tips 提示
     * @param string $default 默认值
     * @param string $mode 模式：默认为rgba(含透明度)，也可以是rgb
     * @param string $extra_attr 额外属性
     * @param string $extra_class 额外css类名
     * @return mixed
     */
    public function item($name = '', $title = '', $tips = '', $default = '', $mode = 'rgba', $extra_attr = '', $extra_class = '')
    {
        return [
            'name'        => $name,
            'title'       => $title,
            'tips'        => $tips,
            'value'       => $default,
            'mode'        => $mode,
            'extra_class' => $extra_class,
            'extra_attr'  => $extra_attr,
        ];
    }

    /**
     * @var array 需要加载的js
     */

    public $js = [
        '__STATIC__/ali-oss-master/dist/aliyun-oss-sdk.min.js',
        'video.js',
    ];

}