-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 13, 2013 at 06:58 AM
-- Server version: 5.5.31
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `proposals`
--
CREATE DATABASE IF NOT EXISTS `proposals` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `proposals`;

-- --------------------------------------------------------

--
-- Table structure for table `deans`
--

CREATE TABLE IF NOT EXISTS `deans` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `userID` int(9) NOT NULL COMMENT 'links a dean entry to a user entry in the database',
  `deanName` varchar(255) NOT NULL,
  `school` varchar(255) NOT NULL,
  `deanEmail` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `deans`
--

INSERT INTO `deans` (`id`, `userID`, `deanName`, `school`, `deanEmail`) VALUES
(1, 2, 'Bart Dworak', 'PHP School of ITMO', 'bdworak@hawk.iit.edu'),
(2, 0, 'Rima Kuprys', 'IPRO School of fun and Games', 'rima@iit.edu'),
(3, 0, 'Ray Trygstadt', 'School of Applied Technology', 'rkuprys@iit.edu');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `proposals`
--

INSERT INTO `proposals` (`ID`, `Instructor`, `InstructorEmail`, `CoInstructor`, `CoInstructorEmail`, `Sponsor`, `ApprovingDean`, `Title`, `Problem`, `Objective`, `Approach`, `Semester`, `Days`, `Time`, `CourseNumber`, `OwnerID`, `status`) VALUES
(1, 'Bart Dworak', 'bdworak@hawk.iit.edu', 'Emanual.pdf', 'bdworak@hawk.iit.edu', 'Rima K.', 1, 'Building better communities', 'Tasks are often blah blah blah', 'Blah blah', 'Blah blah blah', 'SPRING2014', 'a:0:{}', 'Evening', 0, 1, 5),
(2, 'Bart Dworak', 'bdworak@hawk.iit.edu', 'Emanual.pdf', 'bdworak@hawk.iit.edu', 'Rima K.', 1, 'Why the coding cave needs more TV''s', '16 tv''s instead of just 1.', 'we are going to spend alot of money to do fun things.', 'sldkfmsklm', 'SPRING2014', 'a:5:{i:1;s:2:"on";i:2;s:2:"on";i:3;s:2:"on";i:4;s:2:"on";i:5;s:2:"on";}', 'Afternoon', 0, 1, 5),
(3, 'Bart Dworak', 'bdworak@hawk.iit.edu', 'Emanual.pdf', 'emanual@iit.pdf', 'Rima K.', 1, 'Bart is awesome, no?', 'lfksmsdkl', 'lsdkfsldkfm', 'vlskmdflkmf', 'SPRING2015', 'a:0:{}', 'Afternoon', 0, 1, 6),
(4, 'bart dworak', 'bdworak@hawk.iit.edu', 'bart dworak', 'bdworak@hawk.iit.edu', 'Emmanuel MoneyBags Marcha', 1, 'This is a test', 'This is an update to my test.', 'this is an updated objective version 1.5', '', 'SPRING2014', 'a:1:{i:0;i:1;}', 'Afternoon', 0, 1, 2),
(5, 'instructor', 'instructor@gmaodm.com', 'coinstructor', 'instructor@gmaodm.com', 'The Sponsor', 1, 'Test Proposal #1', 'The problem or issue idasdals', 'objectives...', 'approach M T TH Morning FALL 2014', 'FALL2014', 'a:3:{i:1;s:2:"on";i:3;s:2:"on";i:5;s:2:"on";}', 'Morning', 0, 1, 5),
(6, 'instrutmf', 'instructor@gmaodm.com', 'coinstructor', 'instructor@gmaodm.com', 'the sponsor', 1, 'My new proposal', 'This is a test proposal just testing', '12345', 'lkmlsdkfm', 'SUMMER2015', 'a:1:{i:3;s:2:"on";}', 'Evening', 0, 2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `proposals_control`
--

CREATE TABLE IF NOT EXISTS `proposals_control` (
  `proposalID` int(9) NOT NULL AUTO_INCREMENT,
  `lastRevision` int(9) NOT NULL,
  PRIMARY KEY (`proposalID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `proposals_control`
--

INSERT INTO `proposals_control` (`proposalID`, `lastRevision`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `proposal_comments`
--

CREATE TABLE IF NOT EXISTS `proposal_comments` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `comment` text NOT NULL,
  `timestamp` int(12) NOT NULL,
  `userID` int(9) NOT NULL,
  `proposalID` int(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `proposal_comments`
--

INSERT INTO `proposal_comments` (`id`, `comment`, `timestamp`, `userID`, `proposalID`) VALUES
(4, 'This is a great proposal', 1378859775, 2, 6),
(5, 'The committee approves this request.', 1378860112, 3, 6),
(6, 'Bad Proposal', 1378917477, 2, 2),
(7, '', 1378917566, 2, 2),
(8, 'This is fine.', 1378919104, 2, 2),
(9, 'This is not going to happen. add more tv''s''s''s''s''s''s.s''.s''.s''.s''.s''.s', 1378919278, 3, 2),
(10, 'yes yes i approve of the Tv''s''s''s''s''', 1378919512, 2, 2),
(11, 'nono, 16 TV;''s', 1378919531, 3, 2),
(12, 'This is great!', 1378932592, 2, 5),
(13, 'Committee approved!!!', 1378932694, 3, 5),
(14, 'Great Proposal. Let''s schedule it up.', 1378954724, 3, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `FName`, `LName`, `Username`, `Password`, `Email`, `Level`) VALUES
(1, 'Bart', 'Dworak', 'bdworak', 'e10adc3949ba59abbe56e057f20f883e', 'bdworak@hawk.iit.edu', 9),
(2, 'Mike', 'Dean', 'dean', 'e10adc3949ba59abbe56e057f20f883e', 'dean@laksmda.com', 2),
(3, 'Mike', 'Committee', 'cmt', 'e10adc3949ba59abbe56e057f20f883e', 'cmt@ideashop-iit.org', 3),
(4, 'Admin', 'J', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin@ideashop-iit.org', 9);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
