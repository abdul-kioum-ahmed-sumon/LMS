-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2025 at 05:14 AM
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
(40, 'Understanding Deep Learning', 'Simon J. D. Prince', '2023', '9780262048644', 1, 1, 'CSE-12', '2024-04-19 00:33:25', '2025-05-22 18:45:08'),
(41, 'C#', 'J.K. Rowling', '2012', '34342332', 1, 1, 'CSE-12', '2024-05-04 16:25:21', NULL),
(42, 'physic ssc', 'saiful', '2024', '999999', 7, 1, 'others', '2024-10-09 10:02:12', NULL),
(43, 'Software Engineering ', 'labid vai', '2021', '3423423545', 1, 1, 'CSE-12', '2025-05-22 17:11:06', NULL),
(44, 'Introduction to Data Science', 'Rachel Thomas', '2023', '9781234567890', 1, 1, 'CSE-15', '2025-05-22 21:47:13', NULL),
(45, 'Machine Learning Fundamentals', 'Andrew Ng', '2022', '9781234567891', 1, 1, 'CSE-16', '2025-05-22 21:47:13', NULL),
(46, 'Python for Data Analysis', 'Wes McKinney', '2022', '9781234567892', 1, 1, 'CSE-17', '2025-05-22 21:47:13', NULL),
(47, 'Deep Learning Revolution', 'Yoshua Bengio', '2023', '9781234567893', 1, 1, 'CSE-18', '2025-05-22 21:47:13', NULL),
(48, 'Modern Database Systems', 'Jennifer Widom', '2022', '9781234567894', 1, 1, 'CSE-19', '2025-05-22 21:47:13', NULL),
(49, 'Circuit Analysis', 'James Nilsson', '2021', '9781234567895', 2, 1, 'EEE-15', '2025-05-22 21:47:13', NULL),
(50, 'Power System Engineering', 'Alexandra Watson', '2021', '9781234567896', 2, 1, 'EEE-16', '2025-05-22 21:47:13', NULL),
(51, 'Renewable Energy Technologies', 'David Green', '2023', '9781234567897', 2, 1, 'EEE-17', '2025-05-22 21:47:13', NULL),
(52, 'Digital Signal Processing', 'John Smith', '2022', '9781234567898', 2, 1, 'EEE-18', '2025-05-22 21:47:13', NULL),
(53, 'Electronic Devices', 'Thomas Floyd', '2021', '9781234567899', 2, 1, 'EEE-19', '2025-05-22 21:47:13', NULL),
(54, 'Engineering Mechanics', 'Russell Hibbeler', '2021', '9781234567900', 3, 1, 'ME-14', '2025-05-22 21:47:13', NULL),
(55, 'Thermodynamics', 'Michael Moran', '2022', '9781234567901', 3, 1, 'ME-15', '2025-05-22 21:47:13', NULL),
(56, 'Fluid Mechanics', 'Frank White', '2021', '9781234567902', 3, 1, 'ME-16', '2025-05-22 21:47:13', NULL),
(57, 'Structural Analysis', 'Aslam Kassimali', '2023', '9781234567903', 4, 1, 'CE-01', '2025-05-22 21:47:13', NULL),
(58, 'Soil Mechanics', 'Karl Terzaghi', '2022', '9781234567904', 4, 1, 'CE-02', '2025-05-22 21:47:13', NULL),
(59, 'Marketing Principles', 'Philip Kotler', '2023', '9781234567905', 5, 1, 'BBA-10', '2025-05-22 21:47:13', NULL),
(60, 'Business Communication', 'Mary Ellen Guffey', '2022', '9781234567906', 5, 1, 'BBA-11', '2025-05-22 21:47:13', NULL),
(61, 'English Literature Anthology', 'William Shakespeare', '2021', '9781234567907', 6, 1, 'ENG-01', '2025-05-22 21:47:13', NULL),
(62, 'The Art of Public Speaking', 'Dale Carnegie', '2021', '9781234567908', 6, 1, 'ENG-02', '2025-05-22 21:47:13', NULL),
(63, 'World History: A New Perspective', 'David Roberts', '2022', '9781234567909', 7, 1, 'OTH-01', '2025-05-22 21:47:13', NULL);

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
  `issued_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `book_loans`
--

INSERT INTO `book_loans` (`id`, `book_id`, `student_id`, `loan_date`, `return_date`, `is_return`, `issued_at`, `created_at`, `updated_at`) VALUES
(21, 37, 8, '2024-04-24', '2024-04-27', 0, NULL, '2024-04-24 11:39:55', NULL),
(22, 30, 30, '2024-04-24', '2024-04-30', 1, NULL, '2024-04-24 11:54:41', NULL),
(28, 18, 40, '2025-05-22', '2025-06-05', 0, NULL, '2025-05-22 16:22:35', NULL),
(30, 34, 41, '2025-05-22', '2025-06-05', 1, '2025-05-22 20:26:55', '2025-05-22 16:26:31', '2025-05-22 20:26:55'),
(31, 38, 41, '2025-05-22', '2025-06-05', 1, '2025-05-22 20:33:39', '2025-05-22 16:33:20', '2025-05-22 20:33:39'),
(32, 41, 41, '2025-05-22', '2025-06-05', 1, '2025-05-22 20:35:37', '2025-05-22 16:35:26', '2025-05-22 20:35:37'),
(33, 22, 41, '2025-05-22', '2025-06-05', 1, '2025-05-22 20:54:42', '2025-05-22 16:48:14', '2025-05-22 20:54:42'),
(34, 33, 41, '2025-05-22', '2025-06-05', 1, '2025-05-22 20:55:21', '2025-05-22 16:54:57', '2025-05-22 20:55:21'),
(35, 40, 41, '2025-05-22', '2025-06-05', 1, '2025-05-22 20:55:53', '2025-05-22 16:55:42', '2025-05-22 20:55:53'),
(36, 20, 41, '2025-05-22', '2025-06-05', 1, '2025-05-22 21:00:53', '2025-05-22 17:00:38', '2025-05-22 21:00:53'),
(37, 43, 41, '2025-05-22', '2025-06-05', 1, '2025-05-22 21:12:36', '2025-05-22 17:11:36', '2025-05-22 21:12:36'),
(38, 38, 11, '2025-05-23', '2025-05-31', 0, NULL, '2025-05-22 17:18:48', NULL),
(39, 34, 43, '2025-05-22', '2025-06-05', 1, '2025-05-22 21:27:39', '2025-05-22 17:27:01', '2025-05-22 21:27:39'),
(41, 36, 44, '2025-05-22', '2025-06-05', 1, '2025-05-22 21:34:04', '2025-05-22 17:32:14', '2025-05-22 21:34:04'),
(43, 35, 44, '2025-05-22', '2025-06-05', 1, '2025-05-22 21:41:50', '2025-05-22 17:41:24', '2025-05-22 21:41:50'),
(44, 60, 44, '2025-05-22', '2025-06-05', 0, '2025-05-22 21:49:36', '2025-05-22 17:49:12', '2025-05-22 21:49:36'),
(45, 48, 41, '2025-05-23', '2025-06-06', 0, '2025-05-22 22:30:08', '2025-05-22 18:29:51', '2025-05-22 22:30:08'),
(46, 40, 41, '2025-05-23', '2025-06-06', 0, '2025-05-22 22:46:00', '2025-05-22 18:45:27', '2025-05-22 22:46:00'),
(47, 39, 45, '2025-05-23', '2025-06-06', 0, NULL, '2025-05-23 11:24:22', NULL),
(48, 33, 45, '2025-05-23', '2025-06-06', 0, '2025-05-23 15:25:59', '2025-05-23 11:24:47', '2025-05-23 15:25:59'),
(49, 53, 45, '2025-05-23', '2025-06-06', 1, '2025-05-23 15:27:47', '2025-05-23 11:27:25', '2025-05-23 15:27:47'),
(50, 30, 46, '2025-05-23', '2025-06-06', 1, '2025-05-23 16:08:54', '2025-05-23 12:07:42', '2025-05-23 16:08:54'),
(51, 49, 46, '2025-05-23', '2025-06-06', 0, '2025-05-23 16:10:32', '2025-05-23 12:10:13', '2025-05-23 16:10:32'),
(52, 53, 46, '2025-05-23', '2025-06-06', 0, '2025-05-23 16:11:02', '2025-05-23 12:10:37', '2025-05-23 16:11:02');

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
  `password` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
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

INSERT INTO `students` (`id`, `name`, `phone_no`, `email`, `password`, `address`, `status`, `verified`, `created_at`, `updated_at`, `dept_id`, `dept`, `reg_no`, `Username`) VALUES
(41, 'Abdul Kyum Ahmed Sumon', '01309897349', 'abdulkioumahmed@gmail.com', '$2y$10$f4dVuE1Gpj0RE2iJmLrxTeusEEZg/fTBwuSa03po.tJh3.xQIyhDW', 'Joypurhat', 1, 1, '2025-05-22 16:25:13', NULL, '220201046', 'CSE', NULL, NULL),
(43, 'Siam Hasan', '01532213132', 'siamhasan@baust', '$2y$10$qW4to5KJnAMDYh8mHB1u9et8beu87J84LNO.lLd3wlKVt1LqWYjmC', 'rongpur', 1, 1, '2025-05-22 17:22:08', NULL, '220201045', 'CSE', NULL, NULL),
(44, 'Fahad', '0123232123', 'fahad@gmail.com', '$2y$10$vMsvBrD1cZmNT916lx5/Dez4ppSnN5ziRZ224ztWWz8Wr392NaUDC', 'Nougan', 1, 1, '2025-05-22 17:29:11', NULL, '2201232321', 'BBA', NULL, NULL),
(45, 'Md. Labib Ahsan ', '9281742189709', 'labib@gmail.com', '$2y$10$x6GbeeLjM8dHqsYisTXfl.6gihTjoxWcy9cC7p.VX2.5.7KARauQO', 'rangpur', 1, 1, '2025-05-23 11:22:38', NULL, '220201019', 'CSE', NULL, NULL),
(46, 'Abdullah ', '2432432432', 'as@gmail.com', '$2y$10$e9tZlHETt4qVPbz508Z3OuR22hZRf4I2SGyuf0oWdCmqrRxvOs5PC', 'Nilphamari', 1, 1, '2025-05-23 12:06:28', '2025-05-23 23:13:09', '220201034', 'CSE', NULL, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `book_loans`
--
ALTER TABLE `book_loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

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
