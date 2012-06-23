-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 24, 2012 at 12:14 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `floatyourboat`
--

--  --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE IF NOT EXISTS `equipment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `EquipmentName` varchar(30) CHARACTER SET latin1 NOT NULL DEFAULT '1',
  `Make` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `Model` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `Vendor` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `Serial` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `WarrantyExpiry` date DEFAULT NULL,
  `Hours` bigint(20) DEFAULT '0',
  `HoursDate` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=ascii AUTO_INCREMENT=5 ;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`id`, `EquipmentName`, `Make`, `Model`, `Vendor`, `Serial`, `WarrantyExpiry`, `Hours`, `HoursDate`) VALUES
(1, 'Engine - Port', 'Mazda', 'Zoom Zoom', '', '', '2012-06-01', 657, '2012-06-12'),
(2, 'Engine - Starboard', 'Mazda', 'Zoom Zoom', '', '', '2012-06-30', 322, '2012-06-15'),
(3, 'Speakers', 'Altec Lansing', 'Silver', 'Microcenter', '123456789', '2012-06-01', 36, '2012-06-15'),
(4, 'Air Conditioner', '', '', '', '', '2010-01-01', 0, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `logbook`
--

CREATE TABLE IF NOT EXISTS `logbook` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `latitude` varchar(20) DEFAULT NULL,
  `longitude` varchar(20) DEFAULT NULL,
  `locationDesc` varchar(50) DEFAULT NULL,
  `course` int(3) DEFAULT NULL,
  `speed` int(3) DEFAULT NULL,
  `distance` int(3) DEFAULT NULL,
  `airTemp` int(3) DEFAULT NULL,
  `Precip` int(3) DEFAULT NULL,
  `pressure` int(3) DEFAULT NULL,
  `waveHeight` int(3) DEFAULT NULL,
  `seaTemp` int(3) DEFAULT NULL,
  `windDirection` int(3) DEFAULT NULL,
  `windSpeed` int(3) DEFAULT NULL,
  `reefMain` tinyint(1) DEFAULT NULL,
  `reefGenoa` tinyint(1) DEFAULT NULL,
  `sailOther` varchar(30) DEFAULT NULL,
  `observations` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=ascii AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `maintenancelog`
--

CREATE TABLE IF NOT EXISTS `maintenancelog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `TaskName` varchar(30) NOT NULL,
  `EquipmentID` int(10) DEFAULT NULL,
  `Date` date NOT NULL,
  `EquipmentHours` int(20) DEFAULT NULL,
  `Notes` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=ascii AUTO_INCREMENT=7 ;

--
-- Dumping data for table `maintenancelog`
--

INSERT INTO `maintenancelog` (`id`, `TaskName`, `EquipmentID`, `Date`, `EquipmentHours`, `Notes`) VALUES
(1, 'Oil change', 1, '2012-06-17', 356, 'Notes go here'),
(2, 'Change air filter', 4, '2012-06-17', 0, 'Replaced with a cheap-o filter from Walmart'),
(3, 'Oil change', 1, '2012-06-17', 700, ''),
(4, 'Oil change', 1, '2012-06-17', 700, ''),
(5, 'Eat breakfast', 1, '2012-06-17', 0, ''),
(6, 'Eat breakfast', 1, '2012-06-17', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `tasksrecurring`
--

CREATE TABLE IF NOT EXISTS `tasksrecurring` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `TaskName` varchar(30) NOT NULL,
  `EquipmentID` int(10) DEFAULT NULL,
  `IntervalType` enum('hours','days') DEFAULT NULL,
  `LastDate` date DEFAULT NULL,
  `LastHours` int(6) DEFAULT NULL,
  `IntervalHours` int(5) DEFAULT NULL,
  `IntervalDays` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=ascii AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tasksrecurring`
--

INSERT INTO `tasksrecurring` (`id`, `TaskName`, `EquipmentID`, `IntervalType`, `LastDate`, `LastHours`, `IntervalHours`, `IntervalDays`) VALUES
(1, 'Eat breakfast', 1, 'days', '2012-06-16', 322, 0, 1),
(2, 'Mow the lawn', 0, 'days', '2012-06-01', 30, 15, 7),
(3, 'Change air filter', 4, 'days', '2012-01-01', 0, 0, 30),
(4, 'Oil change', 1, 'hours', '2011-12-01', 315, 300, 0),
(5, 'Oil change', 2, 'hours', '0000-00-00', 315, 300, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
