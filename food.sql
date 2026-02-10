-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2025 at 01:14 PM
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
-- Database: `food`
--

-- --------------------------------------------------------

--
-- Table structure for table `food_items`
--

CREATE TABLE `food_items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_items`
--

INSERT INTO `food_items` (`id`, `name`, `description`, `price`, `image`, `available`) VALUES
(1, 'wiuuu', 'tann', 23.00, '1600652880_steak.jpg', 1),
(3, 'admin', 'KUKU MAYAI', 1000.00, '1600654500_cover_2.jpg', 1),
(4, 'ugali vs kuku', 'rosti, mcheusho', 200.00, '1600656600_checken2.jpg', 1),
(5, 'chips', 'zege,kavu', 2.00, '1600654500_cover_2.jpg', 1),
(6, 'juisi', 'papai', 1.00, '1600654680_photo-1504674900247-0877df9cc836.jpg', 1),
(7, 'wali', '', 1500.00, 'f3.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `guest_info`
--

CREATE TABLE `guest_info` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guest_info`
--

INSERT INTO `guest_info` (`id`, `order_id`, `fullname`, `email`, `phone`, `address`) VALUES
(1, 1, 'GODY EZEKIEL', 'godyezekiel35@gmail.com', '0754749485', 'Temeke'),
(2, 2, 'GODY EZEKIEL', 'gody12919@gmail.com', '0765047351', 'Temeke'),
(3, 3, 'GODY EZEKIEL', 'godyezekiel35@gmail.com', '0765047351', 'Temeke'),
(4, 4, 'GODY EZEKIEL', 'godyezekiel35@gmail.com', '0754749485', 'Temeke'),
(5, 5, 'GODY EZEKIEL', 'gody12919@gmail.com', '0627760279', 'Temeke'),
(6, 6, 'GODY EZEKIEL', 'gody12919@gmail.com', '005321', 'Temeke'),
(7, 7, 'nyang\'ango', 'archimede\'s@gmail.com', '123', 'Temeke'),
(8, 8, 'GODY EZEKIEL', 'rozachera@gmail.com', '2222222222', 'Temeke'),
(9, 9, 'jose', 'jose@gmail.com', '1234', 'ukonga'),
(10, 10, 'ouwa', 'ouwa12@gmail.com', '09876', 'rabuor'),
(11, 11, 'asia', 'rozachera@gmail.com', '0000000000', 'keko'),
(12, 12, 'unoke', 'unoke@gmail.com', '77777', 'mtwara'),
(13, 13, 'yassin nassoro', 'yassin@gmail.com', '456789', 'mbagala'),
(14, 14, 'MJEMA', 'yasinsalum60@gmail.com', '+255754377312', 'home\r\n'),
(15, 15, 'MJEMA', 'yasinsalum60@gmail.com', '+255754377312', 'HOME'),
(16, 16, 'KEVI', 'kevi@gmail.com', '0789303030', 'CBE'),
(17, 17, 'Salma Salum Juma', 'sophialiyaya@gmail.com', '00000000', 'kigamboni'),
(18, 18, 'Salma Salum Juma', 'sophialiyaya@gmail.com', '00000000', 'kigamboni'),
(19, 19, 'ibra moust', 'rajabumoust123@gmail.com', '123456789', 'Goms');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `phone`, `email`, `content`, `created_at`) VALUES
(1, 'GODY EZEKIEL', '0754749485', 'dabiel@gmail.com', 'dddd', '2025-05-18 10:18:35'),
(2, 'GODY EZEKIEL', '0754749485', 'archimede\\\'s@gmail.com', 'rrrrrrrrrtyuii', '2025-05-19 11:35:56'),
(3, 'GODY EZEKIEL', '0754749485', 'archimede\\\'s@gmail.ran', 'rrrrrrrrrtyuii', '2025-05-19 11:36:15');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` enum('pending','accepted','dispatched','delivered') DEFAULT 'pending',
  `order_token` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `status`, `order_token`, `created_at`) VALUES
(1, NULL, 'pending', NULL, '2025-05-18 09:19:03'),
(2, NULL, 'delivered', NULL, '2025-05-18 09:19:31'),
(3, NULL, 'pending', NULL, '2025-05-18 09:21:35'),
(4, NULL, 'delivered', NULL, '2025-05-18 09:27:03'),
(5, NULL, 'pending', NULL, '2025-05-18 09:32:37'),
(6, NULL, 'delivered', NULL, '2025-05-18 09:46:43'),
(7, NULL, 'delivered', NULL, '2025-05-18 10:56:13'),
(8, NULL, 'pending', NULL, '2025-05-19 10:46:55'),
(9, NULL, 'pending', 'A6D748728A682C141BB6F18', '2025-05-20 05:33:15'),
(10, NULL, 'pending', 'EE74EC7620682C1A08DC908', '2025-05-20 05:58:32'),
(11, NULL, 'pending', '908339FAD5682C42B663897', '2025-05-20 08:52:06'),
(12, NULL, 'pending', '35720C5B17682C444189E38', '2025-05-20 08:58:41'),
(13, NULL, 'pending', '1F71F9D57F682C75F338ABF', '2025-05-20 12:30:43'),
(14, NULL, 'pending', '9ED547A34C682C7B0E61329', '2025-05-20 12:52:30'),
(15, NULL, 'pending', '59C0A34AD6682C873F04556', '2025-05-20 13:44:31'),
(16, NULL, 'pending', 'FE13292DD1682CA0660A310', '2025-05-20 15:31:50'),
(17, NULL, 'delivered', '9571E4DB0468308F0779630', '2025-05-23 15:06:47'),
(18, NULL, 'pending', 'AA1364314C68308FD3859DB', '2025-05-23 15:10:11'),
(19, NULL, 'pending', '686AB941056830A41C41ABF', '2025-05-23 16:36:44');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `food_item_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `food_item_id`, `quantity`) VALUES
(1, 1, 1, 3),
(2, 1, 3, 1),
(3, 2, 3, 1),
(4, 3, 3, 1),
(5, 4, 3, 4),
(6, 5, 1, 1),
(7, 6, 1, 1),
(8, 7, 1, 1),
(9, 8, 3, 1),
(10, 8, 4, 1),
(11, 9, 1, 1),
(12, 10, 1, 1),
(13, 11, 3, 1),
(14, 12, 3, 1),
(15, 13, 4, 1),
(16, 14, 3, 4),
(17, 14, 4, 1),
(18, 15, 3, 8),
(19, 16, 1, 1),
(20, 17, 3, 1),
(21, 18, 3, 1),
(22, 19, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `about_us` text DEFAULT NULL,
  `homepage_image` varchar(255) DEFAULT NULL,
  `about_us_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `about_us`, `homepage_image`, `about_us_image`) VALUES
(1, 'qwertyui\r\nasdfghjk', '../uploads/1747297680_food-bg.jpg', '../uploads/1600654380_cover 2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','staff','user') DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `image`, `created_at`) VALUES
(1, 'admin', 'gody12919@gmail.com', 'gody123', 'admin', NULL, '2025-05-17 10:49:26'),
(2, 'admin', 'godyezekiel35@gmail.com', '223344', 'admin', 'uploads/1747658615_images (6).jpeg', '2025-05-17 11:06:51'),
(9, 'joe', 'archimede\'s@gmail.com', 'ouwa grs', 'staff', NULL, '2025-05-19 11:23:29'),
(11, NULL, 'ouwa12@gmail.com', '12345', NULL, NULL, '2025-05-20 12:22:47'),
(14, 'wanu', 'wanu@gmail.com', 'asdfghjk', 'staff', NULL, '2025-05-24 14:17:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `food_items`
--
ALTER TABLE `food_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guest_info`
--
ALTER TABLE `guest_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_token` (`order_token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `food_item_id` (`food_item_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
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
-- AUTO_INCREMENT for table `food_items`
--
ALTER TABLE `food_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `guest_info`
--
ALTER TABLE `guest_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `guest_info`
--
ALTER TABLE `guest_info`
  ADD CONSTRAINT `guest_info_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`food_item_id`) REFERENCES `food_items` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
