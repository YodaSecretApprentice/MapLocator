-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 29, 2016 at 04:28 PM
-- Server version: 5.5.43
-- PHP Version: 5.4.45-0+deb7u1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `event_logger`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`) VALUES
(18, 'Potholes'),
(19, 'Signs'),
(20, 'Pavement'),
(21, 'Trees'),
(22, 'test'),
(23, 'testf');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `filename` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=70 ;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `ticket_id`, `filename`) VALUES
(59, 66, 'lakouva.jpg'),
(60, 67, 'tree1.JPG'),
(61, 68, 'stop1.jpg'),
(62, 68, 'stop1.jpg'),
(63, 68, 'stop2.jpg'),
(64, 69, 'pezodromio.jpg'),
(65, 73, 'lakouva.jpg'),
(66, 75, 'anglais.jpg'),
(67, 75, 'avatar.jpg'),
(68, 75, 'anglais.jpg'),
(69, 76, 'magician.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `category` int(11) NOT NULL,
  `state` enum('open','closed') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `solved_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `solved_by` int(11) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double DEFAULT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=77 ;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `user_id`, `title`, `category`, `state`, `timestamp`, `solved_at`, `solved_by`, `latitude`, `longitude`, `description`, `comment`) VALUES
(76, 11, 'Supinfo marker', 21, 'closed', '2016-05-07 14:30:57', '2016-05-22 14:22:20', 13, 43.610577845136305, 1.4353120687255796, 'The trees look old', 'done by me');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` char(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `role` enum('user','admin') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `phone`, `role`) VALUES
(5, 'test admin', 'admin1@admin.com', 'a1102957491b9ce5441e111f7725f2fd0201bc32465e2536e5182d1c5e3f6b0965355c09f2c8b9111ab6d18a73b75f0f3a06e788bd2a6dff4ddc7c4da6ada603', '123456', 'admin'),
(8, 'user2', 'user2@user.com', 'b999105cda0ce3e02e7af1666634e773b12cb735a19eac2125bcd43df73692294403057b5092b2b709a5cbebd635328884e5fc248624d310c87a52cfa8f38a3b', '09012345', 'user');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
