-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2025 at 08:57 AM
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
-- Database: `hep_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `book_hall_form`
--

CREATE TABLE `book_hall_form` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `program_name` varchar(255) DEFAULT NULL,
  `bookhall_date` date DEFAULT NULL,
  `request_equip` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_hall_form`
--

INSERT INTO `book_hall_form` (`id`, `user_id`, `name`, `program_name`, `bookhall_date`, `request_equip`) VALUES
(20, 3, NULL, NULL, NULL, NULL),
(21, 3, NULL, NULL, NULL, NULL),
(22, 3, 'Jasmin binti Ahmad', 'Meneroka Alam sekitar', '2025-07-31', 'Laptop'),
(23, 1, 'Jasmin binti Ahmad', 'Testing #4', '2025-07-02', 'Microphone, Cordless Microphone'),
(24, 4, 'Jasmin binti Ahmad', 'Testing #5', '2025-07-26', 'LCD, Laptop'),
(25, 1, 'Jasmin binti Ahmad', 'Testing #5', '2025-07-02', 'Microphone, Cordless Microphone'),
(26, 1, NULL, NULL, NULL, NULL),
(27, 1, NULL, NULL, NULL, NULL),
(28, 1, NULL, NULL, NULL, NULL),
(29, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `certificate_submissions`
--

CREATE TABLE `certificate_submissions` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `association` varchar(100) DEFAULT NULL,
  `program_name` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `venue` varchar(100) DEFAULT NULL,
  `report_date` date DEFAULT NULL,
  `submission_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificate_submissions`
--

INSERT INTO `certificate_submissions` (`id`, `user_id`, `association`, `program_name`, `date`, `venue`, `report_date`, `submission_time`) VALUES
(20, '3', NULL, NULL, NULL, NULL, NULL, '2025-07-01 16:13:02'),
(21, '3', NULL, NULL, NULL, NULL, NULL, '2025-07-01 16:16:24'),
(22, '3', NULL, NULL, NULL, NULL, NULL, '2025-07-01 16:40:59'),
(23, '1', 'DUMMY-3', 'Testing #4', '2025-07-02', 'Dewan Titiwangsa', '2025-07-02', '2025-07-01 17:22:12'),
(24, '4', NULL, NULL, NULL, NULL, NULL, '2025-07-01 17:27:40'),
(25, '1', NULL, NULL, NULL, NULL, NULL, '2025-07-02 01:15:42'),
(26, '1', NULL, NULL, NULL, NULL, NULL, '2025-07-04 13:10:30'),
(27, '1', NULL, NULL, NULL, NULL, NULL, '2025-07-04 13:16:38'),
(28, '1', 'DUMMY-8', 'Testing #8', '2025-07-25', 'Dewan Titiwangsa', '2025-07-25', '2025-07-04 13:40:50'),
(29, '1', NULL, NULL, NULL, NULL, NULL, '2025-07-06 03:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `c_form_submissions`
--

CREATE TABLE `c_form_submissions` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `association_code` varchar(100) DEFAULT NULL,
  `association_name` varchar(100) DEFAULT NULL,
  `activity_name` varchar(100) DEFAULT NULL,
  `activity_level` varchar(100) DEFAULT NULL,
  `activity_category` varchar(100) DEFAULT NULL,
  `joint_organizer` varchar(100) DEFAULT NULL,
  `activity_venue` varchar(100) DEFAULT NULL,
  `activity_date` date DEFAULT NULL,
  `participants_male` int(11) DEFAULT NULL,
  `participants_female` int(11) DEFAULT NULL,
  `participants_total` int(11) DEFAULT NULL,
  `estimated_cost` decimal(10,2) DEFAULT NULL,
  `estimated_sponsorship` decimal(10,2) DEFAULT NULL,
  `estimated_revenue` decimal(10,2) DEFAULT NULL,
  `impact_students` text DEFAULT NULL,
  `impact_university` text DEFAULT NULL,
  `impact_community` text DEFAULT NULL,
  `soft_skills` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pending',
  `admin_remark` text DEFAULT NULL,
  `checked_by` varchar(100) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `c_form_submissions`
--

INSERT INTO `c_form_submissions` (`id`, `user_id`, `association_code`, `association_name`, `activity_name`, `activity_level`, `activity_category`, `joint_organizer`, `activity_venue`, `activity_date`, `participants_male`, `participants_female`, `participants_total`, `estimated_cost`, `estimated_sponsorship`, `estimated_revenue`, `impact_students`, `impact_university`, `impact_community`, `soft_skills`, `submitted_at`, `status`, `admin_remark`, `checked_by`, `file_path`) VALUES
(21, '3', 'DUMMY-1', 'Testing Program', 'Testing #1', 'District', 'Culture/Heritage', 'Joint Organizer', 'Concorde Hotel', '2025-07-04', 678, 56, 734, 4578.00, 34567.00, 567.00, 'a lot of benefits', 'a lot of benefits', 'a lot', 'Critical Thinking', '2025-07-01 16:16:24', 'Approved', 'Program menarik', '2', NULL),
(22, '3', 'DUMMY-2', 'Testing Program', 'Testing #2', 'Faculty', 'Entrepreneurship', 'Joint Organizer', 'Bukit Jalil', '2025-07-02', 89, 576, 665, 3245678.00, 3456.00, 3456.00, '6789', 'a lot of benefits', 'a lot', 'Critical Thinking', '2025-07-01 16:40:59', 'Rejected', 'tak bagus', '2', NULL),
(23, '1', 'DUMMY-3', 'Testing Program', 'Testing #4', 'Faculty', 'Public Speaking', 'Joint Organizer', 'Dewan Titiwangsa', '2025-07-02', 89, 67, 156, 2345678.00, 34576.00, 675.00, 'a lot of benefits', 'a lot of benefits', 'a lot', 'Critical Thinking', '2025-07-01 17:22:12', 'Rejected', 'Tak menepati PI', '2', NULL),
(24, '4', 'DUMMY-4', 'Testing Program', 'Testing #5', 'International', 'Academic/Intellectual', 'Joint Organizer', 'Concorde Hotel', '2025-07-02', 78, 56, 134, 2345678.00, 456789.00, 567890.00, 'a lot of benefits', 'a lot of benefits', 'banyak', 'Communication, Critical Thinking, Teamwork', '2025-07-01 17:27:40', 'Approved', '', '2', NULL),
(25, '1', 'DUMMY-5', 'Testing Program', 'Testing #5', 'University', 'Entrepreneurship', 'Joint Organizer', 'Concorde Hotel', '2025-07-31', 45, 67, 112, 345.00, 7543.00, 8656.00, 'banyak', 'a lot of benefits', 'a lot', 'Critical Thinking', '2025-07-02 01:15:42', 'Rejected', 'tak bagus', '2', NULL),
(26, '1', 'DUMMY-6', 'Testing Program', 'Testing #6', 'University', 'Culture/Heritage', 'Joint Organizer', 'Dewan Titiwangsa', '2025-07-26', 45678, 678, 46356, 678.00, 5678.00, 5678.00, 'a lot of benefits', 'a lot of benefits', 'banyak', 'Critical Thinking', '2025-07-04 13:10:30', 'Pending', NULL, NULL, NULL),
(27, '1', 'DUMMY-7', 'Testing Program', 'Testing #7', 'University', 'Entrepreneurship', 'Joint Organizer', 'Dewan Titiwangsa', '2025-08-06', 789, 456, 1245, 678.00, 5678.00, 678.00, 'a lot of benefits', 'a lot of benefits', 'banyak', 'Critical Thinking', '2025-07-04 13:16:38', 'Approved', 'tak bagus', '2', NULL),
(29, '1', 'DUMMY-8', 'Testing Program', 'Testing #8', 'District', 'Culture/Heritage', 'Joint Organizer', 'Dewan Titiwangsa', '2025-08-09', 678, 876, 1554, 678.00, 56.00, 78.00, 'a lot of benefits', 'a lot of benefits', 'banyak', 'Critical Thinking', '2025-07-06 03:30:00', 'Pending', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `emerit_submissions`
--

CREATE TABLE `emerit_submissions` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `program_name` varchar(255) DEFAULT NULL,
  `program_date` date DEFAULT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `organizer` varchar(255) DEFAULT NULL,
  `coupon` int(11) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emerit_submissions`
--

INSERT INTO `emerit_submissions` (`id`, `user_id`, `program_name`, `program_date`, `venue`, `organizer`, `coupon`, `submitted_at`) VALUES
(21, '3', 'Meneroka Alam sekitar', '2025-07-10', 'Concorde Hotel', 'Organizer', 1245, '2025-07-01 16:16:24'),
(22, '3', 'Testing #2', '2025-07-05', 'Dewan Titiwangsa', 'Organizer', 34567, '2025-07-01 16:40:59'),
(23, '1', 'Testing #4', '2025-07-02', 'Dewan Titiwangsa', 'Organizer 2', 3456, '2025-07-01 17:22:12'),
(24, '4', NULL, NULL, NULL, NULL, NULL, '2025-07-01 17:27:40'),
(25, '1', 'Testing #5', '2025-07-03', 'Concorde Hotel', 'Organizer 2', 4653, '2025-07-02 01:15:42'),
(26, '1', NULL, NULL, NULL, NULL, NULL, '2025-07-04 13:10:30'),
(27, '1', 'Meneroka Alam sekitar', '2025-07-03', 'Dewan Titiwangsa', 'Organizer', 6709, '2025-07-04 13:16:38'),
(28, '1', NULL, NULL, NULL, NULL, NULL, '2025-07-04 13:40:50'),
(29, '1', NULL, NULL, NULL, NULL, NULL, '2025-07-06 03:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `honorarium_submissions`
--

CREATE TABLE `honorarium_submissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `contact_num` varchar(20) DEFAULT NULL,
  `faculty_unit` varchar(100) DEFAULT NULL,
  `application_date` date DEFAULT NULL,
  `purpose_use` text DEFAULT NULL,
  `use_date` date DEFAULT NULL,
  `use_time` time DEFAULT NULL,
  `expected_num` varchar(10) DEFAULT NULL,
  `request_facilities` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `honorarium_submissions`
--

INSERT INTO `honorarium_submissions` (`id`, `user_id`, `contact_num`, `faculty_unit`, `application_date`, `purpose_use`, `use_date`, `use_time`, `expected_num`, `request_facilities`) VALUES
(20, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 3, '45678', 'Persatuan Sains', '2025-07-02', 'Macam-macam', '2025-07-02', '15:46:00', '907', 'P.A System, Microphone, Others, Others: banyak'),
(23, 1, '0145556666', 'DUMMY-3', '2025-07-02', 'Macam-macam', '2025-07-02', '07:30:00', '907', 'Sofa / Settee, Banquet Chair'),
(24, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `uploaded_paperwork`
--

CREATE TABLE `uploaded_paperwork` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uploaded_paperwork`
--

INSERT INTO `uploaded_paperwork` (`id`, `user_id`, `title`, `file_path`, `upload_date`) VALUES
(21, 3, 'ACCESS Method', 'uploads/1751386673_ENT300_GUIDELINES (2).pdf', '2025-07-01 16:17:53'),
(22, 3, 'RANDOM FILE', 'uploads/1751388234_ENT300 OVERVIEW (2).pdf', '2025-07-01 16:43:54'),
(23, 1, 'RANDOM FILE', 'uploads/1751390721_Ch 12 Security (1).pdf', '2025-07-01 17:25:21'),
(24, 4, 'RANDOM FILE', 'uploads/1751390893_248 cover oage.pdf', '2025-07-01 17:28:13'),
(25, 1, 'RANDOM FILE', 'uploads/1751419010_ENT300 OVERVIEW (2).pdf', '2025-07-02 01:16:50'),
(28, 1, 'RANDOM FILE', 'uploads/1751638582_WhatsApp Image 2025-07-03 at 00.54.20 (1).jpeg', '2025-07-04 14:16:22'),
(29, 1, 'RANDOM FILE', 'uploads/1751772671_[6] ITS332 User Manual-Template-MARCH-2024 (1).docx', '2025-07-06 03:31:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `password`, `is_admin`) VALUES
(1, 'student123', '1234', 0),
(2, 'admin123', '1234', 1),
(3, 'yana55', '1234', 0),
(4, 'Ayesha', '1234', 0),
(5, 'student2', '1234', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book_hall_form`
--
ALTER TABLE `book_hall_form`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificate_submissions`
--
ALTER TABLE `certificate_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `c_form_submissions`
--
ALTER TABLE `c_form_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emerit_submissions`
--
ALTER TABLE `emerit_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `honorarium_submissions`
--
ALTER TABLE `honorarium_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uploaded_paperwork`
--
ALTER TABLE `uploaded_paperwork`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book_hall_form`
--
ALTER TABLE `book_hall_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `certificate_submissions`
--
ALTER TABLE `certificate_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `c_form_submissions`
--
ALTER TABLE `c_form_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `emerit_submissions`
--
ALTER TABLE `emerit_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `honorarium_submissions`
--
ALTER TABLE `honorarium_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `uploaded_paperwork`
--
ALTER TABLE `uploaded_paperwork`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
