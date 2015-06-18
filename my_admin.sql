-- phpMyAdmin SQL Dump
-- version 3.3.10.5
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 �?06 �?18 �?15:30
-- 服务器版本: 5.5.41
-- PHP 版本: 5.5.21

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `my_admin`
--

-- --------------------------------------------------------

--
-- 表的结构 `ad_admin`
--

CREATE TABLE IF NOT EXISTS `ad_admin` (
  `ad_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `ad_username` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `ad_password` char(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `adg_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员组ID',
  `pv_ids` text COLLATE utf8_unicode_ci NOT NULL COMMENT '操作权限[,分割] ',
  `pvg_ids` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '操作权限组[,分割]',
  `ad_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态[1-启用,2-停用]',
  `ad_isdel` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除[0-否,1-是]',
  `ad_addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `ad_addip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加IP',
  `ad_lasttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  `ad_lastip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新IP',
  PRIMARY KEY (`ad_id`),
  KEY `ad_username` (`ad_username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理员表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `ad_admin`
--

INSERT INTO `ad_admin` (`ad_id`, `ad_username`, `ad_password`, `adg_id`, `pv_ids`, `pvg_ids`, `ad_status`, `ad_isdel`, `ad_addtime`, `ad_addip`, `ad_lasttime`, `ad_lastip`) VALUES
(1, '10000', 'dc483e80a7a0bd9ef71d8cf973673924', 1, '', '', 1, 0, 0, 0, 0, 0),
(2, '10001', 'dc483e80a7a0bd9ef71d8cf973673924', 0, ',1,2,3,6,23,', '', 2, 0, 1394967295, 0, 1434612308, 2130706433);

-- --------------------------------------------------------

--
-- 表的结构 `ad_admin_group`
--

CREATE TABLE IF NOT EXISTS `ad_admin_group` (
  `adg_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `adg_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户组名称',
  `pv_ids` text COLLATE utf8_unicode_ci NOT NULL COMMENT '权限ID集[,分割]',
  `pvg_ids` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限组ID集[,分割]',
  `adg_status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态[1-启用,2-停用]',
  `adg_isdel` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除[0-否,1-是]',
  `adg_lasttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后时间[时间戳]',
  `adg_lastip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后IP[数字型]',
  PRIMARY KEY (`adg_id`),
  UNIQUE KEY `adg_name` (`adg_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理员用户组' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `ad_admin_group`
--

INSERT INTO `ad_admin_group` (`adg_id`, `adg_name`, `pv_ids`, `pvg_ids`, `adg_status`, `adg_isdel`, `adg_lasttime`, `adg_lastip`) VALUES
(1, '超级管理员', '', '', 1, 0, 1434612068, 2130706433);

-- --------------------------------------------------------

--
-- 表的结构 `ad_admin_info`
--

CREATE TABLE IF NOT EXISTS `ad_admin_info` (
  `ai_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `ad_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `ai_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '姓名',
  `ai_nickname` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '昵称|笔名|作者',
  `ai_phone` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '手机号',
  `ai_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '电子邮件',
  `ai_lasttime` int(10) NOT NULL DEFAULT '0' COMMENT '最后时间[时间戳]',
  PRIMARY KEY (`ai_id`),
  KEY `ad_id` (`ad_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理员用户信息表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `ad_admin_info`
--

INSERT INTO `ad_admin_info` (`ai_id`, `ad_id`, `ai_name`, `ai_nickname`, `ai_phone`, `ai_email`, `ai_lasttime`) VALUES
(1, 1, '超级管理员', '', 13800138000, 'admin@admin.com', 1399878542),
(2, 2, '', '', 0, '', 1434612308);

-- --------------------------------------------------------

--
-- 表的结构 `ad_admin_log`
--

CREATE TABLE IF NOT EXISTS `ad_admin_log` (
  `al_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID[自增]',
  `ad_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `al_result` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '操作接果[1-成功,2-失败]',
  `al_content` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '日志内容',
  `al_isdel` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除[0-否,1-是]',
  `al_lasttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后操作时间',
  `al_lastip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后操作IP',
  PRIMARY KEY (`al_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理员日志表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ad_admin_log`
--

-- --------------------------------------------------------

--
-- 表的结构 `ad_menu_nav`
--

CREATE TABLE IF NOT EXISTS `ad_menu_nav` (
  `mn_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `mn_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '菜单名称',
  `mn_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '菜单地址',
  `mn_icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '菜单图标[ClassName]',
  `mn_type` tinyint(1) unsigned NOT NULL DEFAULT '3' COMMENT '菜单类别[1-左导航,2-上导航,3-其他]',
  `mn_sort` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序[由小到大优先排序]',
  `mn_pid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '菜单父ID[0-顶级]',
  `mn_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态[1-启用,2-停用]',
  `mn_isdel` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除[0-否,1-是]',
  `mn_lasttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后时间[时间戳]',
  `mn_lastip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后IP[数字型]',
  PRIMARY KEY (`mn_id`),
  KEY `mn_pid` (`mn_pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='菜单和导航表' AUTO_INCREMENT=24 ;

--
-- 转存表中的数据 `ad_menu_nav`
--

INSERT INTO `ad_menu_nav` (`mn_id`, `mn_name`, `mn_url`, `mn_icon`, `mn_type`, `mn_sort`, `mn_pid`, `mn_status`, `mn_isdel`, `mn_lasttime`, `mn_lastip`) VALUES
(1, '后台管理', 'top-sys', '', 2, 0, 0, 1, 0, 1430280184, 2130706433),
(2, '默认首页', 'sys-index-index', 'fa-home', 1, 0, 1, 1, 0, 1434610177, 2130706433),
(3, '导航列表', 'sys-menu_nav', 'fa-navicon', 1, 0, 1, 1, 0, 1434610638, 2130706433),
(4, '导航添加', 'sys-menu_nav-add', '', 0, 0, 3, 1, 0, 1430100982, 4294967295),
(5, '导航修改', 'sys-menu_nav-edit', '', 0, 0, 3, 1, 0, 1430101091, 4294967295),
(6, '导航删除', 'sys-menu_nav-del', '', 0, 0, 3, 1, 0, 1430101121, 4294967295),
(7, '管理员列表', 'sys-admin', 'fa-user', 1, 0, 1, 1, 0, 1434610331, 2130706433),
(8, '管理员添加', 'sys-admin-add', '', 0, 0, 7, 1, 0, 1430112759, 2130706433),
(9, '管理员修改', 'sys-admin-edit', '', 0, 0, 7, 1, 0, 1430112772, 2130706433),
(10, '管理员删除', 'sys-admin-del', '', 0, 0, 7, 1, 0, 1430112779, 2130706433),
(11, '管理员组列表', 'sys-admin_group', 'fa-users', 1, 0, 1, 1, 0, 1434610336, 2130706433),
(12, '管理员组添加', 'sys-admin_group-add', '', 0, 0, 11, 1, 0, 1430101275, 4294967295),
(13, '管理员组修改', 'sys-admin_group-edit', '', 0, 0, 11, 1, 0, 1430101301, 4294967295),
(14, '管理员组删除', 'sys-admin_group-del', '', 0, 0, 11, 1, 0, 1430101325, 4294967295),
(15, '权限列表', 'sys-purview', 'fa-eye', 1, 0, 1, 1, 0, 1434610775, 2130706433),
(16, '权限添加', 'sys-purview-add', '', 0, 0, 15, 1, 0, 1430101384, 4294967295),
(17, '权限修改', 'sys-purview-edit', '', 0, 0, 15, 1, 0, 1430101412, 4294967295),
(18, '权限删除', 'sys-purview-del', '', 0, 0, 15, 1, 0, 1430101439, 4294967295),
(19, '权限组列表', 'sys-purview_group', 'fa-bullseye', 1, 0, 1, 1, 0, 1434610785, 2130706433),
(20, '权限组添加', 'sys-purview_group-add', '', 0, 0, 19, 1, 0, 1430101505, 4294967295),
(21, '权限组修改', 'sys-purview_group-edit', '', 0, 0, 19, 1, 0, 1430101495, 4294967295),
(22, '权限组删除', 'sys-purview_group-del', '', 0, 0, 19, 1, 0, 1430101542, 4294967295),
(23, '管理员日志列表', 'sys-admin_log', 'fa-list', 1, 0, 1, 1, 0, 1434611303, 2130706433);

-- --------------------------------------------------------

--
-- 表的结构 `ad_purview`
--

CREATE TABLE IF NOT EXISTS `ad_purview` (
  `pv_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `pv_name` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限名称',
  `pv_key` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限KEY[唯一]',
  `pv_status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态[1-启用,2-停用]',
  `pv_isdel` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除[0-否,1-是]',
  `pv_lasttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后时间[时间戳]',
  `pv_lastip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后IP[数字型]',
  PRIMARY KEY (`pv_id`),
  KEY `pv_key` (`pv_key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理权限表' AUTO_INCREMENT=24 ;

--
-- 转存表中的数据 `ad_purview`
--

INSERT INTO `ad_purview` (`pv_id`, `pv_name`, `pv_key`, `pv_status`, `pv_isdel`, `pv_lasttime`, `pv_lastip`) VALUES
(1, '后台管理', 'top-sys', 1, 0, 1429079255, 0),
(2, '默认首页', 'sys-index-index', 1, 0, 1429497209, 2130706433),
(3, '导航列表', 'sys-menu_nav', 1, 0, 1429079255, 0),
(4, '导航添加', 'sys-menu_nav-add', 1, 0, 1430100982, 4294967295),
(5, '导航修改', 'sys-menu_nav-edit', 1, 0, 1430101091, 4294967295),
(6, '导航删除', 'sys-menu_nav-del', 1, 0, 1430101121, 4294967295),
(7, '管理员列表', 'sys-admin', 1, 0, 1429079255, 0),
(8, '管理员添加', 'sys-admin-add', 1, 0, 1430101204, 4294967295),
(9, '管理员修改', 'sys-admin-edit', 1, 0, 1430101224, 4294967295),
(10, '管理员删除', 'sys-admin-del', 1, 0, 1430101248, 4294967295),
(11, '管理员组列表', 'sys-admin_group', 1, 0, 1429079255, 0),
(12, '管理员组添加', 'sys-admin_group-add', 1, 0, 1430101275, 4294967295),
(13, '管理员组修改', 'sys-admin_group-edit', 1, 0, 1430101301, 4294967295),
(14, '管理员组删除', 'sys-admin_group-del', 1, 0, 1430101325, 4294967295),
(15, '权限列表', 'sys-purview', 1, 0, 1429079255, 0),
(16, '权限添加', 'sys-purview-add', 1, 0, 1430101384, 4294967295),
(17, '权限修改', 'sys-purview-edit', 1, 0, 1430101412, 4294967295),
(18, '权限删除', 'sys-purview-del', 1, 0, 1430101439, 4294967295),
(19, '权限组列表', 'sys-purview_group', 1, 0, 1429079255, 0),
(20, '权限组添加', 'sys-purview_group-add', 1, 0, 1430101505, 4294967295),
(21, '权限组修改', 'sys-purview_group-edit', 1, 0, 1430101495, 4294967295),
(22, '权限组删除', 'sys-purview_group-del', 1, 0, 1430101542, 4294967295),
(23, '管理员日志列表', 'sys-admin_log', 1, 0, 1429521743, 2130706433);

-- --------------------------------------------------------

--
-- 表的结构 `ad_purview_group`
--

CREATE TABLE IF NOT EXISTS `ad_purview_group` (
  `pvg_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `pvg_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限组名称',
  `pv_ids` text COLLATE utf8_unicode_ci NOT NULL COMMENT '权限ID集[,分割]',
  `pvg_status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态[1-启用,2-停用]',
  `pvg_isdel` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除[0-否,1-是]',
  `pvg_lasttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后时间[时间戳]',
  `pvg_lastip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后IP[数字型]',
  PRIMARY KEY (`pvg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理权限组' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ad_purview_group`
--


-- --------------------------------------------------------

--
-- 表的结构 `ad_session`
--

CREATE TABLE IF NOT EXISTS `ad_session` (
  `sess_id` char(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'SESSION_ID',
  `sess_data` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'SESSION_DATA',
  `sess_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SESSION_TIME',
  `sess_ip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'SESSION_IP',
  `sess_u_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'SESSION_U_ID',
  KEY `sess_id` (`sess_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='SESSION';

--
-- 转存表中的数据 `ad_session`
--
