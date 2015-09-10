-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2015 at 02:58 PM
-- Server version: 5.5.43-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `lc`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fname` varchar(32) NOT NULL,
  `lname` varchar(32) NOT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(256) NOT NULL,
  `accept_terms` tinyint(1) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`uid`, `fname`, `lname`, `phone`, `email`, `password`, `accept_terms`, `created`) VALUES
(6, 'Mike', 'McMann', NULL, 'mmcmann2@gmail.com', '$2a$10$a56a3ff6e371123362536OLBlE07U/HWor6XXy.w.ly/0ypNhK3JK', 1, '2015-09-09 20:00:16'),
(7, 'Sean', 'Connory', NULL, 's@c.com', '$2a$10$f04e372139188b34def01eJPhUjbg0zByhCQ3v2K1K5jzV7sDpm8u', 1, '2015-09-10 00:59:49'),
(8, 'Thread', 'Locker', '6145559999', 't@l.com', '$2a$10$9b9a4364b3f8006e86990e9dQUEYnact54BbnNs2gCeecYmg.X1TG', 1, '2015-09-10 01:08:13');

-- --------------------------------------------------------

--
-- Table structure for table `customer_event`
--

DROP TABLE IF EXISTS `customer_event`;
CREATE TABLE IF NOT EXISTS `customer_event` (
  `uid` int(10) unsigned NOT NULL,
  `event_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`event_id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `customer_event`:
--   `event_id`
--       `event` -> `event_id`
--   `uid`
--       `customer` -> `uid`
--

--
-- Dumping data for table `customer_event`
--

INSERT INTO `customer_event` (`uid`, `event_id`) VALUES
(6, 2),
(6, 6),
(8, 7),
(7, 8);

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
CREATE TABLE IF NOT EXISTS `event` (
  `event_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`event_id`),
  UNIQUE KEY `event_id` (`event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`event_id`, `name`, `date`) VALUES
(1, 'Event 1', '2015-08-03'),
(2, 'Event 2', '2015-08-10'),
(3, 'Event 3', '2015-09-21'),
(4, 'Event 4', '2015-09-28'),
(5, 'Event 5', '2015-10-05'),
(6, 'Event 6', '2015-10-20'),
(7, 'Event 7', '2015-11-18'),
(8, 'Event 8', '2015-12-21');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_event`
--
ALTER TABLE `customer_event`
  ADD CONSTRAINT `customer_event_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`),
  ADD CONSTRAINT `customer_event_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `customer` (`uid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
