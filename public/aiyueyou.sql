/*
爱约游会员基础信息表
*/
CREATE TABLE dp_user(
  `id` INT (11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '主键',
  `sys_id` INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '约游id',
  `group_id` INT (11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员组id',
  `member_deadline` INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员到期时间',
  `city_id` VARCHAR (30) NOT NULL DEFAULT '' COMMENT '城市地址id字符串',
  `phone` VARCHAR (50) UNIQUE NOT NULL COMMENT '用户绑定手机号,或者自动绑定为第三方唯一标识,首次登录进行绑定',
  `user_type` TINYINT(1) UNSIGNED NOT NULL DEFAULT 3 COMMENT '用户类型,1=>推荐，2=>认证,3=>新人',
  `nickname` VARCHAR (255)  NOT NULL DEFAULT '' COMMENT '用户昵称,urlencode编码识别表情符号',
  `head_img` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '用户头像',
  `autograph` VARCHAR (100) NOT NULL DEFAULT '' COMMENT '个性签名',
  `real_name` VARCHAR (20)  NOT NULL DEFAULT '' COMMENT '姓名',
  `sex`  TINYINT(1) UNSIGNED NOT NULL DEFAULT 2 COMMENT '性别,1=>男,2=>女',
  `occupation_id` VARCHAR (50) NOT NULL DEFAULT ''  COMMENT '职业表id' ,
  `birthday` CHAR(10) NOT NULL DEFAULT '' COMMENT '生日',
  `qq` VARCHAR (15) NOT NULL DEFAULT '' COMMENT 'qq号码',
  `address` VARCHAR (50) NOT NULL DEFAULT '' COMMENT '常住地址',
  `height` CHAR(3) NOT NULL DEFAULT '' COMMENT '身高，单位cm',
  `interest` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '爱好',
  `measurement` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '三围',
  `weight` VARCHAR(3) NOT NULL DEFAULT '' COMMENT '体重',
  `account` DECIMAL (8,2) DEFAULT 0.00 COMMENT '用户余额',
  `point` SMALLINT(6) UNSIGNED NOT NULL  DEFAULT 0 COMMENT '积分点数',
  `is_vip` TINYINT(1) UNSIGNED NOT NULL DEFAULT 4 COMMENT '是否为vip用户,4=>非vip用户,1=>vip用户',
  `is_escort` TINYINT(1) UNSIGNED NOT NULL DEFAULT 4 COMMENT '是否为伴游,4=>非伴游用户,1=>伴游用户',
  `is_bind_phone` TINYINT (1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户是否绑定手机号,0=>未绑定,1=>绑定',
  `login_time` CHAR(10) NOT NULL DEFAULT '' COMMENT '最后一次登陆时间',
  `login_ip` VARCHAR (30) NOT NULL DEFAULT '' COMMENT '最后一次登录用户电脑ip地址',
  `login_addr_x` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '最后一次登录经纬度x坐标',
  `login_addr_y` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '最后一次登录经纬度y坐标'
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;
/*
用户授权认证表
*/
 CREATE TABLE dp_user_auth(
  `id` INT (11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '主键',
  `uid` INT (11) UNSIGNED NOT NULL COMMENT '关联用户表主键',
  `identity_type` VARCHAR (20) NOT NULL COMMENT '登录类型（手机号 邮箱 用户名）或第三方应用名称（微信 微博等）',
  `identifier`  VARCHAR (50)   NOT NULL  UNIQUE COMMENT '标识（手机号 邮箱 用户名或第三方应用的唯一标识）',
  `credential`  VARCHAR (512) NOT NULL COMMENT '密码凭证（站内的保存密码，站外的不保存或保存token)',
  `status` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '用户状态,4=>用户被锁定,1=>正常用户',
  `create_time` CHAR(10)  NOT NULL COMMENT '注册时间',
  `update_time` CHAR(10) NOT NULL DEFAULT '' COMMENT '修改时间',
  `regip` VARCHAR(20) NOT NULL COMMENT '注册ip地址'
 );


/*
会员组设计用v9系统的数据库改造
*/


/*
会员媒体表
*/

CREATE TABLE dp_user_video(
  `id` INT (11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '主键',
  `uid` INT (11) UNSIGNED NOT NULL COMMENT '关联用户表主键',
  `video_url` VARCHAR (50) NOT NULL COMMENT '文件储存地址',
  `upload_ip` VARCHAR (50) NOT NULL COMMENT '上传ip地址',
  `video_type` TINYINT(1) NOT NULL COMMENT '文件类型,1=>图片，2=>视频',
  `create_time` CHAR(10) NOT NULL COMMENT '上传时间'
);


/*
用户关注表
*/

CREATE TABLE dp_user_attention(
  `id` INT (11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '主键',
  `user_followid` INT(11) UNSIGNED NOT NULL COMMENT '发起关注的用户id,即粉丝',
  `user_id` INT(11) UNSIGNED NOT NULL COMMENT '被关注的用户id',
  `create_time` CHAR(10) NOT NULL COMMENT '关注时间'
);

/*
用户黑名单表
*/
CREATE TABLE dp_user_blacklist(
  `id` INT (11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '主键',
  `user_id` INT(11) UNSIGNED NOT NULL COMMENT '发起拉黑的用户id',
  `black_user_id` INT(11) UNSIGNED NOT NULL COMMENT '被拉黑的用户id',
  `create_time` CHAR(10) NOT NULL COMMENT '拉黑时间'
);


/*
用户充值记录表
*/

CREATE TABLE dp_recharge(
  `id` INT (11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '主键',
  `uid` INT(11)  UNSIGNED NOT NULL COMMENT '用户id',
  `recharge_money` DECIMAL (8,2) UNSIGNED DEFAULT 0.00  COMMENT '充值金额',
  `recharge_type` VARCHAR(20)  NOT NULL COMMENT '充值类型',
  `status` TINYINT(1) UNSIGNED NOT NULL COMMENT '充值状态1=>成功,4=>失败',
  `create_time` CHAR (10)  NOT NULL COMMENT '充值时间',
  `create_ip` VARCHAR(20) NOT NULL COMMENT '充值ip地址'
);



/*
会员组信息表
*/
CREATE TABLE IF NOT EXISTS `dp_user_group` (
  `id` INT (11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '主键',
  `group_name` char(15) NOT NULL COMMENT '会员组名称',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为系统组',
  `price_y` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00'  COMMENT '会员价格月费',      /*月费价格*/
  `price_m` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00'  COMMENT '会员价格半年费',      /*半年费价格*/
  `price_a` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00'  COMMENT '会员价格年费',      /*年费会员*/
  `prestore` DECIMAL (8,2) UNSIGNED NOT NULL DEFAULT  '0.00' COMMENT '会员需要预存金额',  /*会员需预存金额*/
  `gift_money` DECIMAL (8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '赠送金额',
  `member_type` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '会员组类型,1=>线上会员,2=>线下会员',
  `icon` char(30) NOT NULL COMMENT '会员图标',
  `usernamecolor` char(7) NOT NULL COMMENT '会员名字颜色',
  `description` char(100) NOT NULL COMMENT '相关描述',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' ,
  `create_time` INT(10) UNSIGNED NOT NULL COMMENT '添加时间',
  `update_time` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用'
);

/*
会员组特权表
*/
CREATE TABLE IF NOT EXISTS `dp_user_group_privilege`(
   `id` INT (11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '主键',
   `group_id` INT (11) UNSIGNED NOT NULL COMMENT'会员组id',
   `allow_priview_list` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否允许进入伴游详情,1=>允许,4=>禁止',
   `allow_priview_photo` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否允许查看照片,1=>允许,4=>禁止',
   `allow_priview_video` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否允许查看视频,1=>允许,4=>禁止',
   `allow_chat` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否允许聊天,1=>允许,4=>禁止',
   `allow_insurance` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否享受保险,1=>是,4=>不是',
   `allow_recommend` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否享受客服推荐,1=>是,4=>不是',
   `allow_videoconferencing` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否允许真人视频,1=>允许,4=>禁止',
   `allow_escort_recommend` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否享受高级伴游推荐，1=>是,4=>不是',
   `allow_date` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否允许真人见面,1=>允许,4=>禁止',
   `create_time` INT(10) NOT NULL COMMENT '创建特权时间',
   `update_time` INT(10) NOT NULL COMMENT '修改时间'
);


/*
职业表
*/
CREATE TABLE  IF NOT EXISTS  `dp_profession`(
    `id` INT (11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '主键',
    `profession_name` VARCHAR (30) NOT NULL COMMENT '职业名称',
    `create_time` CHAR(10) NOT NULL COMMENT '添加时间',
    `update_time` CHAR(10) NOT NULL COMMENT '修改时间'
);

/*
城市地址表
*/
CREATE  TABLE IF NOT EXISTS `dp_city_address`(
    `id` INT (11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '主键',
    `citye_name` VARCHAR (30) NOT NULL COMMENT '地址名称',
    `create_time` CHAR(10)  NOT NULL COMMENT '添加时间',
    `update_time` CHAR(10) NOT NULL COMMENT '修改时间'
);


/*
会员发布信息表
*/

CREATE TABLE IF NOT EXISTS `dp_user_release`(
    `id` INT (11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '主键',
    `uid` INT(11)  UNSIGNED NOT NULL COMMENT '用户id',
    `release_object` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT'1=>男,2=>女,0=>不限',
    `travel_start_time` INT(10) UNSIGNED NOT NULL COMMENT '出行时间',
    `travel_total_time` INT(10) UNSIGNED NOT NULL COMMENT '出行天数',
    `travel_tool` VARCHAR(50) NOT NULL COMMENT '出行方式',
    `is_sincerity` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否交纳诚意金',
    `sincerity_money` DECIMAL (8,2) UNSINGNED NOT NULL DEFAULT 0.00 COMMENT '诚意金数额',
    `create_time` INT(10) UNSIGNED NOT NULL COMMENT '发布时间'
);


/*
会员认证表
*/
DROP TABLE IF EXISTS `dp_user_identity`;
CREATE TABLE `dp_user_ identity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL COMMENT '用户id',
  `id_card_num` varchar(50) NOT NULL COMMENT '省份证号码',
  `sfz_font_img` varchar(100) NOT NULL COMMENT '身份证正面照',
  `sfz_back_img` varchar(100) NOT NULL COMMENT '身份证背面照',
  `sfz_hand_img` varchar(100) NOT NULL COMMENT '手持身份证正面照',
  `create_time` int(11) UNSIGNED NOT NULL COMMENT '添加时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status`  TINYINT(1) UNSIGNED DEFAULT 3 NOT NULL COMMENT '审核状态,1=>审核成功,2=>审核失败,3=>审核中',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;