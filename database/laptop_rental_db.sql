-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2024 at 04:48 PM
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
-- Database: `laptop_rental_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `laptop`
--

CREATE TABLE `laptop` (
  `laptop_id` int(11) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `rental_fee` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laptop`
--

INSERT INTO `laptop` (`laptop_id`, `brand`, `model`, `rental_fee`) VALUES
(6, 'Asus', 'TUFF', 380.00),
(7, 'HP', 'Omen', 380.00),
(8, 'Lenovo', 'Legion', 360.00),
(9, 'Acer', 'Aspire', 180.00);

-- --------------------------------------------------------

--
-- Table structure for table `rental`
--

CREATE TABLE `rental` (
  `rental_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `laptop_id` int(11) DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `is_returned` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rental`
--

INSERT INTO `rental` (`rental_id`, `user_id`, `laptop_id`, `date_from`, `date_to`, `is_returned`) VALUES
(68, 8, 6, '2024-01-05', '2024-01-13', 1),
(69, 8, 7, '2024-01-05', '2024-01-22', 1),
(70, 8, 8, '2024-01-13', '2024-01-29', 1),
(71, 8, 9, '2024-01-12', '2024-02-09', 1),
(72, 8, 6, '2024-01-05', '2024-01-13', 1),
(73, 9, 7, '2024-01-19', '2024-01-23', 1),
(74, 8, 8, '2024-01-11', '2024-01-13', 1),
(75, 9, 6, '2024-01-08', '2024-01-18', 1),
(76, 8, 8, '2024-01-12', '2024-01-30', 1),
(77, 8, 7, '2024-01-07', '2024-01-22', 1),
(78, 8, 6, '2024-01-08', '2024-01-17', 1),
(79, 8, 8, '2024-01-18', '2024-01-20', 1),
(80, 8, 7, '2024-01-09', '2024-01-31', 1),
(81, 8, 8, '2024-01-13', '2024-01-14', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_account`
--

CREATE TABLE `user_account` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_account`
--

INSERT INTO `user_account` (`user_id`, `first_name`, `last_name`, `username`, `password`, `user_type`) VALUES
(2, 'Jane', 'Doe', 'admin', '$2y$10$VqX3npPPCfaXzG6q4a6hleyYcLglscdaszmbcwtklfEaBOv33fI7W', 'Admin'),
(8, 'Ahmed Rashad', 'Jamion', 'jamionahmed', '$2y$10$2oLgnoz2Am1h9GSFF9ociOr2VmrBw6s38koGyvUCEDkpfC.Ol0OQS', 'Customer'),
(9, 'ahmed', 'jamion', 'ahmed', '$2y$10$LQAWQnYAwJSDEr2HF8u9ou3Zk0rM3T/aN2/Op1yw2ulzimURPwZMK', 'Customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `laptop`
--
ALTER TABLE `laptop`
  ADD PRIMARY KEY (`laptop_id`);

--
-- Indexes for table `rental`
--
ALTER TABLE `rental`
  ADD PRIMARY KEY (`rental_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `laptop_id` (`laptop_id`);

--
-- Indexes for table `user_account`
--
ALTER TABLE `user_account`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `laptop`
--
ALTER TABLE `laptop`
  MODIFY `laptop_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `rental`
--
ALTER TABLE `rental`
  MODIFY `rental_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `user_account`
--
ALTER TABLE `user_account`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rental`
--
ALTER TABLE `rental`
  ADD CONSTRAINT `laptop_id` FOREIGN KEY (`laptop_id`) REFERENCES `laptop` (`laptop_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user_account` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
