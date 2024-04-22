-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2024 at 12:15 PM
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
-- Database: `library`
--

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
(61, 'Climate Change', 'BAUST Authority', '2024-04-17', 'Nature', 'Bengali', '4587', 'assets/Lecture2.pdf'),
(62, 'Climate Change', 'BAUST Authority', '2024-04-10', 'Nature', 'Bengali', '4587', 'assets/Lecture2.pdf');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `magazine`
--
ALTER TABLE `magazine`
  ADD PRIMARY KEY (`magazine_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `magazine`
--
ALTER TABLE `magazine`
  MODIFY `magazine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
