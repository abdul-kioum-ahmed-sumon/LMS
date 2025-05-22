-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 01:26 PM
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
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `publication_year` varchar(10) DEFAULT NULL,
  `isbn` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `shelf_no` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `publication_year`, `isbn`, `category_id`, `status`, `shelf_no`, `created_at`, `updated_at`) VALUES
(18, 'Operating System', 'Galvin', '2023', '10283', 1, 1, 'CSE - 123', '2023-06-06 01:40:25', '2024-03-26 03:59:12'),
(19, 'International Relations', 'Pushpesh Pant', '2021', '90283', 6, 1, 'AIS-2', '2023-06-06 01:41:04', '2024-04-19 11:44:28'),
(20, 'Theory of Computation', 'Ullman', '2021', '91038', 1, 1, 'CSE-12', '2023-06-06 01:41:32', '2024-04-19 11:44:07'),
(21, 'Ancient India', 'R.S Sharma', '1978', '92031', 7, 1, '123', '2023-06-06 01:42:13', '2024-04-19 11:44:46'),
(22, 'Modern Control Systems', 'Ogata', '2023', '20312', 2, 1, 'EEE-14', '2023-06-06 01:42:36', '2024-04-19 11:43:27'),
(30, 'Bela furabar age', 'arif ajaz', '2024', '334324', 7, 1, '123', '2024-03-26 09:39:48', '0000-00-00 00:00:00'),
(33, 'Cracking the coding interview', 'Gayle Laakmann McDowell', '2008', '9780984782864', 1, 1, 'CSE-12', '2024-03-29 10:35:47', '2024-04-19 11:43:43'),
(34, 'Introduction to Algorithms', 'Thomas H. Cormen', '2009', '978-0262033848', 1, 1, 'CSE-12', '2024-04-02 02:10:25', NULL),
(35, 'Electric Circuits', 'James W. Nilsson, Susan Riedel', '2014', '978-0133760033', 2, 1, 'EEE-14', '2024-04-02 02:13:22', NULL),
(36, 'Principles of Marketing', 'Philip T. Kotler, Gary Armstrong', '2016', '978-0134492513', 7, 1, 'BBA-161', '2024-04-02 02:24:26', '2024-04-20 05:08:12'),
(37, 'Engineering Mechanics: Statics', 'Russell C. Hibbeler', '2014', '978-0133918922', 3, 1, 'ME-13', '2024-04-02 02:25:02', NULL),
(38, 'Harry Potter and the Sorcerer\'s Stone', 'J.K. Rowling', '1995', '978-059035341', 7, 1, '123', '2024-04-02 02:29:29', '0000-00-00 00:00:00'),
(39, 'CP 52 problems', 'subin', '2021', '12938231', 7, 1, 'CSE-12', '2024-04-18 13:49:44', '2024-04-22 09:08:09'),
(40, 'Understanding Deep Learning', 'Simon J. D. Prince', '2023', '9780262048644', 7, 1, 'CSE-12', '2024-04-19 00:33:25', '2024-04-22 09:22:00'),
(41, 'C#', 'J.K. Rowling', '2012', '34342332', 1, 1, 'CSE-12', '2024-05-04 16:25:21', NULL),
(42, 'physic ssc', 'saiful', '2024', '999999', 7, 1, 'others', '2024-10-09 10:02:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `book_loans`
--

CREATE TABLE `book_loans` (
  `id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `loan_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `is_return` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `book_loans`
--

INSERT INTO `book_loans` (`id`, `book_id`, `student_id`, `loan_date`, `return_date`, `is_return`, `created_at`, `updated_at`) VALUES
(19, 21, 17, '2024-04-23', '2024-04-30', 0, '2024-04-23 03:46:19', NULL),
(20, 20, 17, '2024-04-23', '2024-04-30', 1, '2024-04-23 03:47:31', NULL),
(21, 37, 8, '2024-04-24', '2024-04-27', 0, '2024-04-24 11:39:55', NULL),
(22, 30, 30, '2024-04-24', '2024-04-30', 1, '2024-04-24 11:54:41', NULL),
(23, 19, 19, '2024-05-03', '2024-05-23', 0, '2024-05-03 12:13:18', NULL),
(24, 30, 34, '2024-06-06', '2024-06-30', 0, '2024-06-06 01:49:06', NULL),
(25, 18, 13, '2024-08-24', '2024-08-31', 1, '2024-08-24 09:33:54', NULL),
(26, 42, 35, '2024-10-09', '2024-10-30', 1, '2024-10-09 10:03:19', NULL),
(27, 41, 36, '2024-10-26', '2024-10-31', 1, '2024-10-26 10:49:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'CSE', '2023-06-05 06:42:51', NULL),
(2, 'EEE', '2023-06-05 06:42:51', NULL),
(3, 'ME', '2024-03-26 08:56:14', NULL),
(4, 'CE', '2024-03-26 08:56:14', NULL),
(5, 'BBA', '2024-03-26 08:56:30', NULL),
(6, 'English', '2024-03-26 08:56:47', NULL),
(7, 'Other\'s ', '2024-03-26 08:58:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `question` varchar(50) DEFAULT NULL,
  `answer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `question`, `answer`) VALUES
(3, 'sumon', 'fdgsdfgdsfgdsfgdsfgdsfgdfsgdsfgdsgd'),
(4, ' sss', 'ss'),
(5, 'sertynn	', 'aweeeee');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `magazine`
--

CREATE TABLE `magazine` (
  `magazine_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `publication_date` date DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `ISSN_or_ISBN` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `magazine`
--

INSERT INTO `magazine` (`magazine_id`, `title`, `publisher`, `publication_date`, `category`, `language`, `ISSN_or_ISBN`, `description`) VALUES
(50, 'Library ', 'MD. Abdullah', '2024-11-08', 'Education', 'Bengali', '977-0028-609-32', 'assets/Lecture2.pdf'),
(52, 'TIME', 'TIME USA', '2023-04-01', 'News & Current Affairs', 'English', '0040-781X', 'assets/DB-lesson16.pdf'),
(56, 'Human Resource', 'John', '2024-11-05', 'Social', 'English', '45814', 'assets/Lecture1.pdf'),
(57, 'fadsgsdfg', 'sdfgsdfg', '2024-12-12', 'dsgsdfgsdf', 'sdgfsdf', 'sfgsdfgs', 'assets/Lecture4.pdf'),
(58, 'gfhdghd', 'dghdfh', '2024-10-10', 'dfhdfgh', 'dghdfhd', 'dfghdgh', 'assets/Lecture4.pdf'),
(60, 'Discovery', 'Mee', '2024-04-10', 'Animal', 'Urdu', '654654', 'assets/Lecture1.pdf'),
(64, 'java oop', 'dsfha', '2024-04-16', 'cse', 'English ', 'df233423', 'mag_ass/Database_Notes.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `title`, `content`, `created_at`, `photo`) VALUES
(28, 'Notice For Holiday!', 'ggggg', '2024-04-15 16:30:45', NULL),
(30, 'bbddkabkdbakdbkabdk', 'kbdaskbdkasbkd', '2024-04-15 16:34:52', NULL),
(31, 'hahdhahdj', 'MAHHSBDBAjbbAdeee', '2024-04-15 16:36:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notices2`
--

CREATE TABLE `notices2` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices2`
--

INSERT INTO `notices2` (`id`, `title`, `content`, `file_path`, `created_at`) VALUES
(6, 'eeeeee', 'hellow akij', 'uploads/gaming-19.jpg', '2024-04-16 05:23:31'),
(11, 'Library management system ', 'A library management system is software that is designed to manage all the functions of a library. It helps librarian to maintain the database of new books and the books that are borrowed by members along with their due dates.', 'uploads/LIBRARY-MANAGEMENT-SYSTEM.png', '2024-04-22 13:07:12'),
(12, 'Leave Notice', 'It is Said That', 'uploads/Library_bg (1).jpeg', '2024-04-22 16:39:21'),
(13, 'Harry Potter and the Sorcerer\'s Stone', 'Awesome', 'uploads/IMG_20221121_095126_tigr-01.jpeg', '2024-04-22 17:51:23');

-- --------------------------------------------------------

--
-- Table structure for table `previous_questions`
--

CREATE TABLE `previous_questions` (
  `id` int(11) NOT NULL,
  `course` varchar(100) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `question` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `previous_questions`
--

INSERT INTO `previous_questions` (`id`, `course`, `subject`, `year`, `semester`, `question`) VALUES
(3, 'dsfafaf', 'asdfasfsa', 2222, 32323, 'dfdsfasfasf'),
(4, 'B.Sc. Engg. in C.E', 'Building Material', 2021, 2, 'What is concrete grade?'),
(5, 'B.Sc. Engg. in E.E.E', 'Circuit Analysis', 2022, 2, 'What is armature reaction?'),
(6, 'Bachelor of Business Administration', 'International Trade & Business', 2024, 5, 'Why international trade is important?'),
(20, 'gg', 'ss', 2222, 22, 'sss'),
(21, 'ss', 'ss', 22, 22, 'ggg'),
(22, 'sdfasf', 'sdfsdf', 2021, 221, 'dsdfsfsdfdsffsdfafafasf');

-- --------------------------------------------------------

--
-- Table structure for table `reset_password`
--

CREATE TABLE `reset_password` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reset_code` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `position` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `name`, `email`, `password`, `position`) VALUES
(7, 'Anika', 'sumaiyajafrin0@gmail.com', 'Anika', 'teacher'),
(9, 'mmmmmmm', 'chsdfasdfasdfasfhfxx@gmail.com', 'fcdhdhgchc', 'Sales Rep'),
(12, 'Abdullah', 'abdullah@gmail.com', '12345', 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone_no` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `dept_id` varchar(255) DEFAULT NULL,
  `dept` varchar(255) DEFAULT NULL,
  `reg_no` int(255) DEFAULT NULL,
  `Username` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `phone_no`, `email`, `address`, `status`, `created_at`, `updated_at`, `dept_id`, `dept`, `reg_no`, `Username`) VALUES
(8, 'Abdul kyum ahmed Sumon', '+8801309897349', 'sumonahmed2521@gmail.com', 'Joypurhat', 1, '2024-03-26 09:59:42', '2024-04-19 01:03:06', '220101046', 'CSE', NULL, NULL),
(9, 'Abdullah Al Mamun', '01315272398', 'abdullah@1252', 'rongpur', 1, '2024-03-26 10:01:11', '2024-03-26 10:39:54', '220201034', 'CSE', NULL, NULL),
(10, 'Labib', '013456789674563', 'labib@gmail.com', 'baust', 1, '2024-03-26 10:09:57', '2024-03-29 06:39:51', '220201019', 'CSE', NULL, NULL),
(11, 'Siam Hasan', '01303567114', 'siam@baust', 'bogura', 1, '2024-03-26 10:11:49', '2024-04-02 03:26:08', '220201045', 'CSE', NULL, NULL),
(13, 'arif ', '01760676357', 'arifulm233434@gmail.com', 'rongpur23', 0, '2024-03-29 10:09:42', '2024-08-24 09:32:42', '220101044', 'CSE', NULL, NULL),
(17, 'Maruf', '01309895843', 'maruf@gmail.com', 'Nougan', 1, '2024-03-29 10:43:41', NULL, '2201010456', 'CSE', NULL, NULL),
(18, 'Fahad', '013032321', 'fajera9894@lance7.com', 'Nougan', 1, '2024-03-29 10:52:57', '2024-03-29 10:53:18', '2212122123', 'BBA', NULL, NULL),
(19, 'Asif  Rezwan', '01796050003', 'asif@gmail.com', 'r1ngpur', 1, '2024-03-30 00:17:54', '2024-04-02 02:50:36', '220201022', 'CSE', NULL, NULL),
(20, 'Nafsun kabir', '01782921891', 'nafsun@gmail.com', 'rangpur', 1, '2024-04-01 08:03:19', '2024-04-01 08:03:58', '220201049', 'CSE', NULL, NULL),
(21, 'Tanvir Islam', '0169696969', 'tanvir@gmail.com', 'Joypurhat', 1, '2024-04-02 03:05:29', NULL, '220201002', 'CSE', NULL, NULL),
(23, 'Raihan ', '012773626155', 'raihan@gmail.cpm', 'Dhaka', 1, '2024-04-18 12:58:27', NULL, '220101557', 'IPE', NULL, NULL),
(26, 'Vaskor', '01230323112', 'vaskor@gmail.com', 'rangpur', 1, '2024-04-19 01:09:16', '2024-04-19 01:09:29', '32343432233', 'CE', NULL, NULL),
(27, 'Tamzid', '012320323222', 'tamzid@gmail.com', 'fulbari', 1, '2024-04-20 11:31:49', NULL, '220201001', 'CSE', NULL, NULL),
(28, 'Rifat Bro', '0123202332', 'rifatbro@gmail.com', 'Dinajpur', 1, '2024-04-22 09:31:21', NULL, '220201232', 'CSE', NULL, NULL),
(31, 'Emon', '01812649299', 'emon@gmail.com', 'Joypurhat', 1, '2024-05-04 15:31:38', '2024-06-20 15:58:54', '2202221022', 'CSE', NULL, NULL),
(32, 'Adip', '01532213132', 'adip@gmail.com', 'rongpur', 1, '2024-05-05 14:40:10', NULL, '2323121', 'EEE', NULL, NULL),
(33, 'Suvo', '01984322123', 'suvo@gmail.com', 'cumilla', 1, '2024-05-05 15:22:32', NULL, '23123112', 'ME', NULL, NULL),
(34, 'kakoly', '01734897154', 'kakoly@gmail.com', 'Joypurhat', 1, '2024-06-06 01:48:20', NULL, '4554456', 'CSE', NULL, NULL),
(35, 'Saiful islam', '01732101465', 'sisaifulislam7273@gmail.com', 'Joypurhat', 1, '2024-10-09 10:00:00', NULL, '7898103754', 'CSE', NULL, NULL),
(36, 'Taj ul millat', '21342434535', 'fsdghjfash@gmail.com', 'Dhaka', 1, '2024-10-26 10:47:32', NULL, '0802420405101010', 'CSE', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `student_id`, `plan_id`, `start_date`, `end_date`, `amount`, `created_at`, `updated_at`) VALUES
(8, 8, 6, '2024-04-18', '2025-04-18', 2000.00, '2024-04-18 12:31:49', NULL),
(9, 9, 11, '2024-04-19', '2024-06-19', 400.00, '2024-04-19 10:30:57', NULL),
(10, 9, 3, '2024-04-22', '2024-10-22', 1200.00, '2024-04-22 12:24:49', NULL),
(11, 10, 11, '2024-04-22', '2024-06-22', 400.00, '2024-04-22 12:25:53', NULL),
(12, 9, 9, '2024-04-23', '2024-05-23', 300.00, '2024-04-23 03:50:11', NULL),
(13, 8, 11, '2024-04-24', '2024-06-24', 400.00, '2024-04-24 11:40:27', NULL),
(14, 30, 12, '2024-04-24', '2025-02-24', 200.00, '2024-04-24 11:55:36', NULL),
(15, 35, 9, '2024-10-09', '2024-11-09', 300.00, '2024-10-09 10:04:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `amount` float(10,2) NOT NULL DEFAULT 0.00,
  `duration` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`id`, `title`, `amount`, `duration`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Premium', 1200.00, 6, 1, '2023-06-06 03:51:09', NULL),
(6, 'Annual', 2000.00, 12, 1, '2023-06-06 04:21:22', '2023-06-06 06:00:50'),
(9, 'Basic', 300.00, 1, 1, '2024-03-27 11:25:42', NULL),
(11, 'Eid Offer', 400.00, 2, 1, '2024-04-19 01:59:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone_no` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone_no`, `password`, `profile_pic`, `created_at`, `updated_at`) VALUES
(1, 'Sumon Ahmed', 'abdulkioumahmed@gmail.com', '01309897349', '$2y$10$0LYpycDTZOaE3DQNaWgdhO0M.sUO25fX8fRcMvb0xljb1oU3LwGg.', 'profile-pic (15).png', '2023-06-07 03:44:48', '2024-04-22 07:59:55');

-- --------------------------------------------------------

--
-- Table structure for table `users_2`
--

CREATE TABLE `users_2` (
  `id` int(10) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `dept_id` int(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_2`
--

INSERT INTO `users_2` (`id`, `firstName`, `lastName`, `dept_id`, `email`, `password`) VALUES
(5, 'Abdullah', 'Al Mamun', 220201034, 'abdullah@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b'),
(6, 'Sumon', 'Ahmed', 220201046, 'abdulkioumahmed@gmail.com', 'a284df1155ec3e67286080500df36a9a'),
(7, 'labib', 'ahsan', 220201019, 'labib@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b'),
(8, 'siam', 'Hasan', 220201045, 'mdrobiussanysiam@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b'),
(9, 'Sharmin', 'Akter Sumona', 2023123, 'sharminaktersumon@gmail.com', '5e90ae5f6eb12b3f3e3c86c0409de103'),
(10, 'Abdul', 'Fahad', 220211041, 'fajera9894@lance7.com', '827ccb0eea8a706c4c34a16891f84e7b'),
(11, 'Kakoly', 'akter', 2007, 'kakoly@gmail.com', '757b505cfd34c64c85ca5b5690ee5293'),
(12, 'Arif', 'islam', 220201010, 'arifulm234@gmail.com', 'e10adc3949ba59abbe56e057f20f883e'),
(13, 'Saiful', 'Islam', 2147483647, 'sisaifulislam7273@gmail.com', '9d702ffd99ad9c70ac37e506facc8c38');

-- --------------------------------------------------------

--
-- Table structure for table `users_3`
--

CREATE TABLE `users_3` (
  `user_id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `batch` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_3`
--

INSERT INTO `users_3` (`user_id`, `student_id`, `name`, `department`, `phone`, `batch`, `created_at`) VALUES
(7, '220201013', 'Anika', 'CSE', '013666454', '15', '2024-04-15 19:45:15'),
(10, '21311', 'vgchgchgcghc', 'chccghv', '3256111614', '15', '2024-04-16 04:49:28'),
(11, '220201013', 'wrrtygdssc', 'CSE', '099987654334', '15', '2024-04-16 05:16:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_loans`
--
ALTER TABLE `book_loans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `magazine`
--
ALTER TABLE `magazine`
  ADD PRIMARY KEY (`magazine_id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notices2`
--
ALTER TABLE `notices2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `previous_questions`
--
ALTER TABLE `previous_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reset_password`
--
ALTER TABLE `reset_password`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_2`
--
ALTER TABLE `users_2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_3`
--
ALTER TABLE `users_3`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `book_loans`
--
ALTER TABLE `book_loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `magazine`
--
ALTER TABLE `magazine`
  MODIFY `magazine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `notices2`
--
ALTER TABLE `notices2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `previous_questions`
--
ALTER TABLE `previous_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `reset_password`
--
ALTER TABLE `reset_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users_2`
--
ALTER TABLE `users_2`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users_3`
--
ALTER TABLE `users_3`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
