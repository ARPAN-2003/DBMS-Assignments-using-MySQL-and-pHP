-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Jan 07, 2026 at 02:01 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `AUTHOR_ID` varchar(50) NOT NULL,
  `NAME` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`AUTHOR_ID`, `NAME`) VALUES
('1', 'J.K. ROWLING'),
('2', 'GEORGE ORWELL'),
('3', 'ISAAC ASIMOV'),
('4', 'AGATHA CHRISTIE'),
('5', 'FRANK HERBERT');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `BOOK_ID` varchar(50) NOT NULL,
  `TITLE` varchar(200) NOT NULL,
  `GENRE` varchar(100) NOT NULL,
  `AUTHOR_ID` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`BOOK_ID`, `TITLE`, `GENRE`, `AUTHOR_ID`) VALUES
('1', 'Harry Potter', 'Fantasy', '1'),
('2', '1984', 'Dystopian', '2'),
('3', 'Foundation', 'Sci-Fi', '3'),
('4', 'I, Robot', 'Sci-Fi', '3'),
('5', 'Dune', 'Sci-Fi', '5'),
('6', 'Murder on the Orient Express', 'Mystery', '4'),
('7', 'Animal Farm', 'Satire', '2'),
('8', 'The Caves of Steel', 'Sci-Fi', '3');

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `LOAN_ID` varchar(50) NOT NULL,
  `BOOK_ID` varchar(50) NOT NULL,
  `MEMBER_ID` varchar(50) NOT NULL,
  `LOAN_DATE` date NOT NULL,
  `RETURN_DATE` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`LOAN_ID`, `BOOK_ID`, `MEMBER_ID`, `LOAN_DATE`, `RETURN_DATE`) VALUES
('1', '1', '1', '2024-01-01', '2024-01-15'),
('2', '2', '1', '2024-02-01', NULL),
('3', '3', '2', '2024-02-05', NULL),
('4', '4', '3', '2024-02-10', '2024-02-20'),
('5', '5', '4', '2024-03-01', NULL),
('6', '8', '1', '2024-03-05', NULL),
('7', '3', '1', '2024-01-20', '2024-02-01');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `MEMBER_ID` varchar(50) NOT NULL,
  `NAME` varchar(200) NOT NULL,
  `JOIN_DATE` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`MEMBER_ID`, `NAME`, `JOIN_DATE`) VALUES
('1', 'Alice', '2023-01-10'),
('2', 'Bob', '2023-05-20'),
('3', 'Charlie', '2024-02-15'),
('4', 'Diana', '2024-03-01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`AUTHOR_ID`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`BOOK_ID`),
  ADD KEY `AUTHOR_ID` (`AUTHOR_ID`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`LOAN_ID`),
  ADD KEY `BOOK_ID` (`BOOK_ID`),
  ADD KEY `MEMBER_ID` (`MEMBER_ID`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`MEMBER_ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`AUTHOR_ID`) REFERENCES `author` (`AUTHOR_ID`);

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`BOOK_ID`) REFERENCES `books` (`BOOK_ID`),
  ADD CONSTRAINT `loans_ibfk_2` FOREIGN KEY (`MEMBER_ID`) REFERENCES `members` (`MEMBER_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
