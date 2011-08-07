-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 23, 2011 at 03:45 PM
-- Server version: 5.1.54
-- PHP Version: 5.3.5-0.dotdeb.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Table structure for table `yf_campuses`
--

CREATE TABLE IF NOT EXISTS `yf_campuses` (
  `id` int(4) NOT NULL AUTO_INCREMENT COMMENT 'Campus ID',
  `name` varchar(255) NOT NULL COMMENT 'Campus name',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='University Campuses';

-- --------------------------------------------------------

--
-- Table structure for table `yf_events`
--

CREATE TABLE IF NOT EXISTS `yf_events` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `host` varchar(255) DEFAULT NULL,
  `campus_id` int(4) NOT NULL,
  `location` varchar(255) NOT NULL,
  `notes` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `yf_foodserved`
--

CREATE TABLE IF NOT EXISTS `yf_foodserved` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `eid` int(8) NOT NULL,
  `fid` int(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `yf_foodtypes`
--

CREATE TABLE IF NOT EXISTS `yf_foodtypes` (
  `id` int(4) NOT NULL AUTO_INCREMENT COMMENT 'Types of food served',
  `name` varchar(255) NOT NULL COMMENT 'Name of food type',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `yf_reservations`
--

CREATE TABLE IF NOT EXISTS `yf_reservations` (
`eid` INT( 8 ) NOT NULL ,
`uid` INT( 8 ) NOT NULL ,
PRIMARY KEY ( `eid` , `uid` )
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `yf_users`
--

CREATE TABLE IF NOT EXISTS `yf_users` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` char(40) NOT NULL,
  `member_since` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `latest_signin` timestamp NULL DEFAULT NULL,
  `sms_settings` text,
  `signup_ip` int(4) DEFAULT NULL,
  `latest_ip` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;
