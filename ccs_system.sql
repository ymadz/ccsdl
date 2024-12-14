-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2024 at 02:26 AM
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
-- Database: `ccs_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `user_id`, `username`, `password`, `role_id`, `created_at`, `status`) VALUES
(1, 1, 'admin', '$2y$10$iEu2d9THfjkIbT3fkq1GC..5ZD9zbDVajIdZoMuvHouzLl68OakPy', 1, '2024-12-12 16:26:33', 'Active'),
(2, 2, 'jelaine', '$2y$10$iakx25kLYocG9AGBV0cRauat1LDNGV7fKWjmif9yxavsjqncsyqIy', 3, '2024-12-12 16:28:57', 'Active'),
(3, 3, 'salimar', '$2y$10$9BsM/U7.L2P1dEVWGdR2pOdBIjnYnRrUrJYTYAq5ndC/CHDi85NBK', 2, '2024-12-12 17:11:29', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `adviser`
--

CREATE TABLE `adviser` (
  `adviser_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `course_name`) VALUES
(1, 'BS Computer Science'),
(2, 'BS Information Technology'),
(3, 'ACT Application Development'),
(4, 'ACT Networking');

-- --------------------------------------------------------

--
-- Table structure for table `dean_lister_application_periods`
--

CREATE TABLE `dean_lister_application_periods` (
  `id` int(11) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'closed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `department_name`) VALUES
(1, 'Department of Computer Science'),
(2, 'Department of Information Technology'),
(3, 'Department of Associate in Computer Technology');

-- --------------------------------------------------------

--
-- Table structure for table `leaderboard`
--

CREATE TABLE `leaderboard` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `average_rating` decimal(4,2) DEFAULT NULL,
  `rank_position` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prospectus`
--

CREATE TABLE `prospectus` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `subject_code` varchar(20) NOT NULL,
  `descriptive_title` varchar(255) NOT NULL,
  `prerequisite` varchar(255) DEFAULT NULL,
  `lec_units` decimal(3,1) DEFAULT NULL,
  `lab_units` decimal(3,1) DEFAULT NULL,
  `total_units` decimal(3,1) GENERATED ALWAYS AS (`lec_units` + `lab_units`) STORED,
  `year_level` int(11) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `school_year` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prospectus`
--

INSERT INTO `prospectus` (`id`, `course_id`, `subject_code`, `descriptive_title`, `prerequisite`, `lec_units`, `lab_units`, `year_level`, `semester`, `school_year`, `created_at`, `updated_at`) VALUES
(1, 1, 'CC 100', 'Introduction to Computing', NULL, 2.0, 1.0, 1, 'First', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(2, 1, 'CC 101', 'Computer Programming 1 (Fundamentals of Programming)', NULL, 3.0, 1.0, 1, 'First', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(3, 1, 'DS 111', 'Discrete Structures 1', NULL, 3.0, 0.0, 1, 'First', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(4, 1, 'CAS 101', 'Purposive Communication', NULL, 3.0, 0.0, 1, 'First', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 17:53:45'),
(5, 1, 'MATH 100', 'Mathematics in the Modern World', NULL, 3.0, 0.0, 1, 'First', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(6, 1, 'HIST 100', 'Life and Works of Rizal', NULL, 3.0, 0.0, 1, 'First', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(7, 1, 'US 101', 'Understanding the Self', NULL, 3.0, 0.0, 1, 'First', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(8, 1, 'PATHFIT', 'Movement Competency Training', NULL, 2.0, 0.0, 1, 'First', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(9, 1, 'NSTP 1', 'CWTS 1/ LTS 1/ ROTC 1', NULL, 3.0, 0.0, 1, 'First', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(10, 1, 'CC 102', 'Computer Programming (Intermediate Programming)', 'CC 101', 3.0, 1.0, 1, 'Second', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(11, 1, 'OOP 112', 'Object-Oriented Programming', 'CC 101', 2.0, 1.0, 1, 'Second', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(12, 1, 'WD 114', 'Web Development 1', 'CC 101', 2.0, 1.0, 1, 'Second', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(13, 1, 'HCI 116', 'Human Computer Interaction', 'CC 101', 3.0, 0.0, 1, 'Second', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(14, 1, 'DS 118', 'Discrete Structures 2', 'DS 111', 3.0, 0.0, 1, 'Second', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(15, 1, 'STS 100', 'Science, Technology and Society', NULL, 3.0, 0.0, 1, 'Second', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(16, 1, 'PATHFIT 2', 'Exercise-Based Fitness Activities', 'PATHFIT', 2.0, 0.0, 1, 'Second', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(17, 1, 'NSTP 2', 'CWTS 2/ LTS 2/ ROTC 2', 'NSTP 1', 3.0, 0.0, 1, 'Second', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(18, 1, 'CC 103', 'Data Structures and Algorithms', 'CC 102', 2.0, 1.0, 2, 'First', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(19, 1, 'CC 104', 'Information Management', 'CC 102, OOP 112', 2.0, 1.0, 2, 'First', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(20, 1, 'MAD 121', 'Mobile Application Development', 'CC 102, OOP 112', 2.0, 1.0, 2, 'First', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(21, 1, 'WD 123', 'Web Development 2', 'WD 114', 2.0, 1.0, 2, 'First', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(22, 1, 'SIPP 125', 'Social Issues and Professional Practice', 'CC 102', 3.0, 0.0, 2, 'First', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(23, 1, 'NC 127', 'Network and Communications', 'CC 102', 2.0, 1.0, 2, 'First', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(24, 1, 'CC 105', 'Applications Development and Emerging Technologies', 'CC 104', 2.0, 1.0, 2, 'Second', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(25, 1, 'ACTINT 122', 'ACT Internship (320 Hours)', 'WD 123, SIPP 125', 0.0, 6.0, 2, 'Second', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(26, 1, 'AO 124', 'Architecture and Organization', 'DS 111, CC 103', 2.0, 1.0, 2, 'Second', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(27, 1, 'AC 128', 'Algorithms and Complexity', 'DS 118, CC 103', 3.0, 0.0, 2, 'Summer', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(28, 1, 'PL 129', 'Programming Languages', 'CC 103', 2.0, 1.0, 2, 'Summer', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(29, 1, 'STAT 120', 'Statistics for Computer Science', 'MATH 100', 3.0, 0.0, 2, 'Summer', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(30, 1, 'SE 131', 'Software Engineering 1', 'CC 105, WD 123', 2.0, 1.0, 3, 'First', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(31, 1, 'ADS 133', 'Advanced Database Systems', 'CC 104', 2.0, 1.0, 3, 'First', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(32, 1, 'THESIS 139', 'CS Thesis 1', 'SE 132, TW 136', 3.0, 0.0, 3, 'Summer', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(33, 1, 'THESIS 141', 'CS Thesis 2', 'THESIS 139', 3.0, 0.0, 4, 'First', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(34, 1, 'A&H 100', 'Art Appreciation', NULL, 3.0, 0.0, 4, 'First', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(35, 1, 'CSPRAC 142', 'Practicum/ Industry Immersion (162 hours)', 'THESIS 141', 0.0, 3.0, 4, 'Second', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(36, 1, 'ETHICS 101', 'Ethics', NULL, 3.0, 0.0, 4, 'Second', '2023-2024', '2024-12-12 16:48:24', '2024-12-12 16:48:24'),
(37, 2, 'CC 100', 'Introduction to Computing', NULL, 2.0, 1.0, 1, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(38, 2, 'CC 101', 'Computer Programming 1', NULL, 2.0, 1.0, 1, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(39, 2, 'CAS 101', 'Purposive Communication', NULL, 3.0, 0.0, 1, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(40, 2, 'MATH 100', 'Mathematics in the Modern World', NULL, 3.0, 0.0, 1, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(41, 2, 'US 101', 'Understanding the Self', NULL, 3.0, 0.0, 1, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(42, 2, 'GE Elect 1', 'GE Elective 1', NULL, 3.0, 0.0, 1, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(43, 2, 'PATHFIT 1', 'Movement Contemporary Training', NULL, 2.0, 0.0, 1, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(44, 2, 'NSTP 1', 'CWTS 1/LTS 1/ROTC 1', NULL, 3.0, 0.0, 1, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(45, 2, 'FIL 101', 'Wika, Kultura, at Mapayapang Lipunan', NULL, 3.0, 0.0, 1, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(46, 2, 'IT 121', 'Discrete Mathematics', 'MATH 100', 3.0, 0.0, 1, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(47, 2, 'IT 122', 'Introduction to Human-Computer Interaction', 'CC 100', 2.0, 1.0, 1, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(48, 2, 'CC 102', 'Computer Programming 2', 'CC 101', 2.0, 1.0, 1, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(49, 2, 'HIST 100', 'Life and Works of Rizal', NULL, 3.0, 0.0, 1, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(50, 2, 'A&H 100', 'Art Appreciation', NULL, 3.0, 0.0, 1, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(51, 2, 'GE Elect 2', 'GE Elective 2', NULL, 3.0, 0.0, 1, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(52, 2, 'PATHFIT 2', 'Exercise-based Fitness Activities', 'PATHFIT 1', 2.0, 0.0, 1, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(53, 2, 'NSTP 2', 'CWTS 2/LTS 2/ROTC 2', 'NSTP 1', 3.0, 0.0, 1, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(54, 2, 'PHILCON', 'Philippine Constitution', 'NSTP 1', 3.0, 0.0, 1, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(55, 2, 'IT Elective 1', 'IT Elective 1', NULL, 2.0, 1.0, 2, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(56, 2, 'IT 212', 'Quantitative Methods', 'IT 121', 3.0, 0.0, 2, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(57, 2, 'IT 213', 'Social and Professional Practice', NULL, 3.0, 0.0, 2, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(58, 2, 'CC 103', 'Data Structures and Algorithm', 'CC 102', 2.0, 1.0, 2, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(59, 2, 'CC 104', 'Information Management', 'CC 102', 2.0, 1.0, 2, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(60, 2, 'HIST 100', 'Readings in Philippine History', NULL, 3.0, 0.0, 2, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(61, 2, 'STS 100', 'Science, Technology and Society', NULL, 3.0, 0.0, 2, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(62, 2, 'PATHFIT 3', 'Fitness Activities 1', 'PATHFIT 1, PATHFIT 2', 2.0, 0.0, 2, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(63, 2, 'IT 221', 'Integrative Programming and Technologies 1', 'CC 103', 2.0, 1.0, 2, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(64, 2, 'IT 222', 'Networking 1', 'CC 100', 2.0, 1.0, 2, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(65, 2, 'IT Elective 2', 'IT Elective 2', NULL, 2.0, 1.0, 2, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(66, 2, 'IT 224', 'Mobile Computing', 'CC 102, IT 221', 2.0, 1.0, 2, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(67, 2, 'IT 225', 'Advanced Database Systems', 'CC 104', 2.0, 1.0, 2, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(68, 2, 'CW 101', 'The Contemporary World', NULL, 3.0, 0.0, 2, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(69, 2, 'ETHICS 101', 'Ethics', NULL, 3.0, 0.0, 2, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(70, 2, 'PATHFIT 4', 'Fitness Activities 2', 'PATHFIT 1, PATHFIT 2', 2.0, 0.0, 2, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(71, 2, 'CC 105', 'Application Development and Emerging Technologies', 'IT 225', 2.0, 1.0, 3, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(72, 2, 'IT 311', 'Networking 2', 'IT 222', 2.0, 1.0, 3, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(73, 2, 'IT 312', 'Systems Integration and Architecture 1', 'IT 221', 2.0, 1.0, 3, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(74, 2, '', 'IT Elective 3', NULL, 2.0, 1.0, 2, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(75, 2, 'IT 314', 'Data Analysis', 'IT 212', 2.0, 1.0, 3, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(76, 2, 'IT 321', 'Internet of Things', 'IT 311', 2.0, 1.0, 3, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(77, 2, 'IT 322', 'Machine Learning', 'IT 314', 2.0, 1.0, 3, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(78, 2, 'IT 323', 'Information Assurance and Security 1', 'IT 312', 2.0, 1.0, 3, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(79, 2, '', 'IT Elective 4', NULL, 2.0, 1.0, 2, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(80, 2, '', 'GE Elect 3', NULL, 3.0, 0.0, 2, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(81, 2, 'IT 331', 'Capstone Project and Research 1', NULL, 3.0, 0.0, 2, 'Summer', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(82, 2, 'IT 332', 'Information Assurance and Security 2', 'IT 323', 2.0, 1.0, 2, 'Summer', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(83, 2, 'IT 411', 'Capstone Project and Research 2', 'IT 331', 3.0, 0.0, 4, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(84, 2, 'IT 412', 'Systems Administration and Maintenance', 'IT 312', 2.0, 1.0, 4, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(85, 2, 'IT 413', 'Cybersecurity', 'IT 332', 2.0, 1.0, 4, 'First', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(86, 2, 'IT 421', 'Cloud Computing', 'IT 413', 2.0, 1.0, 4, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49'),
(87, 2, 'IT 422', 'Practicum (486 hours)', 'IT 411', 0.0, 2.0, 4, 'Second', '2023-2024', '2024-12-12 17:01:49', '2024-12-12 17:01:49');

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `rating` decimal(4,2) DEFAULT NULL,
  `adviser` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Staff'),
(3, 'Student');

-- --------------------------------------------------------

--
-- Table structure for table `student_applications`
--

CREATE TABLE `student_applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `school_year` varchar(20) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `adviser` varchar(100) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `rejection_reason` text DEFAULT NULL,
  `total_rating` decimal(4,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dean_lister_period_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `identifier` varchar(20) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `course_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `identifier`, `firstname`, `middlename`, `lastname`, `email`, `created_at`, `course_id`, `department_id`) VALUES
(1, 'ADMIN-2024', 'System', NULL, 'Administrator', 'admin@wmsu.edu.ph', '2024-12-12 16:26:33', NULL, 1),
(2, '2023-00493', 'Jelaine', 'Carmelotes', 'Macias', 'hz202300493@wmsu.edu.ph', '2024-12-12 16:28:57', 1, NULL),
(3, '123456', 'SALIMAR', 'B', 'TAHIL', 'SalimarTahil@wmsu.edu.ph', '2024-12-12 17:11:29', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `adviser`
--
ALTER TABLE `adviser`
  ADD PRIMARY KEY (`adviser_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dean_lister_application_periods`
--
ALTER TABLE `dean_lister_application_periods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `prospectus`
--
ALTER TABLE `prospectus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_applications`
--
ALTER TABLE `student_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `dean_lister_period_id` (`dean_lister_period_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `department_id` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `adviser`
--
ALTER TABLE `adviser`
  MODIFY `adviser_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dean_lister_application_periods`
--
ALTER TABLE `dean_lister_application_periods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `leaderboard`
--
ALTER TABLE `leaderboard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prospectus`
--
ALTER TABLE `prospectus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_applications`
--
ALTER TABLE `student_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `account_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Constraints for table `adviser`
--
ALTER TABLE `adviser`
  ADD CONSTRAINT `adviser_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `adviser_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`);

--
-- Constraints for table `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD CONSTRAINT `leaderboard_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `leaderboard_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `prospectus`
--
ALTER TABLE `prospectus`
  ADD CONSTRAINT `prospectus_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`);

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `rating_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `prospectus` (`id`);

--
-- Constraints for table `student_applications`
--
ALTER TABLE `student_applications`
  ADD CONSTRAINT `student_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `student_applications_ibfk_2` FOREIGN KEY (`dean_lister_period_id`) REFERENCES `dean_lister_application_periods` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
