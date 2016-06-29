-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 29, 2016 at 04:59 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=71 ;

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
(69, 76, 'magician.jpg'),
(70, 77, 'arbreMort.jpg');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=78 ;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `user_id`, `title`, `category`, `state`, `timestamp`, `solved_at`, `solved_by`, `latitude`, `longitude`, `description`, `comment`) VALUES
(76, 11, 'Supinfo marker', 21, 'closed', '2016-05-07 14:30:57', '2016-05-22 14:22:20', 13, 43.610577845136305, 1.4353120687255796, 'The trees look old', 'done by me'),
(77, 14, 'Arbre mort au sol', 21, 'open', '2016-06-29 14:53:34', '0000-00-00 00:00:00', 0, 43.606072146424246, 1.448959148193353, 'Un arbre est au sol et entrave le passage des pitons', '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `phone`, `role`) VALUES
(5, 'admin', 'admin1@admin.com', '8b63f8a37cf01fba6f8b595a4c558f77d4cb72a7a226b4e036981537881f6bbd71153ecb78f701c8a43a3756c51c4ed7b6e89148d56d0cd25dcd2a109dd82ff9', '123456', 'admin'),
(8, 'user2', 'user2@user.com', 'b999105cda0ce3e02e7af1666634e773b12cb735a19eac2125bcd43df73692294403057b5092b2b709a5cbebd635328884e5fc248624d310c87a52cfa8f38a3b', '09012345', 'user'),
(14, 'user', 'user@aol.com', 'b14361404c078ffd549c03db443c3fede2f3e534d73f78f77301ed97d4a436a9fd9db05ee8b325c0ad36438b43fec8510c204fc1c1edb21d0941c00e9e2c1ce2', '0654545454', 'user');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
