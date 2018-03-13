/*
Navicat MySQL Data Transfer

Source Server         : test
Source Server Version : 50638
Source Host           : 172.18.144.138:3306
Source Database       : dolphin

Target Server Type    : MYSQL
Target Server Version : 50638
File Encoding         : 65001

Date: 2018-01-16 19:40:17
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for dp_admin_access
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_access`;
CREATE TABLE `dp_admin_access` (
  `module` varchar(16) NOT NULL DEFAULT '' COMMENT '模型名称',
  `group` varchar(16) NOT NULL DEFAULT '' COMMENT '权限分组标识',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `nid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '授权节点id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='统一授权表';

-- ----------------------------
-- Table structure for dp_admin_action
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_action`;
CREATE TABLE `dp_admin_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(16) NOT NULL DEFAULT '' COMMENT '所属模块名',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '行为唯一标识',
  `title` varchar(80) NOT NULL DEFAULT '' COMMENT '行为标题',
  `remark` varchar(128) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text NOT NULL COMMENT '行为规则',
  `log` text NOT NULL COMMENT '日志规则',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COMMENT='系统行为表';

-- ----------------------------
-- Table structure for dp_admin_attachment
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_attachment`;
CREATE TABLE `dp_admin_attachment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件名',
  `module` varchar(32) NOT NULL DEFAULT '' COMMENT '模块名，由哪个模块上传的',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '文件链接',
  `mime` varchar(128) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `ext` char(8) NOT NULL DEFAULT '' COMMENT '文件类型',
  `size` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT 'sha1 散列值',
  `driver` varchar(16) NOT NULL DEFAULT 'local' COMMENT '上传驱动',
  `download` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表';

-- ----------------------------
-- Table structure for dp_admin_config
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_config`;
CREATE TABLE `dp_admin_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '标题',
  `group` varchar(32) NOT NULL DEFAULT '' COMMENT '配置分组',
  `type` varchar(32) NOT NULL DEFAULT '' COMMENT '类型',
  `value` text NOT NULL COMMENT '配置值',
  `options` text NOT NULL COMMENT '配置项',
  `tips` varchar(256) NOT NULL DEFAULT '' COMMENT '配置提示',
  `ajax_url` varchar(256) NOT NULL DEFAULT '' COMMENT '联动下拉框ajax地址',
  `next_items` varchar(256) NOT NULL DEFAULT '' COMMENT '联动下拉框的下级下拉框名，多个以逗号隔开',
  `param` varchar(32) NOT NULL DEFAULT '' COMMENT '联动下拉框请求参数名',
  `format` varchar(32) NOT NULL DEFAULT '' COMMENT '格式，用于格式文本',
  `table` varchar(32) NOT NULL DEFAULT '' COMMENT '表名，只用于快速联动类型',
  `level` tinyint(2) unsigned NOT NULL DEFAULT '2' COMMENT '联动级别，只用于快速联动类型',
  `key` varchar(32) NOT NULL DEFAULT '' COMMENT '键字段，只用于快速联动类型',
  `option` varchar(32) NOT NULL DEFAULT '' COMMENT '值字段，只用于快速联动类型',
  `pid` varchar(32) NOT NULL DEFAULT '' COMMENT '父级id字段，只用于快速联动类型',
  `ak` varchar(32) NOT NULL DEFAULT '' COMMENT '百度地图appkey',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态：0禁用，1启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='系统配置表';

-- ----------------------------
-- Table structure for dp_admin_hook
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_hook`;
CREATE TABLE `dp_admin_hook` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `plugin` varchar(32) NOT NULL DEFAULT '' COMMENT '钩子来自哪个插件',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子描述',
  `system` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否为系统钩子',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='钩子表';

-- ----------------------------
-- Table structure for dp_admin_hook_plugin
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_hook_plugin`;
CREATE TABLE `dp_admin_hook_plugin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hook` varchar(32) NOT NULL DEFAULT '' COMMENT '钩子id',
  `plugin` varchar(32) NOT NULL DEFAULT '' COMMENT '插件标识',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='钩子-插件对应表';

-- ----------------------------
-- Table structure for dp_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_log`;
CREATE TABLE `dp_admin_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `action_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '行为id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '触发行为的数据id',
  `remark` longtext NOT NULL COMMENT '日志备注',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '执行行为的时间',
  PRIMARY KEY (`id`),
  KEY `action_ip_ix` (`action_ip`),
  KEY `action_id_ix` (`action_id`),
  KEY `user_id_ix` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

-- ----------------------------
-- Table structure for dp_admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_menu`;
CREATE TABLE `dp_admin_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级菜单id',
  `module` varchar(16) NOT NULL DEFAULT '' COMMENT '模块名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '菜单标题',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '菜单图标',
  `url_type` varchar(16) NOT NULL DEFAULT '' COMMENT '链接类型（link：外链，module：模块）',
  `url_value` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `url_target` varchar(16) NOT NULL DEFAULT '_self' COMMENT '链接打开方式：_blank,_self',
  `online_hide` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '网站上线后是否隐藏',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `system_menu` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否为系统菜单，系统菜单不可删除',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `params` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=220 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

-- ----------------------------
-- Table structure for dp_admin_module
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_module`;
CREATE TABLE `dp_admin_module` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '模块名称（标识）',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '模块标题',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '图标',
  `description` text NOT NULL COMMENT '描述',
  `author` varchar(32) NOT NULL DEFAULT '' COMMENT '作者',
  `author_url` varchar(255) NOT NULL DEFAULT '' COMMENT '作者主页',
  `config` text COMMENT '配置信息',
  `access` text COMMENT '授权配置',
  `version` varchar(16) NOT NULL DEFAULT '' COMMENT '版本号',
  `identifier` varchar(64) NOT NULL DEFAULT '' COMMENT '模块唯一标识符',
  `system_module` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否为系统模块',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='模块表';

-- ----------------------------
-- Table structure for dp_admin_packet
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_packet`;
CREATE TABLE `dp_admin_packet` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '数据包名',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '数据包标题',
  `author` varchar(32) NOT NULL DEFAULT '' COMMENT '作者',
  `author_url` varchar(255) NOT NULL DEFAULT '' COMMENT '作者url',
  `version` varchar(16) NOT NULL,
  `tables` text NOT NULL COMMENT '数据表名',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='数据包表';

-- ----------------------------
-- Table structure for dp_admin_plugin
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_plugin`;
CREATE TABLE `dp_admin_plugin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '插件名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '插件标题',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '图标',
  `description` text NOT NULL COMMENT '插件描述',
  `author` varchar(32) NOT NULL DEFAULT '' COMMENT '作者',
  `author_url` varchar(255) NOT NULL DEFAULT '' COMMENT '作者主页',
  `config` text NOT NULL COMMENT '配置信息',
  `version` varchar(16) NOT NULL DEFAULT '' COMMENT '版本号',
  `identifier` varchar(64) NOT NULL DEFAULT '' COMMENT '插件唯一标识符',
  `admin` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台管理',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Table structure for dp_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_role`;
CREATE TABLE `dp_admin_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级角色',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '角色名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '角色描述',
  `menu_auth` text NOT NULL COMMENT '菜单权限',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `access` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否可登录后台',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Table structure for dp_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_user`;
CREATE TABLE `dp_admin_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(96) NOT NULL DEFAULT '' COMMENT '密码',
  `email` varchar(64) NOT NULL DEFAULT '' COMMENT '邮箱地址',
  `email_bind` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否绑定邮箱地址',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `mobile_bind` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否绑定手机号码',
  `avatar` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '头像',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '余额',
  `score` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `role` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `group` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '部门id',
  `signup_ip` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '注册ip',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后一次登录时间',
  `last_login_ip` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '登录ip',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态：0禁用，1启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Table structure for dp_city_address
-- ----------------------------
DROP TABLE IF EXISTS `dp_city_address`;
CREATE TABLE `dp_city_address` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `citye_name` varchar(30) NOT NULL COMMENT '地址名称',
  `create_time` char(10) NOT NULL COMMENT '添加时间',
  `update_time` char(10) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dp_profession
-- ----------------------------
DROP TABLE IF EXISTS `dp_profession`;
CREATE TABLE `dp_profession` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `profession_name` varchar(30) NOT NULL COMMENT '职业名称',
  `create_time` char(10) NOT NULL COMMENT '添加时间',
  `update_time` char(10) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dp_user
-- ----------------------------
DROP TABLE IF EXISTS `dp_user`;
CREATE TABLE `dp_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `sys_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '约游id',
  `group_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员组id',
  `member_deadline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员到期时间',
  `city_id` varchar(30) NOT NULL DEFAULT '' COMMENT '城市地址id字符串',
  `phone` varchar(50) NOT NULL COMMENT '用户绑定手机号,或者自动绑定为第三方唯一标识,注册进行绑定',
  `user_type` tinyint(1) unsigned NOT NULL DEFAULT '3' COMMENT '用户类型,1=>推荐，2=>认证,3=>新人',
  `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `head_img` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
  `autograph` varchar(100) NOT NULL DEFAULT '' COMMENT '个性签名',
  `real_name` varchar(20) NOT NULL DEFAULT '' COMMENT '姓名',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '性别,1=>男,2=>女',
  `occupation_id` varchar(50) NOT NULL DEFAULT '' COMMENT '职业表id',
  `birthday` char(10) NOT NULL DEFAULT '' COMMENT '生日',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT 'qq号码',
  `address` varchar(50) NOT NULL DEFAULT '' COMMENT '常住地址',
  `height` char(3) NOT NULL DEFAULT '' COMMENT '身高，单位cm',
  `interest` varchar(50) NOT NULL DEFAULT '' COMMENT '爱好',
  `measurement` varchar(50) NOT NULL DEFAULT '' COMMENT '三围',
  `weight` varchar(3) NOT NULL DEFAULT '' COMMENT '体重',
  `account` decimal(8,2) DEFAULT '0.00' COMMENT '用户余额',
  `point` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '积分点数',
  `is_vip` tinyint(1) unsigned NOT NULL DEFAULT '4' COMMENT '是否为vip用户,4=>非vip用户,1=>vip用户',
  `is_escort` tinyint(1) unsigned NOT NULL DEFAULT '4' COMMENT '是否为伴游,4=>非伴游用户,1=>伴游用户',
  `is_bind_phone` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '用户是否绑定手机号',
  `login_time` char(10) NOT NULL DEFAULT '' COMMENT '最后一次登陆时间',
  `login_ip` varchar(30) NOT NULL DEFAULT '' COMMENT '最后一次登录用户电脑ip地址',
  `login_addr_x` varchar(50) NOT NULL DEFAULT '' COMMENT '最后一次登录经纬度x坐标',
  `login_addr_y` varchar(50) NOT NULL DEFAULT '' COMMENT '最后一次登录经纬度y坐标',
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dp_user_attention
-- ----------------------------
DROP TABLE IF EXISTS `dp_user_attention`;
CREATE TABLE `dp_user_attention` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_followid` int(11) unsigned NOT NULL COMMENT '发起关注的用户id,即粉丝',
  `user_id` int(11) unsigned NOT NULL COMMENT '被关注的用户id',
  `create_time` char(10) NOT NULL COMMENT '关注时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dp_user_auth
-- ----------------------------
DROP TABLE IF EXISTS `dp_user_auth`;
CREATE TABLE `dp_user_auth` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` int(11) unsigned NOT NULL COMMENT '关联用户表主键',
  `identity_type` varchar(20) NOT NULL COMMENT '登录类型（手机号 邮箱 用户名）或第三方应用名称（微信 微博等）',
  `identifier` varchar(50) NOT NULL COMMENT '标识（手机号 邮箱 用户名或第三方应用的唯一标识）',
  `credential` varchar(512) NOT NULL COMMENT '密码凭证（站内的保存密码，站外的不保存或保存token)',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态,4=>用户被锁定,1=>正常用户',
  `create_time` char(10) NOT NULL COMMENT '注册时间',
  `update_time` char(10) NOT NULL DEFAULT '' COMMENT '修改时间',
  `regip` varchar(20) NOT NULL COMMENT '注册ip地址',
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier` (`identifier`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dp_user_blacklist
-- ----------------------------
DROP TABLE IF EXISTS `dp_user_blacklist`;
CREATE TABLE `dp_user_blacklist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(11) unsigned NOT NULL COMMENT '发起拉黑的用户id',
  `black_user_id` int(11) unsigned NOT NULL COMMENT '被拉黑的用户id',
  `create_time` char(10) NOT NULL COMMENT '拉黑时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dp_user_group
-- ----------------------------
DROP TABLE IF EXISTS `dp_user_group`;
CREATE TABLE `dp_user_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `group_name` char(15) NOT NULL COMMENT '会员组名称',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为系统组',
  `price_y` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '会员价格月费',
  `price_m` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '会员价格半年费',
  `price_a` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '会员价格年费',
  `prestore` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '会员需预存金额',
  `gift_money` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '赠送金额',
  `icon` char(30) NOT NULL COMMENT '会员图标',
  `usernamecolor` char(7) NOT NULL COMMENT '会员名字颜色',
  `description` char(100) NOT NULL COMMENT '相关描述',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dp_user_group_privilege
-- ----------------------------
DROP TABLE IF EXISTS `dp_user_group_privilege`;
CREATE TABLE `dp_user_group_privilege` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `group_id` int(11) unsigned NOT NULL COMMENT '会员组id',
  `allow_priview_photo` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否允许查看照片,1=>允许,4=>禁止',
  `allow_priview_video` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否允许查看视频,1=>允许,4=>禁止',
  `allow_chat` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否允许聊天,1=>允许,4=>禁止',
  `allow_insurance` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否享受保险,1=>是,4=>不是',
  `allow_recommend` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否享受客服推荐,1=>是,4=>不是',
  `allow_videoconferencing` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否允许真人视频,1=>允许,4=>禁止',
  `allow_escort_recommend` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否享受高级伴游推荐，1=>是,4=>不是',
  `allow_date` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否允许真人见面,1=>允许,4=>禁止',
  `create_time` int(10) NOT NULL COMMENT '创建特权时间',
  `update_time` int(10) NOT NULL COMMENT '修改时间',
  `allow_priview_list` tinyint(1) unsigned NOT NULL DEFAULT '4',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dp_user_video
-- ----------------------------
DROP TABLE IF EXISTS `dp_user_video`;
CREATE TABLE `dp_user_video` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` int(11) unsigned NOT NULL COMMENT '关联用户表主键',
  `video_url` varchar(50) NOT NULL COMMENT '文件储存地址',
  `video_type` tinyint(1) NOT NULL COMMENT '文件类型,1=>图片，2=>视频',
  `create_time` char(10) NOT NULL COMMENT '上传时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
