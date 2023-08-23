-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 23, 2023 at 08:01 PM
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
-- Table structure for table `administration_office`
--

CREATE TABLE `administration_office` (
  `administrationOfficeID` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `administration_office`
--

INSERT INTO `administration_office` (`administrationOfficeID`, `name`) VALUES
('AARO', 'Academic Affairs, Admission & Registration Office'),
('AFO', 'Account & Finance Office');

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
  `administratorID` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `administrationOfficeID` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`administratorID`, `name`, `administrationOfficeID`) VALUES
('A0001', 'So Yong Quay', 'AARO'),
('A0002', 'Jackson', 'AARO'),
('A0005', 'Sally', 'AFO'),
('A0006', 'Adeline', 'AFO');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `departmentID` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `facultyID` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`departmentID`, `name`, `facultyID`) VALUES
('DCS', 'Department of Computer Science', 'FEIT'),
('DEE', 'Department of Electrical & Electronics Engineering', 'FEIT');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `facultyID` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`facultyID`, `name`) VALUES
('FEIT', 'Faculty Of Engineering & Information Technology');

-- --------------------------------------------------------

--
-- Table structure for table `lecturer`
--

CREATE TABLE `lecturer` (
  `lecturerID` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `departmentID` varchar(20) NOT NULL,
  `position` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lecturer`
--

INSERT INTO `lecturer` (`lecturerID`, `name`, `departmentID`, `position`) VALUES
('L0001', 'So Yong Quay', 'DCS', 'Lecturer'),
('L0002', 'Shaffika Bte Mohd Suhaimi', 'DCS', 'HOD'),
('L0003', 'Feldicia', 'DEE', 'HOD');

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
  `deanOrHeadID` varchar(20) DEFAULT NULL,
  `deanOrHeadAcknowledgeDate` date NOT NULL,
  `afoAcknowledge` tinyint(1) NOT NULL,
  `afoComment` varchar(255) NOT NULL,
  `afoSignature` tinyint(1) NOT NULL,
  `afoID` varchar(20) DEFAULT NULL,
  `afoAcknowledgeDate` date NOT NULL,
  `aaroAcknowledge` tinyint(1) NOT NULL,
  `aaroComment` varchar(255) NOT NULL,
  `aaroSignature` tinyint(1) NOT NULL,
  `aaroID` varchar(20) DEFAULT NULL,
  `aaroAcknowledgeDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `resumption_of_studies_record`
--

INSERT INTO `resumption_of_studies_record` (`yearOfDeferment`, `semOfDeferment`, `yearOfResumption`, `semOfResumption`, `resumptionID`, `applicantID`, `applicantSignature`, `applicationDate`, `deanOrHeadAcknowledge`, `deanOrHeadComment`, `deanOrHeadSignature`, `deanOrHeadID`, `deanOrHeadAcknowledgeDate`, `afoAcknowledge`, `afoComment`, `afoSignature`, `afoID`, `afoAcknowledgeDate`, `aaroAcknowledge`, `aaroComment`, `aaroSignature`, `aaroID`, `aaroAcknowledgeDate`) VALUES
(2020, 2, 2021, 2, '2101002', 'B210157B', 1, '2021-01-16', 0, 'Not at all', 1, 'L0003', '2023-08-23', 0, 'Not currently', 1, 'A0006', '2023-08-23', 0, 'Should be next year', 1, 'A0002', '2023-08-23'),
(2020, 3, 2021, 3, '2108003', 'B210158B', 1, '2021-08-20', 0, '', 0, NULL, '0000-00-00', 1, 'ok', 1, 'A0005', '2023-08-23', 1, 'can Â  Â  ', 1, 'A0001', '2023-08-23'),
(2021, 1, 2021, 3, '2108004', 'B210158B', 1, '2021-08-22', 0, '', 0, NULL, '0000-00-00', 0, '', 0, NULL, '0000-00-00', 0, '', 0, NULL, '0000-00-00'),
(2020, 1, 2021, 1, '2112001', 'B210157B', 1, '2020-12-23', 1, 'great', 1, 'L0002', '2023-08-23', 1, 'ok', 1, 'A0005', '2023-08-23', 1, 'Good ', 1, 'A0001', '2023-08-23'),
(2021, 3, 2022, 2, '2201005', 'B210157B', 1, '2022-01-03', 0, '', 0, NULL, '0000-00-00', 0, '', 0, NULL, '0000-00-00', 0, '', 0, NULL, '0000-00-00'),
(2022, 1, 2022, 3, '2209006', 'B210157B', 1, '2022-09-21', 0, '', 0, NULL, '0000-00-00', 0, '', 0, NULL, '0000-00-00', 0, '', 0, NULL, '0000-00-00'),
(2022, 1, 2023, 1, '2212007', 'B210158B', 1, '2022-12-24', 0, '', 0, NULL, '0000-00-00', 0, '', 0, NULL, '0000-00-00', 0, '', 0, NULL, '0000-00-00'),
(2023, 1, 2023, 3, '2308008', 'B210158B', 1, '2023-08-24', 0, 'no lah ', 1, 'L0002', '2023-08-24', 0, 'nono', 1, 'A0005', '2023-08-24', 0, 'cannot', 1, 'A0002', '2023-08-24'),
(2023, 2, 2024, 1, '2308009', 'B210157B', 1, '2023-08-24', 1, 'sure', 1, 'L0002', '2023-08-24', 1, 'ofcourse', 1, 'A0005', '2023-08-24', 1, 'can lah', 1, 'A0001', '2023-08-24'),
(2023, 1, 2024, 1, '2308010', 'B210157B', 1, '2023-08-24', 0, 'dun dream', 1, 'L0003', '2023-08-24', 0, 'oh nooo', 1, 'A0006', '2023-08-24', 0, 'never', 1, 'A0002', '2023-08-24'),
(2023, 2, 2024, 2, '2308011', 'B210157B', 1, '2023-08-24', 1, 'boleh', 1, 'L0002', '2023-08-24', 0, 'noooo lehh', 1, 'A0006', '2023-08-24', 1, 'can', 1, 'A0002', '2023-08-24'),
(2022, 3, 2023, 3, '2308012', 'B210158B', 1, '2023-08-24', 1, 'yesss', 1, 'L0003', '2023-08-24', 1, 'haha can', 1, 'A0006', '2023-08-24', 1, 'ok can der', 1, 'A0001', '2023-08-24'),
(2023, 2, 2024, 2, '2308014', 'B210157B', 1, '2023-08-24', 0, '', 0, NULL, '0000-00-00', 0, '', 0, NULL, '0000-00-00', 0, '', 0, NULL, '0000-00-00'),
(2022, 1, 2023, 3, '2308015', 'B210158B', 1, '2023-08-24', 0, '', 0, NULL, '0000-00-00', 0, '', 0, NULL, '0000-00-00', 0, '', 0, NULL, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `resumption_temporary`
--

CREATE TABLE `resumption_temporary` (
  `id` varchar(20) NOT NULL,
  `aaroAcknowledge` tinyint(1) DEFAULT NULL,
  `aaroComment` varchar(255) NOT NULL,
  `deanOrHeadAcknowledge` tinyint(1) DEFAULT NULL,
  `deanOrHeadComment` varchar(255) NOT NULL,
  `afoAcknowledge` tinyint(1) DEFAULT NULL,
  `afoComment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `resumption_temporary`
--

INSERT INTO `resumption_temporary` (`id`, `aaroAcknowledge`, `aaroComment`, `deanOrHeadAcknowledge`, `deanOrHeadComment`, `afoAcknowledge`, `afoComment`) VALUES
('2308014', 0, 'not sure how lehh', 0, 'belllooo', 0, 'dunno worr'),
('2308015', NULL, '', 1, 'maybe', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `studentID` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `batchNo` varchar(20) NOT NULL,
  `departmentID` varchar(20) NOT NULL,
  `programme` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`studentID`, `name`, `batchNo`, `departmentID`, `programme`) VALUES
('B210157B', 'Tan Shan Ke', 'BoS21-B1', 'DCS', 'Bachelor of Software Engineering'),
('B210158B', 'Emily Tee', 'BoEE21-B1', 'DEE', 'Bachelor of Electronic Engineering with Honours');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subjectCode` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `credit` int(1) NOT NULL,
  `lecturerID` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
('A0001', '0001', 'aaro'),
('A0002', '0002', 'aaro'),
('A0005', '0005', 'afo'),
('A0006', '0006', 'afo'),
('B210157B', '1234', 'student'),
('B210158B', '5678', 'student'),
('L0002', '2234', 'deanOrHod'),
('L0003', '0003', 'deanOrHod');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administration_office`
--
ALTER TABLE `administration_office`
  ADD PRIMARY KEY (`administrationOfficeID`);

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`administratorID`),
  ADD KEY `administrationOfficeID` (`administrationOfficeID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`departmentID`),
  ADD KEY `facultyID` (`facultyID`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`facultyID`);

--
-- Indexes for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD PRIMARY KEY (`lecturerID`),
  ADD KEY `departmentID` (`departmentID`);

--
-- Indexes for table `resumption_of_studies_record`
--
ALTER TABLE `resumption_of_studies_record`
  ADD PRIMARY KEY (`resumptionID`),
  ADD KEY `applicantID` (`applicantID`),
  ADD KEY `aaroID` (`aaroID`),
  ADD KEY `afoID` (`afoID`),
  ADD KEY `deanOrHeadID` (`deanOrHeadID`);

--
-- Indexes for table `resumption_temporary`
--
ALTER TABLE `resumption_temporary`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`studentID`),
  ADD KEY `departmentID` (`departmentID`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subjectCode`),
  ADD KEY `lecturerID` (`lecturerID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `administrator`
--
ALTER TABLE `administrator`
  ADD CONSTRAINT `administrator_ibfk_1` FOREIGN KEY (`administrationOfficeID`) REFERENCES `administration_office` (`administrationOfficeID`);

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`facultyID`) REFERENCES `faculty` (`facultyID`);

--
-- Constraints for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD CONSTRAINT `lecturer_ibfk_1` FOREIGN KEY (`departmentID`) REFERENCES `department` (`departmentID`);

--
-- Constraints for table `resumption_of_studies_record`
--
ALTER TABLE `resumption_of_studies_record`
  ADD CONSTRAINT `resumption_of_studies_record_ibfk_1` FOREIGN KEY (`applicantID`) REFERENCES `student` (`studentID`),
  ADD CONSTRAINT `resumption_of_studies_record_ibfk_2` FOREIGN KEY (`aaroID`) REFERENCES `administrator` (`administratorID`),
  ADD CONSTRAINT `resumption_of_studies_record_ibfk_3` FOREIGN KEY (`afoID`) REFERENCES `administrator` (`administratorID`),
  ADD CONSTRAINT `resumption_of_studies_record_ibfk_4` FOREIGN KEY (`deanOrHeadID`) REFERENCES `lecturer` (`lecturerID`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`departmentID`) REFERENCES `department` (`departmentID`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_ibfk_1` FOREIGN KEY (`lecturerID`) REFERENCES `lecturer` (`lecturerID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
