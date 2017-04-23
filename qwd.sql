-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2017-04-22 21:11:53
-- 服务器版本： 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qwd`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE `admin` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL COMMENT '用户名',
  `password` char(40) NOT NULL COMMENT '密码',
  `login_time` int(11) NOT NULL COMMENT '上次登录时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`, `login_time`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1489327662);

-- --------------------------------------------------------

--
-- 表的结构 `desk`
--

CREATE TABLE `desk` (
  `id` int(10) UNSIGNED NOT NULL,
  `rid` smallint(6) NOT NULL DEFAULT '0' COMMENT '房间号',
  `tid` smallint(6) NOT NULL DEFAULT '0' COMMENT '桌子号',
  `member_one` varchar(60) DEFAULT NULL COMMENT '玩家1姓名',
  `member_two` varchar(60) DEFAULT NULL COMMENT '玩家2姓名',
  `member_three` varchar(60) DEFAULT NULL COMMENT '玩家3姓名',
  `number` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '玩家在线人数',
  `member_one_counter` int(11) NOT NULL DEFAULT '0',
  `member_two_counter` int(11) NOT NULL DEFAULT '0',
  `member_three_counter` int(11) NOT NULL DEFAULT '0',
  `question` varchar(60) DEFAULT NULL COMMENT '题目id号',
  `question_counter` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '题目数量',
  `qid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '当前题号id',
  `numbers` varchar(30) DEFAULT NULL COMMENT '卡号',
  `message` varchar(255) NOT NULL,
  `winner` varchar(60) DEFAULT NULL,
  `winner_counter` int(11) NOT NULL DEFAULT '0',
  `winner_time` int(11) NOT NULL DEFAULT '0',
  `again` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `desk`
--

INSERT INTO `desk` (`id`, `rid`, `tid`, `member_one`, `member_two`, `member_three`, `number`, `member_one_counter`, `member_two_counter`, `member_three_counter`, `question`, `question_counter`, `qid`, `numbers`, `message`, `winner`, `winner_counter`, `winner_time`, `again`) VALUES
(1, 1, 2, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(2, 1, 3, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(3, 1, 6, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(4, 2, 6, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(5, 6, 2, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(6, 4, 3, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(7, 3, 6, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(8, 5, 6, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(9, 7, 6, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(10, 9, 6, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(11, 9, 9, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(12, 1, 9, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(13, 1, 8, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(14, 7, 3, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(15, 9, 5, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(16, 9, 3, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(17, 9, 2, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(18, 9, 1, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(19, 9, 4, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(20, 9, 8, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(21, 9, 7, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(22, 8, 5, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(23, 5, 2, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(24, 2, 1, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(25, 5, 7, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(26, 5, 8, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(27, 5, 5, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(28, 2, 2, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(29, 2, 4, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(30, 5, 3, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(31, 5, 9, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(32, 5, 1, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0),
(33, 8, 4, '', '', '', 0, 0, 0, 0, '', 0, 0, '', '', '', 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `member`
--

CREATE TABLE `member` (
  `id` int(10) UNSIGNED NOT NULL,
  `rid` int(10) UNSIGNED NOT NULL COMMENT '房间id',
  `tid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(60) DEFAULT NULL COMMENT '名字',
  `email` varchar(60) NOT NULL COMMENT 'email',
  `password` char(40) NOT NULL COMMENT '密码',
  `create_at` int(11) NOT NULL COMMENT '注册时间',
  `admin` smallint(6) NOT NULL DEFAULT '1' COMMENT '是否为管理员（1否）（2是）',
  `status` smallint(6) NOT NULL DEFAULT '1' COMMENT '是否禁用（1否）（2是）',
  `activate` smallint(6) NOT NULL DEFAULT '1' COMMENT '是否激活（1否）（2是）',
  `add_time` int(11) NOT NULL COMMENT '进入游戏时间',
  `win` int(11) NOT NULL DEFAULT '0' COMMENT '赢得局数',
  `correct` int(11) NOT NULL DEFAULT '0' COMMENT '正确答题',
  `error` int(11) NOT NULL DEFAULT '0' COMMENT '错误答题'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `member`
--

INSERT INTO `member` (`id`, `rid`, `tid`, `name`, `email`, `password`, `create_at`, `admin`, `status`, `activate`, `add_time`, `win`, `correct`, `error`) VALUES
(10, 8, 4, 'yuandian', '2385812567@qq.com', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 1489246725, 1, 1, 2, 1492877568, 17, 14, 13),
(11, 8, 4, 'test001', 'test001@qq.com', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 1489246678, 1, 1, 2, 1492877587, 12, 13, 12),
(12, 8, 4, 'test002', 'test002@qq.com', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 1492867676, 1, 1, 2, 1492877578, 13, 16, 11),
(16, 2, 4, 'test003', 'test003@qq.com', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 1492867689, 1, 1, 2, 1489246725, 23, 16, 10),
(17, 1, 6, 'test004', 'test004@qq.com', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 1492867690, 1, 1, 1, 1489246734, 29, 13, 5),
(18, 2, 3, 'test005', 'test005@qq.com', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 1489246725, 1, 1, 2, 1489246712, 32, 14, 5),
(19, 2, 1, 'test006', 'test006@qq.com', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 1489246725, 1, 1, 2, 1489246710, 18, 15, 8),
(20, 1, 8, 'test007', 'test007@qq.com', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 1492867645, 1, 1, 1, 1489246708, 32, 23, 6),
(21, 2, 7, 'test008', 'test008@qq.com', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 1489246725, 1, 1, 2, 1489246789, 13, 27, 9),
(22, 2, 2, 'test009', 'test009@qq.com', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 1489246725, 1, 1, 2, 1489246767, 14, 29, 7);

-- --------------------------------------------------------

--
-- 表的结构 `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `message`
--

INSERT INTO `message` (`id`, `uid`, `name`, `message`) VALUES
(33, -1, 'hhaa', '再来一局'),
(34, -1, 'hhaa', '再来一局'),
(35, 10, 'yuan', '再来一局'),
(36, 11, 'yuan1', '再来一局'),
(37, 12, 'hhaa', '再来一局'),
(38, -1, 'yuan1', '再来一局'),
(39, -1, 'hhaa', '再来一局'),
(40, -1, 'yuan', '再来一局'),
(41, -1, 'yuan1', '再来一局'),
(42, -1, 'hhaa', '再来一局'),
(43, -1, 'yuan', '再来一局'),
(44, -1, 'yuan1', '再来一局'),
(45, -1, 'hhaa', '再来一局'),
(46, -1, 'yuan', '再来一局'),
(47, -1, 'hhaa', '再来一局'),
(48, -1, 'yuan1', '再来一局'),
(49, -1, 'yuan', '再来一局'),
(50, -1, 'hhaa', '再来一局'),
(51, -1, 'yuan1', '再来一局'),
(52, -1, 'hhaa', '再来一局'),
(53, -1, 'yuan', '再来一局'),
(54, -1, 'yuan1', '再来一局'),
(55, -1, 'hhaa', '再来一局'),
(56, -1, 'yuan', '再来一局'),
(57, -1, 'hhaa', '再来一局'),
(58, -1, 'yuan1', '再来一局'),
(59, -1, 'yuan', '再来一局'),
(60, -1, 'hhaa', '再来一局'),
(61, -1, 'yuan1', '再来一局'),
(62, -1, 'hhaa', '再来一局'),
(63, -1, 'yuan', '再来一局'),
(64, -1, 'yuan1', '再来一局'),
(65, -1, 'hhaa', '再来一局'),
(66, -1, 'yuan', '再来一局'),
(67, -1, 'hhaa', '再来一局'),
(68, -1, 'yuan1', '再来一局'),
(69, 10, 'yuan', '再来一局'),
(70, -1, 'hhaa', '再来一局'),
(71, 11, 'yuan1', '再来一局'),
(72, -1, 'yuan1', '再来一局'),
(73, -1, 'yuan', '再来一局'),
(74, -1, 'hhaa', '再来一局'),
(75, -1, 'hhaa', '再来一局'),
(76, -1, 'yuan1', '再来一局');

-- --------------------------------------------------------

--
-- 表的结构 `question`
--

CREATE TABLE `question` (
  `id` int(10) UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL COMMENT '问题',
  `answer_text` varchar(255) NOT NULL,
  `answer` varchar(255) NOT NULL COMMENT '答案',
  `status` smallint(6) NOT NULL DEFAULT '1' COMMENT '1启用2不启用'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `question`
--

INSERT INTO `question` (`id`, `question`, `answer_text`, `answer`, `status`) VALUES
(1, '被称为“万园之园”的是指？', '苏州园林,圆明园,乔家大院', '2', 1),
(2, '秦汉时代，人们说的“关中”指哪一带？', '函谷关以西,嘉峪关北', '1', 1),
(3, '麻婆豆腐的起源是哪？', '京,川,湘', '2', 1),
(4, '在清朝《四库全书》修好藏于文渊阁，文渊阁坐落于？', '上海,杭州,北京', '3', 1),
(5, '对人体来说安全电压是多少伏？', '35,36,37,38', '2', 1),
(6, '酸性物质中毒不可以用以下哪个洗胃？', '牛奶,蛋清,白醋', '3', 1),
(7, '股票交易里面通常说成交多少多少“手”，这一“手”是指多少股？', '100,200,1000', '1', 1),
(8, '下列不属于世界七大奇迹的是？', '巴比伦空中花园,埃及金字塔,秦始皇陵兵马俑,宙斯神像', '4', 1);

-- --------------------------------------------------------

--
-- 表的结构 `room`
--

CREATE TABLE `room` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '房间名称',
  `number` int(11) NOT NULL COMMENT '卡片号',
  `count` varchar(255) NOT NULL COMMENT '在玩人数',
  `addtime` datetime NOT NULL COMMENT '修改时间',
  `status` int(11) NOT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `room`
--

INSERT INTO `room` (`id`, `name`, `number`, `count`, `addtime`, `status`) VALUES
(1, '踏雪无痕', 156756, '99', '2016-10-29 11:19:18', 1),
(2, '三分天下', 56734, '52', '2016-10-27 15:11:35', 1),
(3, '踏雪无痕', 567343, '118', '2016-10-27 15:11:41', 1),
(4, '踏雪无痕', 34343, '247', '2016-10-27 21:43:18', 1),
(5, '快歌而来', 5673434, '44', '2016-10-27 15:11:51', 1),
(6, '踏雪无痕', 567343, '37', '2016-10-27 15:11:56', 1),
(7, '踏雪无痕', 156756, '11', '2016-10-27 15:12:01', 1),
(8, '英雄陌路', 5673434, '18', '2016-10-27 15:12:07', 1),
(9, '踏雪无痕', 5673434, '18', '2016-10-27 15:12:07', 1);

-- --------------------------------------------------------

--
-- 表的结构 `tables`
--

CREATE TABLE `tables` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tables`
--

INSERT INTO `tables` (`id`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `desk`
--
ALTER TABLE `desk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `desk-rid` (`rid`),
  ADD KEY `desk-tid` (`tid`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `desk`
--
ALTER TABLE `desk`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- 使用表AUTO_INCREMENT `member`
--
ALTER TABLE `member`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- 使用表AUTO_INCREMENT `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;
--
-- 使用表AUTO_INCREMENT `question`
--
ALTER TABLE `question`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- 使用表AUTO_INCREMENT `room`
--
ALTER TABLE `room`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- 使用表AUTO_INCREMENT `tables`
--
ALTER TABLE `tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
