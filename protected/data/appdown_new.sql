CREATE TABLE `joy_offers` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `advertiser_id` int(11) NOT NULL DEFAULT 0 COMMENT '广告主id',
 `name` varchar(255) NOT NULL DEFAULT '' COMMENT 'offer名称',
 `description` text COMMENT '详细说明',
 `preview_url` varchar(200) DEFAULT NULL COMMENT '示例url',
 `offer_url` varchar(200) NOT NULL DEFAULT '' COMMENT 'offer链接url',
 `protocol` varchar(50) NOT NULL DEFAULT 'http' COMMENT '协议',
 `expiration_date` date DEFAULT NULL COMMENT '过期时间',
 `offer_category` int(11) NOT NULL DEFAULT 0 COMMENT 'offer分类',
 `ref_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Reference ID',
 `currency` varchar(20) DEFAULT 'USD' COMMENT '货币，默认USD美元',
 `revenue_type` varchar(20) DEFAULT NULL COMMENT '收入类型',
 `revenue` float(11,2) NOT NULL COMMENT '收入',
 `payout_type` varchar(20) DEFAULT NULL COMMENT '收入类型',
 `payout` float(11,2) NOT NULL COMMENT '支出',
 `is_private` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否私有(0:否 1:是)',
 `require_approval` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否需审核(0:否 1:是)',
 `require_terms_and_conditions` tinyint(1) NOT NULL DEFAULT 0 COMMENT '需明确附加条款(0:否 1:是)',
 `is_seo_friendly_301` tinyint(1) NOT NULL DEFAULT 0 COMMENT '搜索引擎友好(0:否 1:是)',
 `email_instructions` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否需邮件(0:否 1:是)',
 `show_mail_list` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否可以退订邮件(0:否 1:是)',
 `redirect_offer_id` varchar(255) DEFAULT NULL COMMENT '过期或者超标跳转offerid',
 `session_hours` int(11) NOT NULL DEFAULT 0  COMMENT '点击追踪时效',
 `session_impression_hours` int(11) NOT NULL DEFAULT 0  COMMENT'追踪时效',
 `enable_offer_whitelist` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否添加IP白名单(0:否 1:是)',
 `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态(0:待审核 1:激活 2:删除)',
 `note` text COMMENT '备注',
 `createtime` datetime DEFAULT NULL COMMENT '创建时间',
 PRIMARY KEY (`id`),
 KEY (`advertiser_id`),
 KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `joy_offers_caps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `offer_id` int(11) NOT NULL COMMENT '对应的offerId唯一',
  `daily_con` int(11) DEFAULT NULL COMMENT '每天的最大转化量',
  `month_con` int(11) DEFAULT NULL COMMENT '每月的最大转化量',
  `daily_pay` float(6,2) DEFAULT NULL COMMENT '每天的最大支付额',
  `month_pay` float(6,2) DEFAULT NULL COMMENT '每月的最大支付额',
  `daily_rev` float(6,2) DEFAULT NULL COMMENT '每天的最大收入',
  `month_rev` float(6,2) DEFAULT NULL COMMENT '每月最大的收入',
  PRIMARY KEY (`id`,`offer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `joy_offer_pixels` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `offerid` int(11) NOT NULL DEFAULT 0,
 `advid` int(11) NOT NULL DEFAULT 0 COMMENT '广告主id',
 `affid` int(11) NOT NULL DEFAULT 0 COMMENT '下游id',
 `type` varchar(20) NOT NULL DEFAULT 'url' COMMENT '回调类型',
 `code` varchar(200) DEFAULT NULL COMMENT 'IP信息',
 `createtime` datetime DEFAULT NULL COMMENT '创建时间',
 PRIMARY KEY (`id`),
 KEY (`offerid`),
 KEY (`advid`),
 KEY (`affid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `joy_transaction` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `offerid` int(11) NOT NULL DEFAULT 0,
 `advid` int(11) NOT NULL DEFAULT 0 COMMENT '广告主id',
 `affid` int(11) NOT NULL DEFAULT 0 COMMENT '下游id',
 `transactionid` varchar(100) NOT NULL DEFAULT '' COMMENT '点击ID',
 `ip` varchar(50) DEFAULT NULL COMMENT 'IP信息',
 `createtime` datetime DEFAULT NULL COMMENT '创建时间',
 `createtime2` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '伦敦时间',
 PRIMARY KEY (`id`),
 KEY (`offerid`),
 KEY (`advid`),
 KEY (`affid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `joy_transaction_income` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `offerid` int(11) NOT NULL DEFAULT 0,
 `advid` int(11) NOT NULL DEFAULT 0 COMMENT '广告主id',
 `affid` int(11) NOT NULL DEFAULT 0 COMMENT '下游id',
 `transactionid` varchar(100) NOT NULL DEFAULT '' COMMENT '点击ID',
 `revenue` float(6,2) DEFAULT 0 COMMENT '收入',
 `payout` float(6,2) DEFAULT 0 COMMENT '支出',
 `serverip` varchar(50) DEFAULT NULL COMMENT '上游回传IP',
 `clientip` varchar(50) DEFAULT NULL COMMENT '上游回传的用户IP',
 `transactiontime` datetime DEFAULT NULL COMMENT '点击时间',
 `transactiontime2` datetime DEFAULT NULL COMMENT '点击伦敦时间',
 `cut_num` int(11) NOT NULL DEFAULT 100 COMMENT '扣量',
 `ispostbacked` tinyint(11) NOT NULL DEFAULT 1 COMMENT '是否已回传，1已回、0未',
 `createtime` datetime DEFAULT NULL COMMENT '创建时间',
 `createtime2` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '伦敦时间',
 PRIMARY KEY (`id`),
 KEY (`offerid`),
 KEY (`advid`),
 KEY (`affid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `joy_system_group`
--

CREATE TABLE IF NOT EXISTS `joy_system_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '组名',
  `status` int(11) DEFAULT '1' COMMENT '状态：1可用，0禁用',
  `addtime` datetime DEFAULT NULL COMMENT '创建时间',
  `lastmodify` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户组' AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `joy_system_group`
--

INSERT INTO `joy_system_group` (`id`, `name`, `status`, `addtime`, `lastmodify`) VALUES
(1, 'super manager', 1, '2015-07-15 00:00:00', NULL),
(2, 'manager', 1, '2015-07-15 00:00:00', NULL),
(3, 'business', 1, '2015-07-15 00:00:00', NULL),
(4, 'advertiser', 1, '2015-07-15 00:00:00', NULL),
(5, 'affiliate', 1, '2015-07-15 00:00:00', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `joy_system_grouppower`
--

CREATE TABLE IF NOT EXISTS `joy_system_grouppower` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` int(11) NOT NULL COMMENT '组ID',
  `weight` int(11) DEFAULT '0' COMMENT '权值，用于显示排序',
  `pname` varchar(100) DEFAULT '' COMMENT '权限组名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='组权限' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `joy_system_grouppower`
--

INSERT INTO `joy_system_grouppower` (`id`, `groupid`, `weight`, `pname`) VALUES
(1, 1, 1000, '系统管理'),
(2, 1, 9999, '广告主管理'),
(3, 1, 9998, '渠道商管理'),
(4, 1, 9997, 'Offer管理');

-- --------------------------------------------------------

--
-- 表的结构 `joy_system_log`
--

CREATE TABLE IF NOT EXISTS `joy_system_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水id',
  `userid` bigint(12) NOT NULL COMMENT '用户ID',
  `email` varchar(32) DEFAULT '' COMMENT '用户邮箱',
  `itype` int(11) DEFAULT '10000' COMMENT '操作类型:整数',
  `stype` varchar(50) DEFAULT '' COMMENT '操作类型:字符串',
  `remark` varchar(200) DEFAULT '' COMMENT '操作描述',
  `amount` decimal(10,3) DEFAULT '0.000' COMMENT '操作描述',
  `ip` varchar(20) DEFAULT '' COMMENT '操作IP',
  `dtime` datetime NOT NULL COMMENT '记录时间',
  PRIMARY KEY (`id`),
  KEY `userid_itype` (`userid`,`itype`),
  KEY `userid_stype` (`userid`,`stype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `joy_system_power`
--

CREATE TABLE IF NOT EXISTS `joy_system_power` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pname` varchar(20) NOT NULL COMMENT '限权中文名',
  `action` varchar(50) NOT NULL COMMENT '权限[英文的操作名,control/action]',
  `status` tinyint(1) DEFAULT '1',
  `parentid` int(11) DEFAULT '0' COMMENT '父级权限ID',
  `weight` int(4) NOT NULL DEFAULT '0' COMMENT '显示顺序：数字越大越靠前',
  `addtime` datetime DEFAULT NULL COMMENT '创建时间',
  `lastmodify` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `action` (`parentid`,`action`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='权限表' AUTO_INCREMENT=28 ;

--
-- 转存表中的数据 `joy_system_power`
--

INSERT INTO `joy_system_power` (`id`, `pname`, `action`, `status`, `parentid`, `weight`, `addtime`, `lastmodify`) VALUES
(1, '用户管理', 'system/userlist', 1, 1, 10000, '2015-01-02 22:47:30', NULL),
(2, '用户添加', 'system/useradd', 1, 1, 0, '2015-05-04 14:24:32', NULL),
(3, '用户修改', 'system/usermod', 1, 1, 0, '2015-01-02 22:47:30', '2015-05-18 16:39:10'),
(4, '用户组管理', 'system/grouplist', 1, 1, 10000, '2015-01-02 22:48:30', NULL),
(5, '用户组添加', 'system/groupadd', 1, 1, 0, '2015-05-04 14:01:36', '2015-05-04 14:14:33'),
(6, '用户组修改', 'system/groupmod', 1, 1, 0, '2015-05-04 14:15:07', NULL),
(7, '用户组删除', 'system/groupdel', 1, 1, 0, '2015-05-21 15:36:17', '2015-05-22 21:13:04'),
(8, '组权限管理', 'system/grouppowerlist', 1, 1, 10000, '2015-01-02 22:51:01', '2015-01-02 23:10:17'),
(9, '组权限添加', 'system/grouppoweradd', 1, 1, 0, '2015-05-04 16:24:50', NULL),
(10, '组权限修改', 'system/grouppowermod', 1, 1, 0, '2015-05-04 16:29:38', NULL),
(11, '组权限删除', 'system/grouppowerdel', 1, 1, 0, '2015-05-21 17:11:03', NULL),
(12, '权限管理', 'system/powerlist', 1, 1, 0, '2015-01-02 22:47:58', '2015-05-15 11:28:00'),
(13, '权限添加', 'system/poweradd', 1, 1, 0, NULL, '2015-05-04 14:13:56'),
(14, '权限修改', 'system/powermod', 1, 1, 0, '2015-05-04 11:34:35', '2015-05-04 13:33:58'),
(15, '广告主列表', 'advertiser/index', 1, 2, 10000, '2015-07-17 16:35:08', NULL),
(16, '广告主列表拉取', 'advertiser/ajaxlist', 1, 2, 0, '2015-07-17 16:35:49', NULL),
(17, '广告主创建', 'advertiser/create', 1, 2, 9000, '2015-07-17 16:35:49', NULL),
(18, '广告主修改页面', 'advertiser/edit', 1, 2, 0, '2015-07-17 16:35:49', NULL),
(19, '广告主修改', 'advertiser/update', 1, 2, 0, '2015-07-17 16:35:49', NULL),
(20, '渠道商列表', 'affiliates/index', 1, 3, 10000, '2015-07-17 16:35:49', NULL),
(21, '渠道商列表拉取', 'affiliates/ajaxlist', 1, 3, 0, '2015-07-17 16:35:49', NULL),
(22, '渠道商创建', 'affiliates/create', 1, 3, 9000, '2015-07-17 16:35:49', NULL),
(23, '渠道商修改页面', 'affiliates/edit', 1, 3, 0, '2015-07-17 16:35:49', NULL),
(24, '渠道商修改', 'affiliates/update', 1, 3, 0, '2015-07-17 16:35:49', NULL),
(25, 'Offer列表', 'offer/list', 1, 4, 10000, '2015-07-17 16:35:49', NULL),
(26, 'Offer添加', 'offer/add', 1, 4, 9000, '2015-07-17 16:35:49', NULL),
(27, 'Offer详情', 'offer/offerdetail', 1, 4, 0, '2015-07-17 16:35:49', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `joy_system_user`
--

CREATE TABLE IF NOT EXISTS `joy_system_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱地址',
  `password` varchar(40) NOT NULL COMMENT '密码',
  `groupid` tinyint(4) DEFAULT '0' COMMENT '组ID',
  `first_name` varchar(50) NOT NULL DEFAULT '',
  `last_name` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `company` varchar(255) DEFAULT NULL COMMENT '公司名称',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `address2` varchar(255) DEFAULT NULL COMMENT '地址2',
  `city` varchar(100) DEFAULT NULL COMMENT '城市',
  `region` varchar(100) DEFAULT NULL COMMENT '省、州',
  `country` varchar(100) DEFAULT NULL COMMENT '国家',
  `zipcode` varchar(50) DEFAULT NULL COMMENT '邮编',
  `phone` varchar(50) DEFAULT NULL COMMENT '电话',
  `manager_userid` int(11) DEFAULT NULL COMMENT '商务userid',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态(0:待审核 1:激活 2:删除)',
  `openuser` tinyint(1) unsigned DEFAULT '0' COMMENT '能否创建用户，0：不能，1：可以',
  `logincount` int(11) DEFAULT '0' COMMENT '登录次数',
  `lastmodify` datetime DEFAULT NULL COMMENT '修改时间',
  `lastlogin` datetime DEFAULT NULL COMMENT '最后一次登陆时间',
  `loginip` char(20) DEFAULT '' COMMENT '登陆IP',
  `createtime` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `password` (`password`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `joy_system_user`
--

INSERT INTO `joy_system_user` (`id`, `email`, `password`, `groupid`, `first_name`, `last_name`, `title`, `company`, `address`, `address2`, `city`, `region`, `country`, `zipcode`, `phone`, `manager_userid`, `status`, `openuser`, `logincount`, `lastmodify`, `lastlogin`, `loginip`, `createtime`) VALUES
(1, 'admin@joydream.cn', 'e10adc3949ba59abbe56e057f20f883e', 1, 'joy', 'dream', 'joydream', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 10, NULL, '2015-07-17 16:57:50', '127.0.0.1', '2015-07-17 14:37:29');





