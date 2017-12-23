/*
爱约游会员基础信息表
*/
CREATE TABLE dp_user(
  `id` INT (11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '主键',
  `sys_id` INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '约游id',
  `groupid` INT (11) UNSIGNED NOT NULL COMMENT '会员组id',
  `phone` CHAR(11) UNIQUE NOT NULL COMMENT '用户绑定手机号,首次进行登录进行绑定',
  `user_type` TINYINT(1) NOT NULL DEFAULT 3 COMMENT '用户类型,1=>推荐，2=>认证,3=>新人',
  `nickname` VARCHAR (50)  NOT NULL COMMENT '用户昵称',
  `head_img` VARCHAR(100) NOT NULL COMMENT '用户头像',
  `member_id` VARCHAR(10) NOT NULL COMMENT '约游id',
  `autograph` VARCHAR (100) NOT NULL COMMENT '个性签名',
  `real_name` VARCHAR (20)  NOT NULL COMMENT '姓名',
  `sex`  TINYINT(1) UNSIGNED NOT NULL COMMENT '性别,1=>男,2=>女',
  `occupation_id` INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '职业表id' ,
  `birthday` CHAR(10) NOT NULL COMMENT '生日',
  `qq` VARCHAR (15) NOT NULL COMMENT 'qq号码',
  `address` VARCHAR (50) NOT NULL COMMENT '常住地址',
  `height` CHAR(3) NOT NULL COMMENT '身高，单位cm',
  `interest` VARCHAR(50) NOT NULL COMMENT '爱好',
  `measurement` VARCHAR(50) NOT NULL COMMENT '三围',
  `weight` VARCHAR(3) NOT NULL COMMENT '体重',
  `account` DECIMAL (8,2) DEFAULT 0.00 COMMENT '用户余额',
  `is_vip` TINYINT(1) UNSIGNED NOT NULL DEFAULT 4 COMMENT '是否为vip用户,4=>非vip用户,1=>vip用户',
  `login_time` CHAR(10) NOT NULL COMMENT '最后一次登陆时间',
  `login_ip` VARCHAR (30) NOT NULL COMMENT '最后一次登录用户电脑ip地址',
  `login_addr_x` VARCHAR(50) NOT NULL COMMENT '最后一次登录经纬度x坐标',
  `login_addr_y` VARCHAR(50) NOT NULL COMMENT '最后一次登录经纬度y坐标'
);
/*
用户授权认证表
*/
 CREATE TABLE dp_user_auth(
  `id` INT (11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '主键',
  `uid` INT (11) UNSIGNED NOT NULL COMMENT '关联用户表主键',
  `identity_type` VARCHAR (20) NOT NULL COMMENT '登录类型（手机号 邮箱 用户名）或第三方应用名称（微信 微博等）',
  `identifier`  VARCHAR (50)   NOT NULL  UNIQUE COMMENT '标识（手机号 邮箱 用户名或第三方应用的唯一标识）',
  `credential`  VARCHAR (60) NOT NULL COMMENT '密码凭证（站内的保存密码，站外的不保存或保存token)',
  `status` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '用户状态,4=>用户被锁定,1=>正常用户',
  `create_time` CHAR(10)  NOT NULL COMMENT '注册时间',
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
会员组表
*/
CREATE TABLE IF NOT EXISTS `dp_user_group` (
  `groupid` tinyint(3) unsigned NOT NULL,
  `name` char(15) NOT NULL,
  `uid` INT (11) UNSIGNED NOT NULL COMMENT '会员id',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `starnum` tinyint(2) unsigned NOT NULL,
  `point` smallint(6) unsigned NOT NULL,
  `price_y` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',      /*年费价格*/
  `price_m` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',      /*包月价格*/
  `price_a` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',      /*终身会员*/
  `icon` char(30) NOT NULL,
  `usernamecolor` char(7) NOT NULL,
  `description` char(100) NOT NULL,
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0'
);

/*
会员组特权表
*/
CREATE TABLE IF NOT EXISTS `dp_user_group_privilege`(
   `id` INT (11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '主键',
   `group_id` INT (11) UNSIGNED NOT NULL COMMENT'会员组id',
   `allow_privilege` VARCHAR (100) NOT NULL COMMENT '特权描述',
   `status` TINYINT (1) NOT NULL DEFAULT 1 COMMENT '特权是否启用,1=>启用,2=>禁用',
   `create_time` CHAR (10) NOT NULL COMMENT '创建特权时间'
);

