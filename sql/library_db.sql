-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2026 at 02:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `available` tinyint(1) DEFAULT 1,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `genre`, `available`, `added_on`) VALUES
(1, 'The Great Gatsby', 'F. Scott Fitzgerald', 'Classic', 1, '2026-04-10 02:44:52'),
(2, 'The Alchemist', 'Paulo Coelho', 'Fiction', 1, '2026-04-10 02:44:52'),
(3, 'Clean Code', 'Robert C. Martin', 'Programming', 1, '2026-04-10 02:44:52'),
(4, 'Cinderella', 'Mona', 'Fiction', 1, '2026-04-10 02:44:52'),
(5, 'The Lion King', 'Les', 'Fiction', 1, '2026-04-10 03:40:58'),
(6, 'HHHHHHHH', 'HHHH', 'HHHH', 1, '2026-04-10 03:57:01'),
(7, 'Olympus', 'Mc Arthur', 'non fiction', 1, '2026-04-10 09:18:57'),
(8, 'bbbb', 'bbbb', 'bbbb', 1, '2026-04-10 11:13:46'),
(9, 'bbbb', 'bbbb', 'bbbb', 1, '2026-04-10 11:14:23'),
(10, 'nn', 'nn', 'nn', 1, '2026-04-10 11:15:22'),
(11, 'bb', 'aa', 'aa', 1, '2026-04-10 11:15:38'),
(12, 'bb', 'aa', 'aa', 1, '2026-04-10 11:15:52'),
(13, 'bb', 'aa', 'aa', 1, '2026-04-10 11:16:36'),
(14, 'The Rat', 'Lester Emuslan', 'fiction', 1, '2026-04-10 11:17:09'),
(15, 'The Rat', 'Lester Emuslan', 'fiction', 1, '2026-04-10 11:18:13'),
(16, 'The Wolf', 'Via', 'Fiction', 1, '2026-04-10 11:18:58'),
(17, 'vv', 'vv', 'vv', 1, '2026-04-10 11:20:00'),
(18, 'lim', 'lim', 'lim', 1, '2026-04-10 11:33:52');

-- --------------------------------------------------------

--
-- Table structure for table `issued_books`
--

CREATE TABLE `issued_books` (
  `id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `student_sid` varchar(20) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issued_books`
--

INSERT INTO `issued_books` (`id`, `book_id`, `student_sid`, `issue_date`, `return_date`) VALUES
(6, 3, 'STU1004', '2026-04-10', '2026-04-10'),
(7, 6, 'STU1002', '2026-04-10', '2026-04-10'),
(8, 1, 'STU1002', '2026-04-10', '2026-04-10'),
(9, 5, 'STU1008', '2026-04-10', '2026-04-10'),
(10, 7, 'STU1010', '2026-04-10', NULL),
(11, 2, 'STU1009', '2026-04-10', NULL),
(12, 9, 'STU1009', '2026-04-10', NULL),
(13, 11, 'STU1008', '2026-04-10', NULL),
(14, 1, 'STU1006', '2026-04-10', NULL),
(15, 18, 'STU1007', '2026-04-10', '2026-04-10'),
(16, 7, 'STU1007', '2026-04-10', NULL),
(17, 5, 'STU1012', '2026-04-10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`) VALUES
(1, 'Juan Dela Cruz', 'juan@gmail.com'),
(2, 'Maria Santos', 'maria@gmail.com'),
(3, 'John Doe', 'john@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `issued_books`
--
ALTER TABLE `issued_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `student_id` (`student_sid`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `issued_books`
--
ALTER TABLE `issued_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `issued_books`
--
ALTER TABLE `issued_books`
  ADD CONSTRAINT `issued_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
