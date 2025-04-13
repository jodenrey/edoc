-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2025 at 12:02 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `edoc`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `aemail` varchar(255) NOT NULL,
  `apassword` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`aemail`, `apassword`) VALUES
('admin@edoc.com', '123');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appoid` int(11) NOT NULL,
  `pid` int(10) DEFAULT NULL,
  `apponum` int(3) DEFAULT NULL,
  `scheduleid` int(10) DEFAULT NULL,
  `appodate` date DEFAULT NULL,
  `payment_status` VARCHAR(20) DEFAULT "Unpaid"
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appoid`, `pid`, `apponum`, `scheduleid`, `appodate`) VALUES
(6, 11, 1, 12, '2025-04-02'),
(7, 11, 1, 13, '2025-04-02'),
(8, 12, 1, 15, '2025-04-04'),
(9, 12, 1, 14, '2025-04-04'),
(10, 12, 1, 17, '2025-04-04');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `docid` int(11) NOT NULL,
  `docemail` varchar(255) DEFAULT NULL,
  `docname` varchar(255) DEFAULT NULL,
  `docpassword` varchar(255) DEFAULT NULL,
  `docnic` varchar(15) DEFAULT NULL,
  `doctel` varchar(15) DEFAULT NULL,
  `specialties` int(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`docid`, `docemail`, `docname`, `docpassword`, `docnic`, `doctel`, `specialties`) VALUES
(3, 'christiemarieebuenga@edoc.com', 'Dr. Emmanuel Lantican', '123', '', '09222073190', 23),
(4, 'renellelabtuon@edoc.com', 'Dr. Renelle Labtuon', '123', '', '09368384156', 32),
(5, 'tawnyannpcortesgaspar@edoc.com', 'Dr. Tawny Ann P. Cortes- Gaspar', '123', '', '09174818044', 32),
(6, 'emmanuellantican@edoc.com', 'Dr. Emmanuel Lantican', '123', '', '09171629186', 23),
(7, 'kathleenmayalpapara@edoc.com', 'Dr. Kathleen May Alpapara', '123', '', '09083938900', 23),
(8, 'glennmichaelramos@edoc.com', 'Dr. Glenn Michael Ramos', '123', '', '09634707004', 23),
(9, 'jeromellapitan@edoc.com', 'Dr. Jeromel Lapitan', '123', '', '09225571070', 23),
(10, 'maristelcorcuera@edoc.com', 'Dr. Maristel Corcuera', '123', '', '09171629186', 23),
(11, 'claudettegabrillo@edoc.com', 'Dr. Claudette Gabrillo', '123', '', '09222073190', 1),
(12, 'ruzenettehernandez@edoc.com', 'Dr. Ruzenette Hernandez', '123', '', '09224033708', 38),
(13, 'jedeanearagon@edoc.com', 'Dr. Jedeane Aragon', '123', '', '09434383211', 38),
(14, 'mariaelenferino@edoc.com', 'Dr. Maria Elena Ferino', '123', '', '09657190649', 19),
(15, 'lafayatteang-santo@edoc.com', 'Dr. Lafayatte Ang-Santo', '123', '', '09222073190', 54);

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `pid` int(11) NOT NULL,
  `pemail` varchar(255) DEFAULT NULL,
  `pname` varchar(255) DEFAULT NULL,
  `ppassword` varchar(255) DEFAULT NULL,
  `paddress` varchar(255) DEFAULT NULL,
  `pnic` varchar(15) DEFAULT NULL,
  `pdob` date DEFAULT NULL,
  `ptel` varchar(15) DEFAULT NULL,
  `payment_status` enum('Paid','Unpaid','Pending') DEFAULT 'Unpaid'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`pid`, `pemail`, `pname`, `ppassword`, `paddress`, `pnic`, `pdob`, `ptel`) VALUES
(12, 'KING@EDOC.COM', 'KING SIR', '123', 'TAGAYTAY', '', '2020-02-05', ''),
(11, 'trina@edoc.com', 'TRINA QWEA', '123', '1234 SUCOL', '', '2025-04-02', ''),
(7, 'ai@gmail.com', 'ai ai', '123', 'manila', '', '2015-06-11', ''),
(6, 'wert@gmail.com', 'qwe rty', '123', 'qasw', '', '2023-08-11', '');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `scheduleid` int(11) NOT NULL,
  `docid` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `scheduledate` date DEFAULT NULL,
  `scheduletime` time DEFAULT NULL,
  `nop` int(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`scheduleid`, `docid`, `title`, `scheduledate`, `scheduletime`, `nop`) VALUES
(12, '12', 'CARDIOLOGY', '2025-04-02', '09:30:00', 3),
(13, '4', 'GENERAL OB-GYNE', '2025-04-02', '10:50:00', 10),
(11, '5', 'ENDOCRINOLY', '2025-04-02', '09:00:00', 5),
(14, '10', 'INFERTILITY', '2025-04-09', '08:37:00', 13),
(15, '14', 'ENDOCRINOLY', '2025-04-09', '16:40:00', 7),
(16, '15', 'UROLOGY', '2025-04-03', '16:38:00', 6),
(17, '7', 'DERMATOLOGY', '2025-04-04', '10:30:00', 10),
(18, '9', 'FAMILY MEDICINE', '2025-04-02', '17:40:00', 13),
(19, '13', 'NEPHROLOGY', '2025-04-06', '12:40:00', 4),
(20, '8', 'GASTROENTEROLOGY', '2025-04-02', '18:00:00', 15),
(21, '3', 'CARDIOLOGY', '2025-04-19', '07:42:00', 9);

-- --------------------------------------------------------

--
-- Table structure for table `specialties`
--

CREATE TABLE `specialties` (
  `id` int(2) NOT NULL,
  `sname` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `specialties`
--

INSERT INTO `specialties` (`id`, `sname`) VALUES
(1, 'Accident and emergency medicine'), 
(2, 'Allergology'),
(3, 'Anaesthetics'),
(4, 'Biological hematology'),
(5, 'Cardiology'),
(6, 'Child psychiatry'),
(7, 'Clinical biology'),
(8, 'Clinical chemistry'),
(9, 'Clinical neurophysiology'),
(10, 'Clinical radiology'),
(11, 'Dental, oral and maxillo-facial surgery'),
(12, 'Dermato-venerology'),
(13, 'Dermatology'),
(14, 'Endocrinology'),
(15, 'Gastro-enterologic surgery'),
(16, 'Gastroenterology'),
(17, 'General hematology'),
(18, 'General Practice'),
(19, 'General surgery'),
(20, 'Geriatrics'),
(21, 'Immunology'),
(22, 'Infectious diseases'),
(23, 'Internal medicine'),
(24, 'Laboratory medicine'),
(25, 'Maxillo-facial surgery'),
(26, 'Microbiology'),
(27, 'Nephrology'),
(28, 'Neuro-psychiatry'),
(29, 'Neurology'),
(30, 'Neurosurgery'),
(31, 'Nuclear medicine'),
(32, 'Obstetrics and gynecology'),
(33, 'Occupational medicine'),
(34, 'Ophthalmology'),
(35, 'Orthopaedics'),
(36, 'Otorhinolaryngology'),
(37, 'Paediatric surgery'),
(38, 'Paediatrics'),
(39, 'Pathology'),
(40, 'Pharmacology'),
(41, 'Physical medicine and rehabilitation'),
(42, 'Plastic surgery'),
(43, 'Podiatric Medicine'),
(44, 'Podiatric Surgery'),
(45, 'Psychiatry'),
(46, 'Public health and Preventive Medicine'),
(47, 'Radiology'),
(48, 'Radiotherapy'),
(49, 'Respiratory medicine'),
(50, 'Rheumatology'),
(51, 'Stomatology'),
(52, 'Thoracic surgery'),
(53, 'Tropical medicine'),
(54, 'Urology'),
(55, 'Vascular surgery'),
(56, 'Venereology');

-- --------------------------------------------------------

--
-- Table structure for table `webuser`
--

CREATE TABLE `webuser` (
  `email` varchar(255) NOT NULL,
  `usertype` char(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `webuser`
--

INSERT INTO `webuser` (`email`, `usertype`) VALUES
('admin@edoc.com', 'a'),
('christiemarieebuenga@edoc.com', 'd'),
('patient@edoc.com', 'p'),
('emhashenudara@gmail.com', 'p'),
('bri@gmail.com', 'p'),
('SHAINE@GMAIL.COM', 'p'),
('sheila@gmail.com', 'p'),
('wert@gmail.com', 'p'),
('ai@gmail.com', 'p'),
('trina@gmail.com', 'p'),
('trina@edoc.com', 'p'),
('renellelabtuon@edoc.com', 'd'),
('tawnyannpcortesgaspar@edoc.com', 'd'),
('emmanuellantican@edoc.com', 'd'),
('kathleenmayalpapara@edoc.com', 'd'),
('glennmichaelramos@edoc.com', 'd'),
('jeromellapitan@edoc.com', 'd'),
('maristelcorcuera@edoc.com', 'd'),
('claudettegabrillo@edoc.com', 'd'),
('ruzenettehernandez@edoc.com', 'd'),
('jedeanearagon@edoc.com', 'd'),
('mariaelenferino@edoc.com', 'd'),
('lafayatteang-santo@edoc.com', 'd'),
('KING@EDOC.COM', 'p');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `appoid` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` enum('Cash','Cashless') NOT NULL,
  `payment_date` datetime NOT NULL DEFAULT current_timestamp(),
  `doctor_remarks` text DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `pid` (`pid`),
  KEY `appoid` (`appoid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Update patient records to have default payment status
--

UPDATE `patient` SET `payment_status` = 'Unpaid' WHERE `payment_status` IS NULL;

-- --------------------------------------------------------

--
-- Table structure for table `checkup_data`
--

CREATE TABLE IF NOT EXISTS `checkup_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appoid` int(11) NOT NULL,
  `doctorRemarks` text DEFAULT NULL,
  `patientName` varchar(255) DEFAULT NULL,
  `firstName` varchar(255) DEFAULT NULL,
  `patientId` varchar(50) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `patientAge` varchar(10) DEFAULT NULL,
  `accompaniedBy` varchar(255) DEFAULT NULL,
  `relationship` varchar(100) DEFAULT NULL,
  `height` varchar(50) DEFAULT NULL,
  `weight` varchar(50) DEFAULT NULL,
  `bloodPressure` varchar(50) DEFAULT NULL,
  `pastMedical` varchar(10) DEFAULT NULL,
  `pastMedicalDesc` text DEFAULT NULL,
  `development` varchar(10) DEFAULT NULL; 
  `developmentalHistory` varchar(10) DEFAULT NULL,
  `developmentalDesc` text DEFAULT NULL,
  `behavioralHealth` varchar(10) DEFAULT NULL,
  `behavioralDesc` text DEFAULT NULL,
  `nutritional` varchar(10) DEFAULT NULL,
  `nutritionalDesc` text DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `plan` text DEFAULT NULL,
  `checkupDate` varchar(50) DEFAULT NULL,
  `appearance` varchar(10) DEFAULT NULL,
  `appearanceComments` text DEFAULT NULL,
  `skin` varchar(10) DEFAULT NULL,
  `skinComments` text DEFAULT NULL,
  `head` varchar(10) DEFAULT NULL,
  `headComments` text DEFAULT NULL,
  `eyes` varchar(10) DEFAULT NULL,
  `eyesComments` text DEFAULT NULL,
  `ears` varchar(10) DEFAULT NULL,
  `earsComments` text DEFAULT NULL,
  `nose` varchar(10) DEFAULT NULL,
  `noseComments` text DEFAULT NULL,
  `mouth` varchar(10) DEFAULT NULL,
  `mouthComments` text DEFAULT NULL,
  `nodes` varchar(10) DEFAULT NULL,
  `nodesComments` text DEFAULT NULL,
  `heart` varchar(10) DEFAULT NULL,
  `heartComments` text DEFAULT NULL,
  `lungs` varchar(10) DEFAULT NULL,
  `lungsComments` text DEFAULT NULL,
  `abdomen` varchar(10) DEFAULT NULL,
  `abdomenComments` text DEFAULT NULL,
  `femPulse` varchar(10) DEFAULT NULL,
  `femPulseComments` text DEFAULT NULL,
  `extGen` varchar(10) DEFAULT NULL,
  `extGenComments` text DEFAULT NULL,
  `extremities` varchar(10) DEFAULT NULL,
  `extremitiesComments` text DEFAULT NULL,
  `spine` varchar(10) DEFAULT NULL,
  `spineComments` text DEFAULT NULL,
  `neuro` varchar(10) DEFAULT NULL,
  `neuroComments` text DEFAULT NULL,
  `other` varchar(10) DEFAULT NULL,
  `otherComments` text DEFAULT NULL,
  `dentalReferral` tinyint(1) DEFAULT 0,
  `fluoride` tinyint(1) DEFAULT 0,
  `referred` tinyint(1) DEFAULT 0,
  `ua` tinyint(1) DEFAULT 0,
  `leadScreen` tinyint(1) DEFAULT 0,
  `otherTests` text DEFAULT NULL,
  `normalVision` tinyint(1) DEFAULT 0,
  `abnormalVision` tinyint(1) DEFAULT 0,
  `referredVision` tinyint(1) DEFAULT 0,
  `normalHearing` tinyint(1) DEFAULT 0,
  `abnormalHearing` tinyint(1) DEFAULT 0, 
  `referredHearing` tinyint(1) DEFAULT 0,
  `speechHearing` varchar(10) DEFAULT NULL,
  `developmentNormal` tinyint(1) DEFAULT 0,
  `current` tinyint(1) DEFAULT 0,
  `deferred` tinyint(1) DEFAULT 0,
  `provided` tinyint(1) DEFAULT 0,
  `providedList` text DEFAULT NULL,
  `dental` tinyint(1) DEFAULT 0,
  `nutrition` tinyint(1) DEFAULT 0,
  `regularActivity` tinyint(1) DEFAULT 0,
  `safety` tinyint(1) DEFAULT 0,
  `peerRelations` tinyint(1) DEFAULT 0,
  `communication` tinyint(1) DEFAULT 0,
  `parentalRole` tinyint(1) DEFAULT 0,
  `schoolPerformance` tinyint(1) DEFAULT 0,
  `limitSetting` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `appoid` (`appoid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Add foreign key constraint for checkup_data
--

ALTER TABLE `checkup_data`
  ADD CONSTRAINT `checkup_data_appoid_fk` FOREIGN KEY (`appoid`) REFERENCES `appointment` (`appoid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`aemail`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appoid`),
  ADD KEY `pid` (`pid`),
  ADD KEY `scheduleid` (`scheduleid`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`docid`),
  ADD KEY `specialties` (`specialties`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`scheduleid`),
  ADD KEY `docid` (`docid`);

--
-- Indexes for table `specialties`
--
ALTER TABLE `specialties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `webuser`
--
ALTER TABLE `webuser`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appoid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `docid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `scheduleid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `specialties`
--
ALTER TABLE `specialties`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */; 