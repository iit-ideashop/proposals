-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2013 at 10:16 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `deans`
--

INSERT INTO `deans` (`id`, `deanName`, `school`, `deanEmail`) VALUES
(1, 'Bart Dworak', 'PHP School of ITMO', 'bdworak@hawk.iit.edu'),
(2, 'Rima Kuprys', 'IPRO School of fun and Games', 'rima@iit.edu'),
(3, 'Ray Trygstadt', 'School of Applied Technology', 'rkuprys@iit.edu');

-- --------------------------------------------------------

--
-- Table structure for table `disciplines`
--

CREATE TABLE IF NOT EXISTS `disciplines` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `disciplineName` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `disciplines`
--

INSERT INTO `disciplines` (`id`, `disciplineName`) VALUES
(1, 'ITM'),
(2, 'INTM'),
(3, 'Physics'),
(4, 'Math'),
(5, 'Computer Science'),
(6, 'Computer Engineering'),
(7, 'Electrical Engineering');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `proposals`
--

INSERT INTO `proposals` (`ID`, `Instructor`, `InstructorEmail`, `CoInstructor`, `CoInstructorEmail`, `Sponsor`, `ApprovingDean`, `Disciplines`, `Title`, `Problem`, `Objective`, `Approach`, `Semester`, `Days`, `Time`, `CourseNumber`, `OwnerID`, `status`) VALUES
(1, 'Bart Dworak', 'bdworak@hawk.iit.edu', 'Emanual.pdf', 'emanual@iit.pdf', 'Rima K.', 1, 'a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;i:4;i:7;}', 'Building better communities', 'Tasks are often blah blah blah', 'Blah blah', 'Blah blah blah', 'SPRING2014', 'a:0:{}', 'Evening', 0, 1, 0),
(2, 'Bart Dworak', 'bdworak@hawk.iit.edu', 'Emanual.pdf', 'emanual@iit.pdf', 'Rima K.', 1, 'a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:7;}', 'Why the coding cave needs more TV''s', 'lsdkmfsldkm', 'sdlfkmsfkldm', 'sldkfmsklm', 'SPRING2014', 'a:0:{}', 'Afternoon', 0, 1, 2),
(3, 'Bart Dworak', 'bdworak@hawk.iit.edu', 'Emanual.pdf', 'emanual@iit.pdf', 'Rima K.', 1, 'a:3:{i:0;i:2;i:1;i:4;i:2;i:6;}', 'Bart is awesome, no?', 'lfksmsdkl', 'lsdkfsldkfm', 'vlskmdflkmf', 'SPRING2015', 'a:0:{}', 'Afternoon', 0, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `proposals_control`
--

CREATE TABLE IF NOT EXISTS `proposals_control` (
  `proposalID` int(9) NOT NULL AUTO_INCREMENT,
  `lastRevision` int(9) NOT NULL,
  PRIMARY KEY (`proposalID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `proposals_control`
--

INSERT INTO `proposals_control` (`proposalID`, `lastRevision`) VALUES
(1, 1),
(2, 1),
(3, 1);

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
