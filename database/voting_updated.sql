-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 29, 2025 at 07:08 AM
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
-- Database: `voting`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'ssvps', 'ssvps@gmail.com', 'pass@123');

-- --------------------------------------------------------

--
-- Table structure for table `candidate`
--

CREATE TABLE `candidate` (
  `id` int(11) NOT NULL,
  `cname` varchar(50) NOT NULL,
  `symbol` varchar(50) NOT NULL,
  `symphoto` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `tvotes` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidate`
--

INSERT INTO `candidate` (`id`, `cname`, `symbol`, `symphoto`, `position`, `tvotes`) VALUES
(20, 'Mohan Sharma', 'Mobile', 'symbol/e3.png', 'Secretary', 3),
(21, 'Rohit Misra', 'Football', 'symbol/s2.png', 'Chairman', 6),
(22, 'Aniket Sharma', 'Camera', 'symbol/e4.png', 'Secretary', 6),
(23, 'Vivek Joshi', 'Helmet', 'symbol/a6.png', 'Chairman', 4),
(24, 'Vivek Varma', 'Wheel', 'symbol/a5.png', 'Chairman', 10),
(25, 'Kamalesh Pawar', 'Tree', 'symbol/tree.jfif', 'Secretary', 8),
(26, 'Ajinkya Patil', 'Computer', 'symbol/cmptr.jfif', 'Chairman', 7),
(27, 'Vaibhav Sonar', 'Helicopter', 'symbol/helicptr.jfif', 'Secretary', 10);

-- --------------------------------------------------------

--
-- Table structure for table `can_position`
--

CREATE TABLE `can_position` (
  `id` int(255) NOT NULL,
  `position_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `can_position`
--

INSERT INTO `can_position` (`id`, `position_name`) VALUES
(1, 'Chairman'),
(2, 'Secretary');

-- --------------------------------------------------------

--
-- Table structure for table `phno_change`
--

CREATE TABLE `phno_change` (
  `id` int(255) NOT NULL,
  `vname` varchar(50) NOT NULL,
  `idname` varchar(20) NOT NULL,
  `idcard` varchar(300) NOT NULL,
  `dob` varchar(50) NOT NULL,
  `old_phno` varchar(15) NOT NULL,
  `new_phno` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phno_change`
--

INSERT INTO `phno_change` (`id`, `vname`, `idname`, `idcard`, `dob`, `old_phno`, `new_phno`) VALUES
(5, 'Rohan Khairnar', 'Aadhar', 'phnochange/Aadhar-Card-UIDAI-.jpg', '2003-03-11', '7420087741', '9576564324'),
(6, 'Prashant Patil', 'other Id Proof', 'phnochange/4.jpeg', '2003-01-09', '9562663773', '8377266277');

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `id` int(255) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `idname` varchar(50) NOT NULL,
  `idnum` varchar(50) NOT NULL,
  `idcard` varchar(300) NOT NULL,
  `inst_id` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(10) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` varchar(100) NOT NULL,
  `verify` varchar(10) NOT NULL,
  `status` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`id`, `fname`, `lname`, `idname`, `idnum`, `idcard`, `inst_id`, `dob`, `gender`, `phone`, `address`, `verify`, `status`) VALUES
(110, 'vincent', 'Bett', 'Other ID Card', '37482712', 'img/0aliens_plot.png', '23456789', '1998-06-26', 'male', '0737544766', '', 'no', 'not voted'),
(122, 'vincent', 'Kip', 'Other ID Card', '32445678', 'img/1aliens_plot.png', '23456787', '1999-02-09', 'male', '0702502955', '', 'no', 'not voted'),
(123, 'vincent', 'Kip', 'Other ID Card', '32445670', 'img/2aliens_plot.png', '23456784', '1999-02-09', 'male', '0738502955', '', 'no', 'not voted');

-- --------------------------------------------------------

--
-- Table structure for table `voting`
--

CREATE TABLE `voting` (
  `id` int(50) NOT NULL,
  `voting_title` varchar(50) NOT NULL,
  `vot_start_date` datetime NOT NULL,
  `vot_end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voting`
--

INSERT INTO `voting` (`id`, `voting_title`, `vot_start_date`, `vot_end_date`) VALUES
(1, 'Voting 2022', '2022-06-01 17:01:00', '2022-06-01 17:18:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `candidate`
--
ALTER TABLE `candidate`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `symbol` (`symbol`);

--
-- Indexes for table `can_position`
--
ALTER TABLE `can_position`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phno_change`
--
ALTER TABLE `phno_change`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `idnum` (`idnum`),
  ADD UNIQUE KEY `inst_id` (`inst_id`);

--
-- Indexes for table `voting`
--
ALTER TABLE `voting`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `candidate`
--
ALTER TABLE `candidate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `can_position`
--
ALTER TABLE `can_position`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `phno_change`
--
ALTER TABLE `phno_change`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `voting`
--
ALTER TABLE `voting`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
