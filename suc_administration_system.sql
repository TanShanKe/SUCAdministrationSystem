-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2023 at 08:38 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suc_administration_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `administration_office_review`
--

CREATE TABLE `administration_office_review` (
  `administrationOfficeReviewID` varchar(20) NOT NULL,
  `approveOrDisapprove` tinyint(1) NOT NULL,
  `dateOfApprovalOrDisapproval` date NOT NULL,
  `comment` varchar(255) NOT NULL,
  `administratorSignature` tinyint(1) NOT NULL,
  `reviewStatus` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE `administrator` (
  `administratorID` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `lecturer`
--

CREATE TABLE `lecturer` (
  `lecturerID` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `resumption_of_studies_record`
--

CREATE TABLE `resumption_of_studies_record` (
  `yearOfDeferment` int(4) NOT NULL,
  `semOfDeferment` int(1) NOT NULL,
  `yearOfResumption` int(4) NOT NULL,
  `semOfResumption` int(1) NOT NULL,
  `resumptionID` varchar(20) NOT NULL,
  `applicantID` varchar(20) NOT NULL,
  `applicantSignature` tinyint(1) NOT NULL,
  `applicationDate` date NOT NULL,
  `deanOrHeadAcknowledge` tinyint(1) NOT NULL,
  `deanOrHeadComment` varchar(255) NOT NULL,
  `deanOrHeadSignature` tinyint(1) NOT NULL,
  `deanOrHeadAcknowledgeDate` date NOT NULL,
  `afoAcknowledge` tinyint(1) NOT NULL,
  `afoComment` varchar(255) NOT NULL,
  `afoSignature` tinyint(1) NOT NULL,
  `afoAcknowledgeDate` date NOT NULL,
  `aaroAcknowledge` tinyint(1) NOT NULL,
  `aaroComment` varchar(255) NOT NULL,
  `aaroSignature` tinyint(1) NOT NULL,
  `aaroAcknowledgeDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `resumption_of_studies_record`
--

INSERT INTO `resumption_of_studies_record` (`yearOfDeferment`, `semOfDeferment`, `yearOfResumption`, `semOfResumption`, `resumptionID`, `applicantID`, `applicantSignature`, `applicationDate`, `deanOrHeadAcknowledge`, `deanOrHeadComment`, `deanOrHeadSignature`, `deanOrHeadAcknowledgeDate`, `afoAcknowledge`, `afoComment`, `afoSignature`, `afoAcknowledgeDate`, `aaroAcknowledge`, `aaroComment`, `aaroSignature`, `aaroAcknowledgeDate`) VALUES
(2019, 1, 2022, 1, '2308005', 'B210157B', 1, '2022-01-16', 1, '', 1, '0000-00-00', 1, '', 1, '0000-00-00', 1, '', 1, '0000-00-00'),
(2021, 1, 2023, 3, '2308006', 'B210157B', 1, '2023-08-16', 0, 'Hello World', 1, '0000-00-00', 0, 'Happy Go Lucky', 1, '0000-00-00', 0, 'Today is a good day!', 1, '0000-00-00'),
(2022, 1, 2022, 2, '2308008', 'B210157B', 1, '2022-08-16', 0, '', 0, '0000-00-00', 0, '', 0, '0000-00-00', 0, '', 0, '0000-00-00'),
(2020, 1, 2022, 1, '2308010', 'B220157B', 1, '2022-08-16', 0, '', 1, '0000-00-00', 0, '', 1, '0000-00-00', 0, '', 1, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `studentID` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `batchNo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`studentID`, `name`, `batchNo`) VALUES
('B210157B', 'Tan Shan Ke', 'BoS21-B1'),
('B220157B', 'Emily Tee', 'BoS22-B1');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subjectCode` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `credit` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subjectCode`, `name`, `credit`) VALUES
('BTIS3103', 'Final Year Project I', 4),
('LMPU3421', 'Life 30', 2);

-- --------------------------------------------------------

--
-- Table structure for table `subject_registration_record`
--

CREATE TABLE `subject_registration_record` (
  `registerID` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `dateOfApplication` date NOT NULL,
  `applicantSignature` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `position` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `password`, `position`) VALUES
('A1234', '1234', 'administrator'),
('B210157B', '1234', 'student'),
('B220157B', '5678', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`administratorID`);

--
-- Indexes for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD PRIMARY KEY (`lecturerID`);

--
-- Indexes for table `resumption_of_studies_record`
--
ALTER TABLE `resumption_of_studies_record`
  ADD PRIMARY KEY (`resumptionID`),
  ADD KEY `applicantID` (`applicantID`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`studentID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `resumption_of_studies_record`
--
ALTER TABLE `resumption_of_studies_record`
  ADD CONSTRAINT `resumption_of_studies_record_ibfk_1` FOREIGN KEY (`applicantID`) REFERENCES `student` (`studentID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
