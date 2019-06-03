-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `course`;
CREATE TABLE `course` (
  `courseID` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `courseMei` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(10,0) NOT NULL,
  PRIMARY KEY (`courseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `course` (`courseID`, `category`, `courseMei`, `price`) VALUES
(0,	'0',	'ランチ/“至福の午餐会”',	6800),
(1,	'0',	'ランチ/“美食の歓び',	4800),
(2,	'1',	'ディナー/ムニュ“神楽”',	8500),
(3,	'1',	'ディナー/シェフ“大堀”スペシャル',	13500),
(4,	'0',	'ランチ/Lunch クリスマス特別メニュ',	8800),
(5,	'0',	'ランチ/Lunch 2018謹賀新年メニュ',	8800),
(6,	'1',	'ディナー/Dinner 年末年始限定メニュ',	8800),
(7,	'1',	'お席のみのご予約',	0),
(8,	'0',	'お席のみのご予約',	0);

DROP TABLE IF EXISTS `kokyak`;
CREATE TABLE `kokyak` (
  `kokyakuID` int(11) NOT NULL AUTO_INCREMENT,
  `kokyakuMei` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kokyakuHuri` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `addr` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pswd` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`kokyakuID`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `stock`;
CREATE TABLE `stock` (
  `tableID` int(11) NOT NULL AUTO_INCREMENT,
  `tableSu` int(11) NOT NULL,
  `seatSu` int(11) NOT NULL,
  `biko` varchar(200) NOT NULL,
  PRIMARY KEY (`tableID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `stock` (`tableID`, `tableSu`, `seatSu`, `biko`) VALUES
(1,	10,	4,	'');

DROP TABLE IF EXISTS `yoyak`;
CREATE TABLE `yoyak` (
  `yoyakuID` int(11) NOT NULL AUTO_INCREMENT,
  `yoyakuji` datetime NOT NULL,
  `ninzu` int(3) NOT NULL,
  `courceID` int(5) NOT NULL,
  `kokyakuID` int(11) NOT NULL,
  `goyobo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`yoyakuID`),
  KEY `kibobi` (`yoyakuji`),
  KEY `kokyakuID` (`kokyakuID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- 2019-06-03 12:43:51
