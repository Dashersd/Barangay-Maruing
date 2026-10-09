-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2026 at 01:55 PM
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
-- Database: `barangay_maruing`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` enum('published','draft','archived') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barangay_information`
--

CREATE TABLE `barangay_information` (
  `id` int(11) NOT NULL,
  `barangay_name` varchar(100) NOT NULL,
  `municipality` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barangay_information`
--

INSERT INTO `barangay_information` (`id`, `barangay_name`, `municipality`, `province`, `description`, `address`, `contact_number`, `email`, `created_at`, `updated_at`) VALUES
(1, 'Maruing', 'Lapuyan', 'Zamboanga del Sur', 'Welcome to the official information system of Barangay Maruing.', 'Barangay Hall, Maruing, Lapuyan, Zamboanga del Sur', '09123456789', 'info@barangaymaruing.com', '2026-08-28 12:44:13', '2026-08-28 12:44:13');

-- --------------------------------------------------------

--
-- Table structure for table `officials`
--

CREATE TABLE `officials` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sm_legends`
--

CREATE TABLE `sm_legends` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon_path` varchar(255) DEFAULT NULL,
  `color_hex` varchar(7) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sm_legends`
--

INSERT INTO `sm_legends` (`id`, `name`, `icon_path`, `color_hex`, `is_active`) VALUES
(1, 'Household', NULL, '#00FF00', 1),
(2, 'School', NULL, '#0000FF', 1),
(3, 'Sari-Sari Store', NULL, '#FFA500', 1),
(4, 'Chapel', NULL, '#800080', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sm_legend_spots`
--

CREATE TABLE `sm_legend_spots` (
  `id` int(11) NOT NULL,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sm_purok_1_spots`
--

CREATE TABLE `sm_purok_1_spots` (
  `id` int(11) NOT NULL,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sm_purok_1_spots`
--

INSERT INTO `sm_purok_1_spots` (`id`, `house_number`, `husband_name`, `spouse_name`, `legend_id`, `title`, `details`, `marker_width`, `marker_height`, `top_position`, `left_position`, `marker_image`, `house_image`, `pos_x`, `pos_y`, `created_at`) VALUES
(1, '565', 'asd', 'gds', NULL, '', NULL, 40, 40, 41.1600, 52.5000, 'uploads/markers/1791422572_Icon2.png', 'uploads/houses/1791422572_house_ChatGPTImageSep27202611_53_21AM.png', NULL, NULL, '2026-10-08 01:22:52'),
(2, '46754', 'Dash', 'Dahsesa', NULL, '', NULL, 40, 40, 59.4300, 33.4800, 'uploads/markers/1791424545_Icon2.png', 'uploads/houses/1791424545_house_images.jpg', NULL, NULL, '2026-10-08 01:55:45');

-- --------------------------------------------------------

--
-- Table structure for table `sm_purok_2_spots`
--

CREATE TABLE `sm_purok_2_spots` (
  `id` int(11) NOT NULL,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sm_purok_2_spots`
--

INSERT INTO `sm_purok_2_spots` (`id`, `house_number`, `husband_name`, `spouse_name`, `legend_id`, `title`, `details`, `marker_width`, `marker_height`, `top_position`, `left_position`, `marker_image`, `house_image`, `pos_x`, `pos_y`, `created_at`) VALUES
(1, '31', 'Helloo', 'Malupiton', NULL, '', NULL, 40, 40, 36.3200, 53.1400, 'uploads/markers/1791422755_Icon2.png', 'uploads/houses/1791422755_house_TabonBarangayhall.jpg', NULL, NULL, '2026-10-08 01:25:55');

-- --------------------------------------------------------

--
-- Table structure for table `sm_purok_3_spots`
--

CREATE TABLE `sm_purok_3_spots` (
  `id` int(11) NOT NULL,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sm_purok_3_spots`
--

INSERT INTO `sm_purok_3_spots` (`id`, `house_number`, `husband_name`, `spouse_name`, `legend_id`, `title`, `details`, `marker_width`, `marker_height`, `top_position`, `left_position`, `marker_image`, `house_image`, `pos_x`, `pos_y`, `created_at`) VALUES
(1, '234', 'nfd', 'as', NULL, '', NULL, 40, 40, 50.0000, 50.0000, 'uploads/markers/1791423250_images.jpg', 'uploads/houses/1791423250_house_images.jpg', NULL, NULL, '2026-10-08 01:34:10');

-- --------------------------------------------------------

--
-- Table structure for table `sm_purok_4_spots`
--

CREATE TABLE `sm_purok_4_spots` (
  `id` int(11) NOT NULL,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sm_purok_4_spots`
--

INSERT INTO `sm_purok_4_spots` (`id`, `house_number`, `husband_name`, `spouse_name`, `legend_id`, `title`, `details`, `marker_width`, `marker_height`, `top_position`, `left_position`, `marker_image`, `house_image`, `pos_x`, `pos_y`, `created_at`) VALUES
(1, '213', 'dfh', 'zvzxc', NULL, '', NULL, 40, 40, 50.0000, 50.0000, 'uploads/markers/1791423267_images.jpg', 'uploads/houses/1791423267_house_images.jpg', NULL, NULL, '2026-10-08 01:34:27');

-- --------------------------------------------------------

--
-- Table structure for table `sm_purok_5_spots`
--

CREATE TABLE `sm_purok_5_spots` (
  `id` int(11) NOT NULL,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sm_purok_5_spots`
--

INSERT INTO `sm_purok_5_spots` (`id`, `house_number`, `husband_name`, `spouse_name`, `legend_id`, `title`, `details`, `marker_width`, `marker_height`, `top_position`, `left_position`, `marker_image`, `house_image`, `pos_x`, `pos_y`, `created_at`) VALUES
(1, '214', 'dfb', ',kgfgh', NULL, '', NULL, 40, 40, 50.0000, 50.0000, 'uploads/markers/1791423287_images.jpg', 'uploads/houses/1791423287_house_images.jpg', NULL, NULL, '2026-10-08 01:34:47');

-- --------------------------------------------------------

--
-- Table structure for table `sm_purok_6_spots`
--

CREATE TABLE `sm_purok_6_spots` (
  `id` int(11) NOT NULL,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sm_purok_6_spots`
--

INSERT INTO `sm_purok_6_spots` (`id`, `house_number`, `husband_name`, `spouse_name`, `legend_id`, `title`, `details`, `marker_width`, `marker_height`, `top_position`, `left_position`, `marker_image`, `house_image`, `pos_x`, `pos_y`, `created_at`) VALUES
(1, '5', 'dfhdf', 'zvdsd', NULL, '', NULL, 40, 40, 50.0000, 50.0000, 'uploads/markers/1791423302_images.jpg', 'uploads/houses/1791423302_house_images.jpg', NULL, NULL, '2026-10-08 01:35:02');

-- --------------------------------------------------------

--
-- Table structure for table `sm_purok_7_spots`
--

CREATE TABLE `sm_purok_7_spots` (
  `id` int(11) NOT NULL,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sm_purok_7_spots`
--

INSERT INTO `sm_purok_7_spots` (`id`, `house_number`, `husband_name`, `spouse_name`, `legend_id`, `title`, `details`, `marker_width`, `marker_height`, `top_position`, `left_position`, `marker_image`, `house_image`, `pos_x`, `pos_y`, `created_at`) VALUES
(1, '4t4', 'sdgsd', 'asfas', NULL, '', NULL, 40, 40, 50.0000, 50.0000, 'uploads/markers/1791423357_images.jpg', 'uploads/houses/1791423357_house_images.jpg', NULL, NULL, '2026-10-08 01:35:57');

-- --------------------------------------------------------

--
-- Table structure for table `sm_purok_8_spots`
--

CREATE TABLE `sm_purok_8_spots` (
  `id` int(11) NOT NULL,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sm_purok_8_spots`
--

INSERT INTO `sm_purok_8_spots` (`id`, `house_number`, `husband_name`, `spouse_name`, `legend_id`, `title`, `details`, `marker_width`, `marker_height`, `top_position`, `left_position`, `marker_image`, `house_image`, `pos_x`, `pos_y`, `created_at`) VALUES
(1, '542', 'dgsd', 'dfsdf', NULL, '', NULL, 40, 40, 50.0000, 50.0000, 'uploads/markers/1791423371_images.jpg', 'uploads/houses/1791423371_house_images.jpg', NULL, NULL, '2026-10-08 01:36:11');

-- --------------------------------------------------------

--
-- Table structure for table `sm_purok_9_spots`
--

CREATE TABLE `sm_purok_9_spots` (
  `id` int(11) NOT NULL,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sm_purok_9_spots`
--

INSERT INTO `sm_purok_9_spots` (`id`, `house_number`, `husband_name`, `spouse_name`, `legend_id`, `title`, `details`, `marker_width`, `marker_height`, `top_position`, `left_position`, `marker_image`, `house_image`, `pos_x`, `pos_y`, `created_at`) VALUES
(1, '45', 'sdgs', 'dada', NULL, '', NULL, 40, 40, 42.1200, 47.0700, 'uploads/markers/1791423389_images.jpg', 'uploads/houses/1791423389_house_images.jpg', NULL, NULL, '2026-10-08 01:36:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'System Admin', 'admin@barangaymaruing.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', '2026-08-28 12:44:13', '2026-08-28 12:44:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barangay_information`
--
ALTER TABLE `barangay_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `officials`
--
ALTER TABLE `officials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sm_legends`
--
ALTER TABLE `sm_legends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sm_legend_spots`
--
ALTER TABLE `sm_legend_spots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `legend_id` (`legend_id`);

--
-- Indexes for table `sm_purok_1_spots`
--
ALTER TABLE `sm_purok_1_spots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `legend_id` (`legend_id`);

--
-- Indexes for table `sm_purok_2_spots`
--
ALTER TABLE `sm_purok_2_spots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `legend_id` (`legend_id`);

--
-- Indexes for table `sm_purok_3_spots`
--
ALTER TABLE `sm_purok_3_spots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `legend_id` (`legend_id`);

--
-- Indexes for table `sm_purok_4_spots`
--
ALTER TABLE `sm_purok_4_spots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `legend_id` (`legend_id`);

--
-- Indexes for table `sm_purok_5_spots`
--
ALTER TABLE `sm_purok_5_spots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `legend_id` (`legend_id`);

--
-- Indexes for table `sm_purok_6_spots`
--
ALTER TABLE `sm_purok_6_spots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `legend_id` (`legend_id`);

--
-- Indexes for table `sm_purok_7_spots`
--
ALTER TABLE `sm_purok_7_spots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `legend_id` (`legend_id`);

--
-- Indexes for table `sm_purok_8_spots`
--
ALTER TABLE `sm_purok_8_spots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `legend_id` (`legend_id`);

--
-- Indexes for table `sm_purok_9_spots`
--
ALTER TABLE `sm_purok_9_spots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `legend_id` (`legend_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barangay_information`
--
ALTER TABLE `barangay_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `officials`
--
ALTER TABLE `officials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sm_legends`
--
ALTER TABLE `sm_legends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sm_legend_spots`
--
ALTER TABLE `sm_legend_spots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sm_purok_1_spots`
--
ALTER TABLE `sm_purok_1_spots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sm_purok_2_spots`
--
ALTER TABLE `sm_purok_2_spots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sm_purok_3_spots`
--
ALTER TABLE `sm_purok_3_spots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sm_purok_4_spots`
--
ALTER TABLE `sm_purok_4_spots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sm_purok_5_spots`
--
ALTER TABLE `sm_purok_5_spots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sm_purok_6_spots`
--
ALTER TABLE `sm_purok_6_spots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sm_purok_7_spots`
--
ALTER TABLE `sm_purok_7_spots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sm_purok_8_spots`
--
ALTER TABLE `sm_purok_8_spots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sm_purok_9_spots`
--
ALTER TABLE `sm_purok_9_spots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- --------------------------------------------------------

--
-- Table structure for table `feedback_chats`
--

CREATE TABLE `feedback_chats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` varchar(100) NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `sender_type` enum('user','admin') NOT NULL DEFAULT 'user',
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
