-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2023 at 01:28 PM
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
('AFO', 'Account & Finance Office'),
('ISO', 'International Student Office'),
('LIB', 'Library'),
('SAO', 'Student Affairs Office'),
('SRO', 'Student Residence Office');

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
('A0003', 'Pang Zhe Yan', 'AARO'),
('A0005', 'Sally', 'AFO'),
('A0006', 'Adeline', 'AFO'),
('B0001', 'Ong Yan Bin', 'LIB'),
('I0001', 'Tee Ling Yan', 'ISO'),
('S0001', 'Lim Ju Heng', 'SAO'),
('S0002', 'Gan Yee Xin', 'SAO'),
('S0005', 'Soo Chee Ting', 'SRO');

-- --------------------------------------------------------

--
-- Table structure for table `change_class_documentalproof`
--

CREATE TABLE `change_class_documentalproof` (
  `changeClassID` varchar(20) NOT NULL,
  `fileName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `change_class_record`
--

CREATE TABLE `change_class_record` (
  `changeClassID` varchar(20) NOT NULL,
  `typeOfChange` tinyint(1) NOT NULL,
  `subjectCode` varchar(20) NOT NULL,
  `lecturerID` varchar(255) NOT NULL,
  `existingDate` date DEFAULT NULL,
  `existingDay` varchar(20) DEFAULT NULL,
  `existingTime` time NOT NULL,
  `hour` int(11) NOT NULL,
  `existingVenue` varchar(20) NOT NULL,
  `newDate` date DEFAULT NULL,
  `newDay` varchar(20) DEFAULT NULL,
  `newTime` time NOT NULL,
  `newVenue` varchar(20) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `applicantID` varchar(255) NOT NULL,
  `applicationDate` date NOT NULL,
  `applicantSignature` tinyint(1) NOT NULL,
  `deanOrHeadAcknowledge` tinyint(1) NOT NULL,
  `deanOrHeadComment` varchar(255) NOT NULL,
  `deanOrHeadAcknowledgeDate` date DEFAULT NULL,
  `deanOrHeadSignature` tinyint(1) NOT NULL,
  `deanOrHeadID` varchar(20) DEFAULT NULL,
  `aaroAcknowledge` tinyint(1) NOT NULL,
  `aaroComment` varchar(255) NOT NULL,
  `aaroAcknowledgeDate` date DEFAULT NULL,
  `aaroSignature` tinyint(1) NOT NULL,
  `aaroID` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `change_class_temporary`
--

CREATE TABLE `change_class_temporary` (
  `id` varchar(20) NOT NULL,
  `deanOrHeadAcknowledge` tinyint(1) DEFAULT NULL,
  `deanOrHeadComment` varchar(255) NOT NULL,
  `aaroAcknowledge` tinyint(1) DEFAULT NULL,
  `aaroComment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `deferment_record`
--

CREATE TABLE `deferment_record` (
  `defermentID` varchar(20) NOT NULL,
  `category` tinyint(1) NOT NULL,
  `reasons` varchar(255) NOT NULL,
  `applicantID` varchar(20) NOT NULL,
  `applicantSignature` tinyint(1) NOT NULL,
  `applicationDate` date NOT NULL,
  `isoID` varchar(20) NOT NULL,
  `isoSignature` tinyint(1) NOT NULL,
  `isoDate` date DEFAULT NULL,
  `isoRemarks` varchar(255) NOT NULL,
  `scholarship` tinyint(1) DEFAULT NULL,
  `saoRemarks` varchar(255) NOT NULL,
  `saoID` varchar(20) NOT NULL,
  `saoSignature` tinyint(1) NOT NULL,
  `saoDate` date DEFAULT NULL,
  `counselingRemarks` varchar(255) NOT NULL,
  `counselingID` varchar(20) NOT NULL,
  `counselingSignature` tinyint(1) NOT NULL,
  `counselingDate` date DEFAULT NULL,
  `returnedDocument` tinyint(1) DEFAULT NULL,
  `sroRemarks` varchar(255) NOT NULL,
  `sroID` varchar(20) NOT NULL,
  `sroSignature` tinyint(1) NOT NULL,
  `sroDate` date DEFAULT NULL,
  `overdueBooks` tinyint(1) DEFAULT NULL,
  `libRemarks` varchar(255) NOT NULL,
  `libID` varchar(20) NOT NULL,
  `libSignature` tinyint(1) NOT NULL,
  `libDate` date DEFAULT NULL,
  `feesOverdue` tinyint(1) DEFAULT NULL,
  `fees` int(11) NOT NULL,
  `afoRemarks` varchar(255) NOT NULL,
  `returnedDeposit` tinyint(1) DEFAULT NULL,
  `afoID` varchar(20) NOT NULL,
  `afoSignature` tinyint(1) NOT NULL,
  `afoDate` date DEFAULT NULL,
  `hodRemarks` varchar(255) NOT NULL,
  `hodID` varchar(20) NOT NULL,
  `hodSignature` tinyint(1) NOT NULL,
  `hodDate` date DEFAULT NULL,
  `aaroRemarks` varchar(255) NOT NULL,
  `aaroID` varchar(20) NOT NULL,
  `aaroSignature` tinyint(1) NOT NULL,
  `aaroDate` date DEFAULT NULL,
  `registrarRemarks` varchar(255) NOT NULL,
  `registrarDecision` tinyint(1) DEFAULT NULL,
  `registrarID` varchar(20) NOT NULL,
  `registrarSignature` tinyint(1) NOT NULL,
  `registrarDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `deferment_record`
--

INSERT INTO `deferment_record` (`defermentID`, `category`, `reasons`, `applicantID`, `applicantSignature`, `applicationDate`, `isoID`, `isoSignature`, `isoDate`, `isoRemarks`, `scholarship`, `saoRemarks`, `saoID`, `saoSignature`, `saoDate`, `counselingRemarks`, `counselingID`, `counselingSignature`, `counselingDate`, `returnedDocument`, `sroRemarks`, `sroID`, `sroSignature`, `sroDate`, `overdueBooks`, `libRemarks`, `libID`, `libSignature`, `libDate`, `feesOverdue`, `fees`, `afoRemarks`, `returnedDeposit`, `afoID`, `afoSignature`, `afoDate`, `hodRemarks`, `hodID`, `hodSignature`, `hodDate`, `aaroRemarks`, `aaroID`, `aaroSignature`, `aaroDate`, `registrarRemarks`, `registrarDecision`, `registrarID`, `registrarSignature`, `registrarDate`) VALUES
('2312011', 1, 'ok', 'B210158B', 1, '2023-12-17', 'I0001', 1, '2023-12-17', 'ok', 1, 'ok', 'S0001', 1, '2023-12-17', 'ok', 'S0002', 1, '2023-12-17', 1, 'ok', 'S0005', 1, '2023-12-17', 1, 'ok', 'B0001', 1, '2023-12-17', 0, 0, 'done', 1, 'A0005', 1, '2023-12-17', 'ok', 'L0002', 1, '2023-12-17', 'ok', 'A0001', 1, '2023-12-17', 'no', 1, 'A0003', 1, '2023-12-17'),
('2312012', 1, 'sick', 'B210158B', 1, '2023-12-17', 'I0001', 1, '2023-12-17', 'ok', 1, 'done', 'S0001', 1, '2023-12-17', 'done', 'S0002', 1, '2023-12-17', 1, 'done', 'S0005', 1, '2023-12-17', 1, 'done', 'B0001', 1, '2023-12-17', 0, 0, 'ok', 0, 'A0005', 1, '2023-12-17', 'done', 'L0002', 1, '2023-12-17', 'done', 'A0001', 1, '2023-12-17', 'ok', 1, 'A0003', 1, '2023-12-17'),
('2312013', 1, 'sick', 'B210158B', 1, '2023-12-18', 'I0001', 1, '2023-12-18', 'ok', 0, 'no problem', 'S0001', 1, '2023-12-18', 'should be ok', 'S0002', 1, '2023-12-18', 1, 'ok', 'S0005', 1, '2023-12-18', 0, 'ok', 'B0001', 1, '2023-12-18', 1, 200, 'no', 0, 'A0005', 1, '2023-12-18', 'no', 'L0002', 1, '2023-12-18', 'no', 'A0001', 1, '2023-12-18', 'no', 0, 'A0003', 1, '2023-12-18'),
('2312014', 1, 'sick', 'B210158B', 1, '2023-12-18', 'I0001', 1, '2023-12-18', 'done', 0, 'ok', 'S0001', 1, '2023-12-18', 'done', 'S0002', 1, '2023-12-18', 0, 'ok', 'S0005', 1, '2023-12-18', 0, 'ok', 'B0001', 1, '2023-12-18', 0, 0, 'done', 0, 'A0005', 1, '2023-12-18', 'ok', 'L0002', 1, '2023-12-18', 'done', 'A0001', 1, '2023-12-18', 'ok', 1, 'A0003', 1, '2023-12-18'),
('2312022', 1, 'sick', 'B210158B', 1, '2023-12-20', 'I0001', 1, '2023-12-21', 'hi', 1, 'ok', 'S0001', 1, '2023-12-21', 'done', 'S0002', 1, '2023-12-21', 1, 'hello', 'S0005', 1, '2023-12-21', 0, 'good', 'B0001', 1, '2023-12-21', 0, 0, 'ok', 1, 'A0005', 1, '2023-12-21', 'en', 'L0002', 1, '2023-12-21', 'done', 'A0001', 1, '2023-12-21', 'good', 0, 'A0003', 1, '2023-12-21');

-- --------------------------------------------------------

--
-- Table structure for table `deferment_temporary`
--

CREATE TABLE `deferment_temporary` (
  `defermentID` varchar(20) NOT NULL,
  `tempIsoRemarks` varchar(255) NOT NULL,
  `tempScholarship` tinyint(1) DEFAULT NULL,
  `tempSaoRemarks` varchar(255) NOT NULL,
  `tempCounselingRemarks` varchar(255) NOT NULL,
  `tempReturnedDocument` tinyint(1) DEFAULT NULL,
  `tempSroRemarks` varchar(255) NOT NULL,
  `tempOverdueBooks` tinyint(1) DEFAULT NULL,
  `tempLibRemarks` varchar(255) NOT NULL,
  `tempFeesOverdue` tinyint(1) DEFAULT NULL,
  `tempFees` int(11) DEFAULT NULL,
  `tempReturnedDeposit` tinyint(1) DEFAULT NULL,
  `tempAfoRemarks` varchar(255) NOT NULL,
  `tempHodRemarks` varchar(255) NOT NULL,
  `tempAaroRemarks` varchar(255) NOT NULL,
  `tempRegistrarRemarks` varchar(255) NOT NULL,
  `tempRegistrarDecision` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `deferment_temporary`
--

INSERT INTO `deferment_temporary` (`defermentID`, `tempIsoRemarks`, `tempScholarship`, `tempSaoRemarks`, `tempCounselingRemarks`, `tempReturnedDocument`, `tempSroRemarks`, `tempOverdueBooks`, `tempLibRemarks`, `tempFeesOverdue`, `tempFees`, `tempReturnedDeposit`, `tempAfoRemarks`, `tempHodRemarks`, `tempAaroRemarks`, `tempRegistrarRemarks`, `tempRegistrarDecision`) VALUES
('2311008', '', NULL, 'ok', '', NULL, '', NULL, '', 0, 20, 0, 'ok', '', '', '', NULL),
('2311003', '', 0, 'ok', '', NULL, '', NULL, '', NULL, NULL, NULL, '', '', '', '', NULL),
('2311001', '', NULL, '', 'okk', NULL, '', 1, 'ok', 1, 300, 1, 'wait', 'okk', 'okk', 'ok', 0),
('2312011', '', 0, 'ok', '', NULL, '', NULL, '', NULL, NULL, NULL, '', '', '', '', NULL),
('2312013', '', NULL, '', '', 1, 'ok', NULL, '', 1, 200, 0, 'no', '', '', '', NULL),
('2312022', 'hi', NULL, '', 'done', 1, 'hello', NULL, '', 0, 0, 1, 'ok', 'en', '', 'good', 1),
('2312023', '', 0, 'ok', 'no problem', NULL, '', NULL, '', NULL, NULL, NULL, '', '', '', 'haha', 1);

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
-- Table structure for table `document_paymentslip`
--

CREATE TABLE `document_paymentslip` (
  `documentID` varchar(20) NOT NULL,
  `fileName` varchar(255) NOT NULL,
  `counter` int(11) NOT NULL,
  `applicationDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `document_paymentslip`
--

INSERT INTO `document_paymentslip` (`documentID`, `fileName`, `counter`, `applicationDate`) VALUES
('2312002', 'uploads/paymentSlip/65849a3849d00_Payment slip.pdf', 0, NULL),
('2312002', 'uploads/paymentSlip/65849bf1c51a0_Payment slip.pdf', 2, '2023-12-22');

-- --------------------------------------------------------

--
-- Table structure for table `document_record`
--

CREATE TABLE `document_record` (
  `documentID` varchar(20) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `document` varchar(255) NOT NULL,
  `officialReceipt` varchar(255) NOT NULL,
  `applicantID` varchar(20) NOT NULL,
  `applicantSignature` tinyint(1) NOT NULL,
  `applicationDate` date NOT NULL,
  `counter` int(11) NOT NULL,
  `applicationStatus` tinyint(1) DEFAULT NULL,
  `waitingStatus` tinyint(1) DEFAULT NULL,
  `aaroCollectionID` varchar(20) DEFAULT NULL,
  `aaroCollectionDate` date DEFAULT NULL,
  `aaroCollectedID` varchar(20) DEFAULT NULL,
  `aaroCollectedDate` date DEFAULT NULL,
  `collectionDate` date DEFAULT NULL,
  `collectionStatus` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `document_record`
--

INSERT INTO `document_record` (`documentID`, `reason`, `document`, `officialReceipt`, `applicantID`, `applicantSignature`, `applicationDate`, `counter`, `applicationStatus`, `waitingStatus`, `aaroCollectionID`, `aaroCollectionDate`, `aaroCollectedID`, `aaroCollectedDate`, `collectionDate`, `collectionStatus`) VALUES
('2312002', 'student id card lost', 'Letter of KWSP,Renew of Student ID Card,', '', 'B210157B', 1, '2023-12-22', 2, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `document_review`
--

CREATE TABLE `document_review` (
  `documentID` varchar(20) NOT NULL,
  `afoID` varchar(20) DEFAULT NULL,
  `afoSignature` tinyint(1) NOT NULL,
  `afoDecision` tinyint(1) DEFAULT NULL,
  `afoComment` varchar(255) NOT NULL,
  `afoDate` text NOT NULL,
  `counter` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `document_review`
--

INSERT INTO `document_review` (`documentID`, `afoID`, `afoSignature`, `afoDecision`, `afoComment`, `afoDate`, `counter`) VALUES
('2312002', 'A0005', 1, 0, 'less RM5', '2023-12-22', 2);

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
-- Table structure for table `leave_documentalproof`
--

CREATE TABLE `leave_documentalproof` (
  `leaveID` varchar(20) NOT NULL,
  `fileName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `leave_documentalproof`
--

INSERT INTO `leave_documentalproof` (`leaveID`, `fileName`) VALUES
('2312008', 'uploads/65849b4c09895_MC.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `leave_record`
--

CREATE TABLE `leave_record` (
  `leaveID` varchar(20) NOT NULL,
  `typeOfLeave` tinyint(1) NOT NULL,
  `dateOfLeave` date NOT NULL,
  `noOfDays` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `applicantID` varchar(20) NOT NULL,
  `applicantSignature` tinyint(1) NOT NULL,
  `applicationDate` date NOT NULL,
  `aaroID` varchar(20) DEFAULT NULL,
  `aaroSignature` tinyint(1) NOT NULL,
  `aaroAcknowledgeDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `leave_record`
--

INSERT INTO `leave_record` (`leaveID`, `typeOfLeave`, `dateOfLeave`, `noOfDays`, `reason`, `applicantID`, `applicantSignature`, `applicationDate`, `aaroID`, `aaroSignature`, `aaroAcknowledgeDate`) VALUES
('2312008', 0, '2023-12-22', 1, 'sick', 'B210157B', 1, '2023-12-22', 'A0001', 1, '2023-12-22');

-- --------------------------------------------------------

--
-- Table structure for table `leave_subject`
--

CREATE TABLE `leave_subject` (
  `leaveID` varchar(20) NOT NULL,
  `subjectCode` varchar(20) NOT NULL,
  `lecturerID` varchar(20) NOT NULL,
  `lecturerSignature` tinyint(1) DEFAULT NULL,
  `lecturerAcknowledgeDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `leave_subject`
--

INSERT INTO `leave_subject` (`leaveID`, `subjectCode`, `lecturerID`, `lecturerSignature`, `lecturerAcknowledgeDate`) VALUES
('2312008', 'BTPR1003', 'L0001', 1, '2023-12-22');

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
('L0003', 'Feldicia', 'DEE', 'HOD'),
('L0004', 'Nur Shamilla Binti Selamat', 'DCS', 'lecturer'),
('L0005', 'Chan Ler-Kuan', 'DCS', 'lecturer');

-- --------------------------------------------------------

--
-- Table structure for table `resumption_of_studies_record`
--

CREATE TABLE `resumption_of_studies_record` (
  `resumptionID` varchar(20) NOT NULL,
  `defermentID` varchar(20) NOT NULL,
  `yearOfDeferment` int(4) NOT NULL,
  `semOfDeferment` int(1) NOT NULL,
  `yearOfResumption` int(4) NOT NULL,
  `semOfResumption` int(1) NOT NULL,
  `applicantID` varchar(20) NOT NULL,
  `applicantSignature` tinyint(1) NOT NULL,
  `applicationDate` date NOT NULL,
  `deanOrHeadComment` varchar(255) NOT NULL,
  `deanOrHeadSignature` tinyint(1) NOT NULL,
  `deanOrHeadID` varchar(20) DEFAULT NULL,
  `deanOrHeadAcknowledgeDate` date DEFAULT NULL,
  `afoFees` int(11) NOT NULL,
  `afoComment` varchar(255) NOT NULL,
  `afoSignature` tinyint(1) NOT NULL,
  `afoID` varchar(20) DEFAULT NULL,
  `afoAcknowledgeDate` date DEFAULT NULL,
  `aaroComment` varchar(255) NOT NULL,
  `aaroSignature` tinyint(1) NOT NULL,
  `aaroID` varchar(20) DEFAULT NULL,
  `aaroAcknowledgeDate` date DEFAULT NULL,
  `registrarAcknowledge` tinyint(1) DEFAULT NULL,
  `registrarComment` varchar(255) NOT NULL,
  `registrarSignature` tinyint(1) NOT NULL,
  `registrarID` varchar(20) DEFAULT NULL,
  `registrarAcknowledgeDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `resumption_of_studies_record`
--

INSERT INTO `resumption_of_studies_record` (`resumptionID`, `defermentID`, `yearOfDeferment`, `semOfDeferment`, `yearOfResumption`, `semOfResumption`, `applicantID`, `applicantSignature`, `applicationDate`, `deanOrHeadComment`, `deanOrHeadSignature`, `deanOrHeadID`, `deanOrHeadAcknowledgeDate`, `afoFees`, `afoComment`, `afoSignature`, `afoID`, `afoAcknowledgeDate`, `aaroComment`, `aaroSignature`, `aaroID`, `aaroAcknowledgeDate`, `registrarAcknowledge`, `registrarComment`, `registrarSignature`, `registrarID`, `registrarAcknowledgeDate`) VALUES
('2312003', '2312011', 2023, 3, 2023, 1, 'B210158B', 1, '2023-12-17', 'ok', 1, 'L0002', '2023-12-17', 0, 'ok', 1, 'A0005', '2023-12-17', 'ok', 1, 'A0001', '2023-12-17', 1, 'ok', 1, 'A0003', '2023-12-17'),
('2312004', '2312012', 2023, 3, 2023, 1, 'B210158B', 1, '2023-12-17', 'ok', 1, 'L0002', '2023-12-17', 0, 'ok', 1, 'A0005', '2023-12-17', 'ok', 1, 'A0001', '2023-12-17', 0, 'ok', 1, 'A0003', '2023-12-17'),
('2312005', '2312012', 2023, 3, 2023, 1, 'B210158B', 1, '2023-12-17', 'done', 1, 'L0002', '2023-12-17', 0, 'done', 1, 'A0005', '2023-12-17', 'ok', 1, 'A0001', '2023-12-17', 1, 'ok', 1, 'A0003', '2023-12-17'),
('2312006', '2312014', 2023, 3, 2024, 1, 'B210158B', 1, '2023-12-18', 'ok', 1, 'L0002', '2023-12-18', 0, 'ok', 1, 'A0005', '2023-12-18', 'done', 1, 'A0001', '2023-12-18', 0, 'ok', 1, 'A0003', '2023-12-18'),
('2312007', '2312014', 2023, 3, 2024, 1, 'B210158B', 1, '2023-12-19', 'ok', 1, 'L0002', '2023-12-19', 0, 'ok', 1, 'A0005', '2023-12-19', 'ok', 1, 'A0001', '2023-12-20', 0, 'ok', 1, 'A0003', '2023-12-20'),
('2312013', '2312014', 2023, 3, 2023, 3, 'B210158B', 1, '2023-12-20', 'ok', 1, 'L0002', '2023-12-20', 0, 'ok', 1, 'A0005', '2023-12-20', 'ok', 1, 'A0001', '2023-12-20', 1, 'ok', 1, 'A0003', '2023-12-20');

-- --------------------------------------------------------

--
-- Table structure for table `resumption_temporary`
--

CREATE TABLE `resumption_temporary` (
  `id` varchar(20) NOT NULL,
  `aaroComment` varchar(255) NOT NULL,
  `deanOrHeadComment` varchar(255) NOT NULL,
  `afoFees` int(11) DEFAULT NULL,
  `afoComment` varchar(255) NOT NULL,
  `registrarAcknowledge` tinyint(1) DEFAULT NULL,
  `registrarComment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `resumption_temporary`
--

INSERT INTO `resumption_temporary` (`id`, `aaroComment`, `deanOrHeadComment`, `afoFees`, `afoComment`, `registrarAcknowledge`, `registrarComment`) VALUES
('2312001', 'ok', 'okk', 300, 'done', 1, 'done'),
('2312003', '', '', NULL, '', 0, 'ok'),
('2312007', 'ok', '', NULL, '', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `studentID` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL,
  `hostel` tinyint(1) NOT NULL,
  `batchNo` varchar(20) NOT NULL,
  `departmentID` varchar(20) NOT NULL,
  `programme` varchar(255) NOT NULL,
  `contactNo` varchar(20) NOT NULL,
  `icPassport` varchar(100) NOT NULL,
  `mailingAddress` varchar(255) NOT NULL,
  `totalCreditsEarned` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`studentID`, `name`, `type`, `hostel`, `batchNo`, `departmentID`, `programme`, `contactNo`, `icPassport`, `mailingAddress`, `totalCreditsEarned`) VALUES
('B210157B', 'Tan Shan Ke', 'Local', 0, 'BoS21-B1', 'DCS', 'Bachelor of Software Engineering', '011-10990890', '010505-01-0828', '16, Jalan Harmonium 22/8, Taman Desa Tebrau', 104),
('B210158B', 'Emily Tee', 'International', 1, 'BoEE21-B1', 'DEE', 'Bachelor of Electronic Engineering with Honours', '012-3748239', '020306-01-8496', '23, Jalan Gaya 16, Taman Gaya', 18);

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
('BTIS1013', 'Introduction to Information Technology', 3),
('BTPR1003', 'Java Programming I', 3),
('BTPR2123', 'Object Oriented Programming', 3);

-- --------------------------------------------------------

--
-- Table structure for table `subject_lecturer`
--

CREATE TABLE `subject_lecturer` (
  `subjectCode` varchar(20) NOT NULL,
  `lecturerID` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_lecturer`
--

INSERT INTO `subject_lecturer` (`subjectCode`, `lecturerID`) VALUES
('BTIS1013', 'L0005'),
('BTPR1003', 'L0005'),
('BTPR1003', 'L0001'),
('BTPR2123', 'L0004');

-- --------------------------------------------------------

--
-- Table structure for table `subject_student`
--

CREATE TABLE `subject_student` (
  `subjectCode` varchar(20) NOT NULL,
  `lecturerID` varchar(20) NOT NULL,
  `studentID` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_student`
--

INSERT INTO `subject_student` (`subjectCode`, `lecturerID`, `studentID`) VALUES
('BTIS1013', 'L0005', 'B210157B'),
('BTIS1013', 'L0005', 'B210158B'),
('BTPR1003', 'L0001', 'B210157B'),
('BTPR1003', 'L0005', 'B210158B'),
('BTPR2123', 'L0004', 'B210157B');

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
('A0003', '0003', 'registrar'),
('A0005', '0005', 'afo'),
('A0006', '0006', 'afo'),
('B0001', '0001', 'lib'),
('B210157B', '1234', 'student'),
('B210158B', '5678', 'student'),
('I0001', '0001', 'iso'),
('L0001', '0890', 'lecturer'),
('L0002', '2234', 'deanOrHod'),
('L0003', '0003', 'deanOrHod'),
('L0004', '0004', 'lecturer'),
('L0005', '0005', 'lecturer'),
('S0001', '0001', 'sao'),
('S0002', '0002', 'counseling'),
('S0005', '0005', 'sro');

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
-- Indexes for table `change_class_documentalproof`
--
ALTER TABLE `change_class_documentalproof`
  ADD KEY `changeClassID` (`changeClassID`);

--
-- Indexes for table `change_class_record`
--
ALTER TABLE `change_class_record`
  ADD PRIMARY KEY (`changeClassID`),
  ADD KEY `lecturerID` (`lecturerID`),
  ADD KEY `subjectCode` (`subjectCode`),
  ADD KEY `applicantID` (`applicantID`),
  ADD KEY `deanOrHeadID` (`deanOrHeadID`),
  ADD KEY `aaroID` (`aaroID`);

--
-- Indexes for table `deferment_record`
--
ALTER TABLE `deferment_record`
  ADD PRIMARY KEY (`defermentID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`departmentID`),
  ADD KEY `facultyID` (`facultyID`);

--
-- Indexes for table `document_paymentslip`
--
ALTER TABLE `document_paymentslip`
  ADD KEY `documentID` (`documentID`);

--
-- Indexes for table `document_record`
--
ALTER TABLE `document_record`
  ADD PRIMARY KEY (`documentID`),
  ADD KEY `applicantID` (`applicantID`),
  ADD KEY `aaroCollectionID` (`aaroCollectionID`),
  ADD KEY `aaroCollectedID` (`aaroCollectedID`);

--
-- Indexes for table `document_review`
--
ALTER TABLE `document_review`
  ADD KEY `documentID` (`documentID`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`facultyID`);

--
-- Indexes for table `leave_documentalproof`
--
ALTER TABLE `leave_documentalproof`
  ADD KEY `leaveID` (`leaveID`);

--
-- Indexes for table `leave_record`
--
ALTER TABLE `leave_record`
  ADD PRIMARY KEY (`leaveID`),
  ADD KEY `applicantID` (`applicantID`),
  ADD KEY `aaroID` (`aaroID`);

--
-- Indexes for table `leave_subject`
--
ALTER TABLE `leave_subject`
  ADD KEY `leaveID` (`leaveID`),
  ADD KEY `subjectCode` (`subjectCode`),
  ADD KEY `lecturerID` (`lecturerID`);

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
  ADD KEY `deanOrHeadID` (`deanOrHeadID`),
  ADD KEY `registrarID` (`registrarID`);

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
  ADD PRIMARY KEY (`subjectCode`);

--
-- Indexes for table `subject_lecturer`
--
ALTER TABLE `subject_lecturer`
  ADD KEY `subjectCode` (`subjectCode`),
  ADD KEY `lecturerID` (`lecturerID`);

--
-- Indexes for table `subject_student`
--
ALTER TABLE `subject_student`
  ADD KEY `subjectCode` (`subjectCode`),
  ADD KEY `lecturerID` (`lecturerID`),
  ADD KEY `studentID` (`studentID`);

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
-- Constraints for table `change_class_documentalproof`
--
ALTER TABLE `change_class_documentalproof`
  ADD CONSTRAINT `change_class_documentalproof_ibfk_1` FOREIGN KEY (`changeClassID`) REFERENCES `change_class_record` (`changeClassID`);

--
-- Constraints for table `change_class_record`
--
ALTER TABLE `change_class_record`
  ADD CONSTRAINT `change_class_record_ibfk_1` FOREIGN KEY (`lecturerID`) REFERENCES `lecturer` (`lecturerID`),
  ADD CONSTRAINT `change_class_record_ibfk_2` FOREIGN KEY (`subjectCode`) REFERENCES `subject` (`subjectCode`),
  ADD CONSTRAINT `change_class_record_ibfk_3` FOREIGN KEY (`applicantID`) REFERENCES `lecturer` (`lecturerID`),
  ADD CONSTRAINT `change_class_record_ibfk_4` FOREIGN KEY (`deanOrHeadID`) REFERENCES `lecturer` (`lecturerID`),
  ADD CONSTRAINT `change_class_record_ibfk_5` FOREIGN KEY (`aaroID`) REFERENCES `administrator` (`administratorID`);

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`facultyID`) REFERENCES `faculty` (`facultyID`);

--
-- Constraints for table `document_paymentslip`
--
ALTER TABLE `document_paymentslip`
  ADD CONSTRAINT `document_paymentslip_ibfk_1` FOREIGN KEY (`documentID`) REFERENCES `document_record` (`documentID`);

--
-- Constraints for table `document_record`
--
ALTER TABLE `document_record`
  ADD CONSTRAINT `document_record_ibfk_1` FOREIGN KEY (`applicantID`) REFERENCES `student` (`studentID`),
  ADD CONSTRAINT `document_record_ibfk_4` FOREIGN KEY (`aaroCollectionID`) REFERENCES `administrator` (`administratorID`),
  ADD CONSTRAINT `document_record_ibfk_5` FOREIGN KEY (`aaroCollectedID`) REFERENCES `administrator` (`administratorID`);

--
-- Constraints for table `document_review`
--
ALTER TABLE `document_review`
  ADD CONSTRAINT `document_review_ibfk_1` FOREIGN KEY (`documentID`) REFERENCES `document_record` (`documentID`);

--
-- Constraints for table `leave_documentalproof`
--
ALTER TABLE `leave_documentalproof`
  ADD CONSTRAINT `leave_documentalproof_ibfk_1` FOREIGN KEY (`leaveID`) REFERENCES `leave_record` (`leaveID`);

--
-- Constraints for table `leave_record`
--
ALTER TABLE `leave_record`
  ADD CONSTRAINT `leave_record_ibfk_1` FOREIGN KEY (`applicantID`) REFERENCES `student` (`studentID`),
  ADD CONSTRAINT `leave_record_ibfk_2` FOREIGN KEY (`aaroID`) REFERENCES `administrator` (`administratorID`);

--
-- Constraints for table `leave_subject`
--
ALTER TABLE `leave_subject`
  ADD CONSTRAINT `leave_subject_ibfk_1` FOREIGN KEY (`leaveID`) REFERENCES `leave_record` (`leaveID`),
  ADD CONSTRAINT `leave_subject_ibfk_2` FOREIGN KEY (`subjectCode`) REFERENCES `subject` (`subjectCode`),
  ADD CONSTRAINT `leave_subject_ibfk_3` FOREIGN KEY (`lecturerID`) REFERENCES `lecturer` (`lecturerID`);

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
  ADD CONSTRAINT `resumption_of_studies_record_ibfk_4` FOREIGN KEY (`deanOrHeadID`) REFERENCES `lecturer` (`lecturerID`),
  ADD CONSTRAINT `resumption_of_studies_record_ibfk_6` FOREIGN KEY (`registrarID`) REFERENCES `administrator` (`administratorID`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`departmentID`) REFERENCES `department` (`departmentID`);

--
-- Constraints for table `subject_lecturer`
--
ALTER TABLE `subject_lecturer`
  ADD CONSTRAINT `subject_lecturer_ibfk_1` FOREIGN KEY (`subjectCode`) REFERENCES `subject` (`subjectCode`),
  ADD CONSTRAINT `subject_lecturer_ibfk_2` FOREIGN KEY (`lecturerID`) REFERENCES `lecturer` (`lecturerID`);

--
-- Constraints for table `subject_student`
--
ALTER TABLE `subject_student`
  ADD CONSTRAINT `subject_student_ibfk_1` FOREIGN KEY (`subjectCode`) REFERENCES `subject` (`subjectCode`),
  ADD CONSTRAINT `subject_student_ibfk_2` FOREIGN KEY (`lecturerID`) REFERENCES `lecturer` (`lecturerID`),
  ADD CONSTRAINT `subject_student_ibfk_3` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
