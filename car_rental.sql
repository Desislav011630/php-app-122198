-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2024 at 10:53 AM
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
-- Database: `car_rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `make` varchar(50) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `price_per_day` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `make`, `model`, `year`, `price_per_day`, `image_url`) VALUES
(1, 'BMW', '320d', 2009, 80.00, 'https://www.auto-data.net/images/f62/BMW-3-Series-Sedan-E90-facelift-2009.jpg'),
(7, 'audi', 'a3', 2024, 250.00, 'https://myve.bg/f/news/b/53/183d0bce4ba38ec27c6d4a291a911edf.jpeg'),
(8, 'audi', 'rs6 ABT', 2024, 1300.00, 'https://img.gta5-mods.com/q95/images/audi-rs6-avant-c8-sound/693f62-abt-audi-rs-6.jpg'),
(9, 'BMW', 'M5 CS', 2022, 1300.00, 'https://cdn.motor1.com/images/mgl/YMnev/s1/2022-bmw-m5-cs-front-view.webp'),
(10, 'Dodge', 'Challenger', 2023, 1100.00, 'https://media.ed.edmunds-media.com/dodge/challenger/2023/oem/2023_dodge_challenger_coupe_gt_fq_oem_1_1600.jpg'),
(11, 'Ford', 'Mustang GT500', 2023, 600.00, 'https://dizzyriders.bg/uploads/thumbs/avtomobili/1_2019/2019-5/mustang-shelby-lead-1080x600.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `car_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `rent_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(3, 'user', 'asdasd@gmail.com', '$2y$10$A95Qxx6ek6BYob/KbpT.bufepJ4ywwjlsNpZOikVcxkJOBsxZLNEO', 'user', '2024-12-16 20:54:01'),
(4, 'desislav', '122198@students.ue-varna.bg', '$2y$10$R5t9.ilpLOMuEehed9olwOiMJ6tlGyXubCAOD655i4bS8j9iARFOm', 'admin', '2024-12-16 21:25:18'),
(5, 'deskod', 'desko_tedii@abv.bg', '$2y$10$LvsBYQABrGvo5ZyhNTu53.9iQyytiM5rTvoallvlyL4KrF9t0jez2', 'user', '2024-12-17 09:35:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
