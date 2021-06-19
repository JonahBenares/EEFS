-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2018 at 03:25 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_filing`
--

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE IF NOT EXISTS `company` (
`company_id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`company_id`, `company_name`) VALUES
(1, 'Energreen'),
(2, 'CENPRI'),
(3, 'SPC'),
(4, 'Power One'),
(5, 'Calapan Power'),
(6, 'Sta. Isabel Power');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE IF NOT EXISTS `department` (
`department_id` int(11) NOT NULL,
  `department_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `department_name`) VALUES
(1, 'Procurement'),
(2, 'IT'),
(3, 'HR'),
(4, 'Admin'),
(5, 'Finance'),
(6, 'Trading'),
(7, 'Billing');

-- --------------------------------------------------------

--
-- Table structure for table `document_attach`
--

CREATE TABLE IF NOT EXISTS `document_attach` (
`attach_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `attach_file` varchar(255) DEFAULT NULL,
  `attach_remarks` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `document_attach`
--

INSERT INTO `document_attach` (`attach_id`, `document_id`, `attach_file`, `attach_remarks`) VALUES
(4, 1, 'test subject_11.jpg', ''),
(5, 1, 'test subject_12.xlsx', ''),
(8, 3, 'aaaa_31.jpg', ''),
(11, 4, 'Desert_2018-05-15.jpg', 'Via Email');

-- --------------------------------------------------------

--
-- Table structure for table `document_info`
--

CREATE TABLE IF NOT EXISTS `document_info` (
`document_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL DEFAULT '0',
  `department_id` int(11) NOT NULL,
  `logged_date` varchar(255) DEFAULT NULL,
  `document_date` varchar(255) DEFAULT NULL,
  `location_id` int(11) NOT NULL DEFAULT '0',
  `subject` text NOT NULL,
  `copy_type` varchar(100) DEFAULT NULL,
  `confidential` varchar(15) DEFAULT NULL,
  `sender_company` varchar(150) DEFAULT NULL,
  `sender_person` varchar(255) DEFAULT NULL,
  `addressee_company` varchar(150) DEFAULT NULL,
  `addressee_person` varchar(255) DEFAULT NULL,
  `addressee` varchar(255) DEFAULT NULL,
  `signatory` varchar(255) DEFAULT NULL,
  `remarks` text NOT NULL,
  `email_attach` int(11) NOT NULL DEFAULT '0',
  `email_sender` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `document_info`
--

INSERT INTO `document_info` (`document_id`, `type_id`, `user_id`, `company_id`, `department_id`, `logged_date`, `document_date`, `location_id`, `subject`, `copy_type`, `confidential`, `sender_company`, `sender_person`, `addressee_company`, `addressee_person`, `addressee`, `signatory`, `remarks`, `email_attach`, `email_sender`) VALUES
(1, 2, 4, 2, 3, '2018-05-15 09:54:01', '2018-05-15', 5, 'test subject', 'Original', 'Yes', '', '', '', '', NULL, 'Syndey Sinoro', 'test remarkds', 0, NULL),
(3, 2, 1, 1, 4, '2018-05-15 14:22:09', '2018-05-15', 1, 'aaaa', 'Original', 'Yes', '', '', '', '', NULL, '', '', 0, NULL),
(4, 6, 1, 0, 4, '2018-05-17 08:45:44', '2018-05-15', 6, 'test confidential', 'Original', 'Yes', '', '', '', '', NULL, '', 'test IT admin_x000D_\r\n', 1, ' Jonah Faye Benares'),
(5, 0, 3, 0, 0, '2018-05-17 08:52:39', '2018-05-17', 0, 'test test test', NULL, 'No', NULL, NULL, NULL, NULL, NULL, NULL, 'hello world! from manager_x000D_\n', 1, ' Jonah Faye Benares');

-- --------------------------------------------------------

--
-- Table structure for table `document_location`
--

CREATE TABLE IF NOT EXISTS `document_location` (
`location_id` int(11) NOT NULL,
  `location_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `document_location`
--

INSERT INTO `document_location` (`location_id`, `location_name`) VALUES
(1, 'drawer 1'),
(2, 'drawer 2'),
(3, 'drawer 3'),
(4, 'drawer 4'),
(5, 'drawer 5'),
(6, '');

-- --------------------------------------------------------

--
-- Table structure for table `document_type`
--

CREATE TABLE IF NOT EXISTS `document_type` (
`type_id` int(11) NOT NULL,
  `type_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `document_type`
--

INSERT INTO `document_type` (`type_id`, `type_name`) VALUES
(1, 'Receipt'),
(2, 'Contract'),
(3, 'word document'),
(5, 'Resume'),
(6, '');

-- --------------------------------------------------------

--
-- Table structure for table `shared_document`
--

CREATE TABLE IF NOT EXISTS `shared_document` (
`share_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shared_document`
--

INSERT INTO `shared_document` (`share_id`, `document_id`, `user_id`) VALUES
(16, 3, 3),
(17, 3, 4),
(18, 4, 4),
(19, 4, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`user_id` int(11) NOT NULL,
  `usertype_id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `initial` int(11) NOT NULL DEFAULT '0',
  `status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `usertype_id`, `username`, `password`, `fullname`, `initial`, `status`) VALUES
(1, 1, 'admin', 'admin', 'admin', 1, 'Active'),
(2, 3, 'staff', 'staff', 'staff', 1, 'Active'),
(3, 2, 'manager', 'manager', 'manager', 1, 'Active'),
(4, 2, 'manager1', 'manager1', 'Manager 1', 1, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `usertype`
--

CREATE TABLE IF NOT EXISTS `usertype` (
`usertype_id` int(11) NOT NULL,
  `usertype_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usertype`
--

INSERT INTO `usertype` (`usertype_id`, `usertype_name`) VALUES
(1, 'Admin'),
(2, 'Manager'),
(3, 'Staff');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company`
--
ALTER TABLE `company`
 ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
 ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `document_attach`
--
ALTER TABLE `document_attach`
 ADD PRIMARY KEY (`attach_id`), ADD KEY `document_id` (`document_id`);

--
-- Indexes for table `document_info`
--
ALTER TABLE `document_info`
 ADD PRIMARY KEY (`document_id`), ADD KEY `type_id` (`type_id`,`user_id`,`department_id`);

--
-- Indexes for table `document_location`
--
ALTER TABLE `document_location`
 ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `document_type`
--
ALTER TABLE `document_type`
 ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `shared_document`
--
ALTER TABLE `shared_document`
 ADD PRIMARY KEY (`share_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`user_id`), ADD KEY `usertype_id` (`usertype_id`);

--
-- Indexes for table `usertype`
--
ALTER TABLE `usertype`
 ADD PRIMARY KEY (`usertype_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `document_attach`
--
ALTER TABLE `document_attach`
MODIFY `attach_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `document_info`
--
ALTER TABLE `document_info`
MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `document_location`
--
ALTER TABLE `document_location`
MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `document_type`
--
ALTER TABLE `document_type`
MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `shared_document`
--
ALTER TABLE `shared_document`
MODIFY `share_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `usertype`
--
ALTER TABLE `usertype`
MODIFY `usertype_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
