-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2013 at 12:29 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `proposals`
--

-- --------------------------------------------------------

--
-- Table structure for table `deans`
--

CREATE TABLE IF NOT EXISTS `deans` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `deanName` varchar(255) NOT NULL,
  `school` varchar(255) NOT NULL,
  `deanEmail` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `disciplines`
--

CREATE TABLE IF NOT EXISTS `disciplines` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `disciplineName` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `proposals`
--

CREATE TABLE IF NOT EXISTS `proposals` (
  `ID` int(9) NOT NULL AUTO_INCREMENT,
  `Instructor` varchar(255) NOT NULL,
  `InstructorEmail` varchar(255) NOT NULL,
  `CoInstructor` varchar(255) NOT NULL,
  `CoInstructorEmail` varchar(255) NOT NULL,
  `Sponsor` varchar(255) NOT NULL,
  `ApprovingDean` int(9) NOT NULL,
  `Disciplines` text NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Problem` text NOT NULL,
  `Objective` text NOT NULL,
  `Approach` text NOT NULL,
  `Semester` varchar(255) NOT NULL,
  `Days` text NOT NULL,
  `Time` varchar(255) NOT NULL,
  `CourseNumber` int(3) NOT NULL,
  `OwnerID` int(9) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `proposals_control`
--

CREATE TABLE IF NOT EXISTS `proposals_control` (
  `proposalID` int(9) NOT NULL AUTO_INCREMENT,
  `lastRevision` int(9) NOT NULL,
  PRIMARY KEY (`proposalID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `FName` varchar(255) NOT NULL,
  `LName` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Level` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `FName`, `LName`, `Username`, `Password`, `Email`, `Level`) VALUES
(1, 'Bart', 'Dworak', 'bdworak', 'e10adc3949ba59abbe56e057f20f883e', 'bdworak@hawk.iit.edu', 9);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
