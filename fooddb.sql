-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2026 at 02:35 PM
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
-- Database: `fooddb`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `cartId` int(11) NOT NULL,
  `userId` int(10) UNSIGNED NOT NULL,
  `foodId` int(11) NOT NULL DEFAULT 1,
  `quantity` int(11) DEFAULT 1,
  `addedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

CREATE TABLE `foods` (
  `foodId` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `image` varchar(255) NOT NULL,
  `categoryId` int(10) UNSIGNED NOT NULL,
  `active` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `foods`
--

INSERT INTO `foods` (`foodId`, `name`, `description`, `price`, `image`, `categoryId`, `active`) VALUES
(11, 'burger king', 'burger king', 1, '6984aad3c766e.jpg', 1, 'active'),
(12, 'Cannot Eat this Salad', 'i am a salad', 100, '698714ba094cd.jpg', 2, 'active'),
(13, 'Fried Chicken', 'fried chicken', 10, '6984b6572bc6e.jpg', 1, 'inactive'),
(14, 'A Pizza!', 'this is a drink', 18, '69873f462b49f.jpg', 3, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `food_categories`
--

CREATE TABLE `food_categories` (
  `categoryId` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `active` enum('visible','invisible') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `food_categories`
--

INSERT INTO `food_categories` (`categoryId`, `title`, `active`) VALUES
(1, 'Burgers', 'visible'),
(2, 'Salads', 'invisible'),
(3, 'Drinks', 'visible');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderId` int(10) UNSIGNED NOT NULL,
  `status` enum('pending','paid','progress','done','shipping','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `address` varchar(255) NOT NULL,
  `state` varchar(50) NOT NULL,
  `district` varchar(50) NOT NULL,
  `userId` int(10) UNSIGNED NOT NULL,
  `extraNotes` varchar(255) NOT NULL,
  `totalAmount` decimal(10,2) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderId`, `status`, `address`, `state`, `district`, `userId`, `extraNotes`, `totalAmount`, `createdAt`) VALUES
(12, 'paid', 'this is an address', 'Kuala Lumpur', 'aa', 5, '...', 36.00, '2026-02-07 13:56:53'),
(13, '', 'ayeh keloh', 'Melaka', 'Ayeh Keloh', 5, '', 200.00, '2026-02-07 14:02:41'),
(14, 'paid', 'dfsdfd', 'Labuan', 'ddd', 5, 'fdfsd', 319.00, '2026-02-07 14:11:29'),
(15, 'paid', 'taman next time, jalan time', 'Johor', 'Batu Pahat', 8, 'give me more salad', 700.00, '2026-02-08 12:23:35');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `orderItemId` int(10) UNSIGNED NOT NULL,
  `orderId` int(10) UNSIGNED NOT NULL,
  `foodId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `priceAtPurchase` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`orderItemId`, `orderId`, `foodId`, `quantity`, `priceAtPurchase`) VALUES
(24, 12, 14, 2, 18.00),
(25, 13, 12, 2, 100.00),
(26, 14, 11, 1, 1.00),
(27, 14, 12, 3, 100.00),
(28, 14, 14, 1, 18.00),
(29, 15, 12, 7, 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(10) UNSIGNED NOT NULL,
  `fullName` varchar(150) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contactNumber` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `confirmPassword` varchar(255) NOT NULL,
  `role` enum('user','staff','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `fullName`, `username`, `email`, `address`, `contactNumber`, `password`, `confirmPassword`, `role`) VALUES
(5, 'Kieran', 'kieran', 'kieran@kieran.com', 'John Doe, Melaka', '012-3454831', '$2y$10$jl8zrXJFHIavyRKfBy5uveU2SWN86qo0H1l6d5FjwWH4WK0dWxn46', '', 'admin'),
(6, 'Kieran Ong', 'kieran ong', 'KIERAN.ONG.ZHI@student.mmu.edu.my', 'MMU MELAKA', '012-3456789', '$2y$10$NProNbj.OqwXzbdOJkEbyeYTPvAVyNrilkSiGE.G/cronjJTwcVWG', '', 'user'),
(7, 'TAY', 'TAY@', 'tay@gmail.com', 'John Doe, Malaysia', '012-4937127', '$2y$10$mt4Zi7VNLlo4pwiIc17GzutRYspZ2w5GYsDObvGo5meRGp02kfr02', '', 'staff'),
(8, 'Daniel', 'Pua', 'danielpua@gmail.com', 'taman next time, jalan time', '012-3456789', '$2y$10$/MDrkvVuRTTGHqMrTRXp1OlXmJRXW5IX6GmQAEaTJSPbON8IJ4PYy', '', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cartId`);

--
-- Indexes for table `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`foodId`);

--
-- Indexes for table `food_categories`
--
ALTER TABLE `food_categories`
  ADD PRIMARY KEY (`categoryId`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderId`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`orderItemId`),
  ADD KEY `orderId` (`orderId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `cartId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `foodId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `food_categories`
--
ALTER TABLE `food_categories`
  MODIFY `categoryId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `orderItemId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
