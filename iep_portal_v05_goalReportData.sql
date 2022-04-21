-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 21, 2022 at 04:50 PM
-- Server version: 5.7.34
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iep_portal`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `saveReport` (IN `@objId` INT, IN `@date` DATE, IN `@observed` INT)  BEGIN
INSERT INTO report (objective_id, report_date, report_observed) VALUES (@objID,@date,@observed);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin_active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `user_id`, `admin_active`) VALUES
(1, 11, 1);

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE `document` (
  `document_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `document_date` datetime NOT NULL,
  `document_name` varchar(100) NOT NULL,
  `document_path` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `document`
--

INSERT INTO `document` (`document_id`, `student_id`, `user_id`, `document_date`, `document_name`, `document_path`) VALUES
(1, 1, 2, '2022-04-11 00:00:00', 'exampleDocument.pdf', 'http://localhost/capstoneCurrent/'),
(2, 2, 2, '2022-04-14 00:00:00', 'exampleDocument2.pdf', 'http://localhost/capstoneCurrent/'),
(3, 2, 3, '2022-04-18 00:00:00', 'exampleDocument3.pdf', 'http://localhost/capstoneCurrent/');

-- --------------------------------------------------------

--
-- Table structure for table `goal`
--

CREATE TABLE `goal` (
  `goal_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `goal_label` varchar(40) DEFAULT NULL,
  `goal_category` varchar(50) DEFAULT NULL,
  `goal_text` text,
  `goal_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `goal`
--

INSERT INTO `goal` (`goal_id`, `student_id`, `goal_label`, `goal_category`, `goal_text`, `goal_active`) VALUES
(1, 1, 'Updated Goal Name', 'Communication/Language', 'Here is a brief description of the goal, as it would be described on the IEP plan agreed on by parents and school.', 0),
(2, 1, 'Goal Name', 'Social/Emotional', 'Here is a brief description of the goal, as it would be described on the IEP plan agreed on by parents and school.', 0),
(3, 2, 'Goal Name', 'Academic/Reading', 'Here is a brief description of the goal, as it would be described on the IEP plan agreed on by parents and school.', 0),
(4, 3, 'Goal Name', 'Independent Functioning', 'Here is a brief description of the goal, as it would be described on the IEP plan agreed on by parents and school.', 0),
(5, 3, 'Goal Name', 'Social/Emotional', 'Here is a brief description of the goal, as it would be described on the IEP plan agreed on by parents and school.', 0),
(6, 4, 'Goal Name', 'Communication/Language', 'Here is a brief description of the goal, as it would be described on the IEP plan agreed on by parents and school.', 0),
(7, 4, 'Goal Name', 'Academic/Writing', 'Here is a brief description of the goal, as it would be described on the IEP plan agreed on by parents and school.', 0),
(9, 2, 'Test Insert Goal Alert', 'Test', 'Does the alert to tell the user the new goal has been added work?', 0),
(10, 2, 'Test Insert Goal Alert', 'Test', 'Does the alert to tell the user the new goal has been added work?', 0),
(14, 1, 'Brand New Goal', 'Example', 'Text test', 0),
(17, 4, 'New label', 'category', 'text', 0);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message_text` text,
  `message_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`message_id`, `user_id`, `message_text`, `message_date`) VALUES
(1, 12, 'Example message text.', '2022-03-26 20:26:11');

-- --------------------------------------------------------

--
-- Table structure for table `message_recipient`
--

CREATE TABLE `message_recipient` (
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message_read` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `message_recipient`
--

INSERT INTO `message_recipient` (`message_id`, `user_id`, `message_read`) VALUES
(1, 11, 0);

-- --------------------------------------------------------

--
-- Table structure for table `objective`
--

CREATE TABLE `objective` (
  `objective_id` int(11) NOT NULL,
  `goal_id` int(11) NOT NULL,
  `objective_label` varchar(40) DEFAULT NULL,
  `objective_text` text,
  `objective_attempts` int(11) DEFAULT NULL,
  `objective_target` int(11) DEFAULT NULL,
  `objective_status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `objective`
--

INSERT INTO `objective` (`objective_id`, `goal_id`, `objective_label`, `objective_text`, `objective_attempts`, `objective_target`, `objective_status`) VALUES
(1, 1, 'Updated Objective Name', 'A brief text description of the objective, what is measured over how many attempts.', 10, 7, 0),
(2, 1, 'Objective Name', 'A brief text description of the objective, what is measured over how many attempts.', 10, 7, 0),
(3, 2, 'Objective Name', 'A brief text description of the objective, what is measured over how many attempts.', 10, 7, 0),
(4, 2, 'Updated Objective Name', 'Is the objective status accessed properly now? How about now?', 10, 8, 0),
(5, 3, 'Objective Name', 'A brief text description of the objective, what is measured over how many attempts.', 10, 7, 0),
(6, 3, 'Objective Name', 'A brief text description of the objective, what is measured over how many attempts.', 10, 7, 0),
(7, 4, 'Objective Name', 'A brief text description of the objective, what is measured over how many attempts.', 10, 7, 0),
(8, 4, 'Objective Name', 'A brief text description of the objective, what is measured over how many attempts.', 10, 7, 0),
(9, 5, 'Objective Name', 'A brief text description of the objective, what is measured over how many attempts.', 10, 7, 0),
(10, 5, 'Objective Name', 'A brief text description of the objective, what is measured over how many attempts.', 10, 7, 0),
(11, 6, 'Objective Name', 'A brief text description of the objective, what is measured over how many attempts.', 10, 7, 0),
(12, 6, 'Objective Name', 'A brief text description of the objective, what is measured over how many attempts.', 10, 7, 0),
(13, 7, 'Objective Name', 'A brief text description of the objective, what is measured over how many attempts.', 10, 7, 0),
(14, 7, 'Objective Name', 'A brief text description of the objective, what is measured over how many attempts.', 10, 7, 0),
(16, 2, 'New Objective Test', 'Check new objective button function', 12, 6, 0),
(19, 17, 'Objective', 'text', 10, 7, 0),
(20, 14, 'Brand New Objective', 'A description of the objective.', 10, 8, 0);

-- --------------------------------------------------------

--
-- Table structure for table `provider`
--

CREATE TABLE `provider` (
  `provider_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `provider_title` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `provider`
--

INSERT INTO `provider` (`provider_id`, `user_id`, `provider_title`) VALUES
(1, 15, 'teacher'),
(2, 16, 'teacher');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `report_id` int(11) NOT NULL,
  `objective_id` int(11) NOT NULL,
  `report_date` date DEFAULT NULL,
  `report_observed` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`report_id`, `objective_id`, `report_date`, `report_observed`) VALUES
(1, 1, '2021-06-30', 3),
(2, 1, '2021-09-30', 4),
(3, 1, '2021-10-30', 6),
(4, 1, '2021-11-30', 8),
(5, 2, '2021-06-30', 6),
(6, 2, '2021-09-30', 6),
(7, 2, '2021-10-30', 7),
(8, 3, '2021-06-30', 4),
(9, 3, '2021-09-30', 5),
(10, 3, '2021-10-30', 5),
(11, 3, '2022-04-26', 8),
(12, 3, '2021-12-30', 5),
(13, 4, '2021-09-30', 5),
(14, 4, '2021-10-01', 1),
(15, 5, '2021-10-02', 2),
(16, 5, '2021-10-03', 3),
(17, 6, '2021-10-04', 4),
(18, 6, '2021-10-05', 5),
(19, 7, '2021-10-06', 6),
(20, 7, '2021-10-08', 7),
(21, 8, '2021-10-08', 8),
(22, 8, '2021-10-09', 9),
(23, 9, '2021-10-10', 10),
(24, 9, '2021-10-11', 4),
(25, 10, '2021-10-12', 2),
(26, 10, '2021-10-13', 3),
(27, 11, '2021-10-14', 4),
(28, 11, '2021-10-15', 5),
(29, 12, '2021-10-16', 6),
(30, 12, '2021-10-17', 7),
(31, 13, '2021-10-18', 8),
(32, 13, '2021-10-19', 9),
(33, 14, '2021-10-20', 10),
(34, 14, '2021-10-21', 5),
(35, 14, '2021-10-22', 6),
(36, 11, '2022-04-05', 6),
(37, 11, '2022-04-05', 6),
(38, 11, '2022-04-05', 6),
(45, 11, '2022-04-07', 4),
(46, 11, '2022-04-07', 4),
(48, 11, '2022-04-08', 7),
(54, 16, '2022-04-13', 5),
(55, 16, '2022-04-13', 5),
(56, 16, '2022-04-13', 5),
(57, 16, '2022-04-13', 5),
(58, 16, '2022-04-13', 5),
(59, 16, '2022-04-13', 5),
(60, 16, '2022-04-13', 5),
(61, 4, '2022-04-11', 4),
(62, 7, '2022-04-15', 3),
(63, 7, '2022-04-15', 6),
(64, 8, '2022-04-16', 3),
(67, 3, '2022-04-16', 2),
(68, 5, '2022-04-17', 1),
(69, 5, '2022-04-18', 1),
(70, 5, '2022-04-19', 1),
(76, 11, '2022-04-11', 3),
(78, 11, '2022-04-12', 3),
(80, 5, '2022-04-21', 5),
(81, 16, '2022-04-19', 6),
(82, 20, '2022-04-20', 6),
(83, 20, '2022-04-21', 6),
(84, 20, '2022-03-15', 5),
(85, 20, '2022-02-10', 5),
(86, 1, '2022-04-29', 7);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `student_school` varchar(50) DEFAULT NULL,
  `student_grade` varchar(10) DEFAULT NULL,
  `student_homeroom` varchar(50) DEFAULT NULL,
  `student_dob` date DEFAULT NULL,
  `student_eval_date` date DEFAULT NULL,
  `student_next_evaluation` date DEFAULT NULL,
  `student_iep_date` date DEFAULT NULL,
  `student_next_iep` date DEFAULT NULL,
  `student_eval_status` varchar(20) DEFAULT NULL,
  `student_iep_status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `user_id`, `provider_id`, `student_school`, `student_grade`, `student_homeroom`, `student_dob`, `student_eval_date`, `student_next_evaluation`, `student_iep_date`, `student_next_iep`, `student_eval_status`, `student_iep_status`) VALUES
(1, 17, 1, 'Stargazer Elementary', '1', 'Smith', '2015-07-01', '2020-04-15', '2023-04-15', '2021-05-30', '2022-05-30', 'current', 'current'),
(2, 18, 1, 'Stargazer Elementary', '3', 'Jones', '2013-08-01', '2021-05-15', '2023-05-15', '2021-06-30', '2022-06-30', 'current', 'current'),
(3, 19, 2, 'Enterprise Middle School', '7', 'Harris', '2009-04-01', '2021-05-15', '2024-03-15', '2021-04-15', '2022-04-15', 'current', 'current'),
(4, 20, 1, 'Stargazer Elementray', '5', 'Lee', '2011-03-01', '2019-10-15', '2022-10-15', '2021-12-12', '2022-12-12', 'current', 'current');

-- --------------------------------------------------------

--
-- Table structure for table `student_parent`
--

CREATE TABLE `student_parent` (
  `student_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_relationship` varchar(30) DEFAULT NULL,
  `parent_access` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `student_parent`
--

INSERT INTO `student_parent` (`student_id`, `user_id`, `parent_relationship`, `parent_access`) VALUES
(1, 12, 'grandparent', 1),
(2, 13, 'mother', 1),
(3, 13, 'mother', 1),
(4, 14, 'father', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_password` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_first_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_last_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_email` varchar(65) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_phone` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_address` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_city` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_district` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `user_password`, `user_first_name`, `user_last_name`, `user_email`, `user_phone`, `user_address`, `user_city`, `user_district`, `user_type`) VALUES
(11, 'Reginald.Dax', 'appleChair123!', 'Reginald', 'Dax', 'reginald.dax@example.com', '1234567890', '2234 Example Street', 'Randomburg', 'Randomburg SD 234', 'admin'),
(12, 'Julian.Crusher', 'appleChair123!', 'Julian', 'Crusher', 'julian.crusher@example.com', '1234567891', '2235 Example Street', 'Randomburg', 'Randomburg SD 235', 'user'),
(13, 'Christine.Chapel', 'appleChair123!', 'Christine', 'Chapel', 'christine.chapel@example.com', '1234567892', '2236 Example Street', 'Randomburg', 'Randomburg SD 236', 'user'),
(14, 'Hikaru.Scott', 'appleChair123!', 'Hikaru', 'Scott', 'Hikaru.Scott@example.com', '1234567893', '2237 Example Street', 'Randomburg', 'Randomburg SD 237', 'user'),
(15, 'Nerys.Chekov', 'appleChair123!', 'Nerys', 'Chekov', 'Nerys.Chekov@example.com', '1234567894', '2238 Example Street', 'Randomburg', 'Randomburg SD 238', 'provider'),
(16, 'Nyota.Kira', 'appleChair123!', 'Nyota', 'Kira', 'Nyota.Kira@example.com', '1234567895', '2239 Example Street', 'Randomburg', 'Randomburg SD 239', 'provider'),
(17, 'Hikaru.Paris', 'appleChair123!', 'Hikaru', 'Paris', 'Hikaru.Paris@example.com', '1234567896', '2240 Example Street', 'Randomburg', 'Randomburg SD 240', 'student'),
(18, 'Deanna.Paris', 'appleChair123!', 'Deanna', 'Paris', 'Deanna.Paris@example.com', '1234567897', '2241 Example Street', 'Randomburg', 'Randomburg SD 241', 'student'),
(19, 'Julian.Uhura', 'appleChair123!', 'Julian', 'Uhura', 'Julian.Uhura@example.com', '1234567898', '2242 Example Street', 'Randomburg', 'Randomburg SD 242', 'student'),
(20, 'James.McCoy', 'appleChair123!', 'James', 'McCoy', 'James.McCoy@example.com', '1234567899', '2243 Example Street', 'Randomburg', 'Randomburg SD 243', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `user_id` (`user_id`) USING BTREE;

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `studentAccount` (`student_id`);

--
-- Indexes for table `goal`
--
ALTER TABLE `goal`
  ADD PRIMARY KEY (`goal_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `message_recipient`
--
ALTER TABLE `message_recipient`
  ADD PRIMARY KEY (`message_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `objective`
--
ALTER TABLE `objective`
  ADD PRIMARY KEY (`objective_id`),
  ADD KEY `goal_id` (`goal_id`);

--
-- Indexes for table `provider`
--
ALTER TABLE `provider`
  ADD PRIMARY KEY (`provider_id`),
  ADD UNIQUE KEY `user_id` (`user_id`) USING BTREE;

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `objective_id` (`objective_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `student_parent`
--
ALTER TABLE `student_parent`
  ADD PRIMARY KEY (`student_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `goal`
--
ALTER TABLE `goal`
  MODIFY `goal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `objective`
--
ALTER TABLE `objective`
  MODIFY `objective_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `provider`
--
ALTER TABLE `provider`
  MODIFY `provider_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `studentAccount` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `goal`
--
ALTER TABLE `goal`
  ADD CONSTRAINT `goal_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `message_recipient`
--
ALTER TABLE `message_recipient`
  ADD CONSTRAINT `message_recipient_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `message` (`message_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `message_recipient_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `objective`
--
ALTER TABLE `objective`
  ADD CONSTRAINT `objective_ibfk_1` FOREIGN KEY (`goal_id`) REFERENCES `goal` (`goal_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `provider`
--
ALTER TABLE `provider`
  ADD CONSTRAINT `provider_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`objective_id`) REFERENCES `objective` (`objective_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`provider_id`) REFERENCES `provider` (`provider_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_parent`
--
ALTER TABLE `student_parent`
  ADD CONSTRAINT `student_parent_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_parent_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
