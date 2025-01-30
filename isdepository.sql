-- phpMyAdmin SQL Dump
-- version 4.9.11
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 28, 2025 at 01:48 AM
-- Server version: 5.6.32-78.1
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `matthewx_ISDepository`
--

-- --------------------------------------------------------

--
-- Table structure for table `conditions`
--

CREATE TABLE `conditions` (
  `condition_id` int(11) NOT NULL,
  `condition_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `conditions`
--

INSERT INTO `conditions` (`condition_id`, `condition_name`) VALUES
(1, 'Brand New'),
(5, 'Heavily Used'),
(3, 'Lightly Used'),
(2, 'Like New'),
(4, 'Well Used');

-- --------------------------------------------------------

--
-- Table structure for table `listings`
--

CREATE TABLE `listings` (
  `listing_id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `seller_id` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` int(11) NOT NULL,
  `condition_id` int(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `listings`
--

INSERT INTO `listings` (`listing_id`, `seller_id`, `title`, `description`, `price`, `condition_id`, `created_at`, `updated_at`) VALUES
('1ae91acc-d304-11ef-9f4b-525400877de3', 'c19d5548-d303-11ef-9f4b-525400877de3', 'My Big Balls', 'Negotiable', 999, 5, '2025-01-15 05:46:45', '2025-01-15 05:46:45'),
('2e1c2466-d0f7-11ef-84bd-525400877de3', 'a06fae5f-c74e-11ef-967c-704d7bc0', 'Maths IB AASL Textbook ', 'I\'m AAHL. ', 200, 2, '2025-01-12 15:09:12', '2025-01-12 15:09:12'),
('2e1c2a28-d0f7-11ef-84bd-525400877de3', '3d109ccc-c2a3-11ef-9633-0a002700', 'Entire IS Library ', 'The entire Island School Library for sale', 1, 5, '2025-01-12 15:09:12', '2025-01-12 15:09:12'),
('3481aefc-dbe6-11ef-9e01-525400877de3', 'a06fae5f-c74e-11ef-967c-704d7bc0', 'The Things They Carried', 'English text must have!!', 150, 4, '2025-01-26 13:05:24', '2025-01-26 13:05:24'),
('58184c05-d305-11ef-9f4b-525400877de3', '113b6688-d305-11ef-9f4b-525400877de3', 'Gemma Kim (Myself)', '- Korean \r\n- Asexual\r\n- 100 Aura \r\n- Skibidi Toilet \r\n- Alpha \r\n- Gen Z', 5, 1, '2025-01-15 05:55:37', '2025-01-15 05:55:37'),
('633113be-d260-11ef-8aac-525400877de3', 'a06fae5f-c74e-11ef-967c-704d7bc0', 'Computer science homework', 'I\'ll do your cs homework for you... hehehe', 50, 1, '2025-01-14 10:14:49', '2025-01-14 10:14:49'),
('74c17b2c-dbe6-11ef-9e01-525400877de3', 'a06fae5f-c74e-11ef-967c-704d7bc0', 'The Things They Carried', 'English text must have!!', 150, 4, '2025-01-26 13:07:11', '2025-01-26 13:07:11'),
('98690bdc-d2f7-11ef-8aac-525400877de3', 'd5f557dd-d2f6-11ef-8aac-525400877de3', 'ESS Textbook', 'DM for trading details', 200, 3, '2025-01-15 04:17:12', '2025-01-15 04:17:12'),
('b1ca4012-d25f-11ef-a816-525400877de3', 'a06fae5f-c74e-11ef-967c-704d7bc0', 'Computer Science Tutoring', 'Tutoring for computer science, IGCSE or IB syllabi. I specialise in Python.', 400, 1, '2025-01-14 10:09:51', '2025-01-14 10:09:51'),
('c2ee8252-d286-11ef-8aac-525400877de3', 'a06fae5f-c74e-11ef-967c-704d7bc0', 'IGCSE Chemistry Book', 'With some notes inside', 350, 5, '2025-01-14 14:49:30', '2025-01-14 14:49:30'),
('c5f91e21-dbef-11ef-9e01-525400877de3', 'd5f557dd-d2f6-11ef-8aac-525400877de3', 'ib maths aahl textbook', 'switched to aasl. inquiries welcome', 200, 1, '2025-01-26 14:13:53', '2025-01-26 14:13:53'),
('ff7fa716-dbd4-11ef-9e01-525400877de3', 'a06fae5f-c74e-11ef-967c-704d7bc0', 'The World\'s Wife', 'English LL HL poetry text.', 80, 2, '2025-01-26 11:02:13', '2025-01-26 11:02:13');

-- --------------------------------------------------------

--
-- Table structure for table `listing_images`
--

CREATE TABLE `listing_images` (
  `img_id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `listing_id` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path` longtext COLLATE utf8mb4_unicode_ci,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `listing_images`
--

INSERT INTO `listing_images` (`img_id`, `listing_id`, `image_path`, `uploaded_at`) VALUES
('914fd44f-d289-11ef-8aac-525400877de3', '2e1c2a28-d0f7-11ef-84bd-525400877de3', 'https://imgs.search.brave.com/7ZHzjkpAbgm-ALUFyTUUy45nInAC71bHnHLCjhwT9Js/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9rY2xz/LmJpYmxpb2NvbW1v/bnMuY29tL2V2ZW50/cy91cGxvYWRzL2lt/YWdlcy9mdWxsLzE4/YTlhNjIwYzcyNTE2/MGIyZjc4YWRlNDQ1/OTkxZmY0LzA5XzIx/LUxpYnJhcmllcy0x/MjAwXzY0MF8wMDIw/X01lcmNlci1Jc2xh/bmQucG5n', '2025-01-14 15:09:36'),
('948cf48b-d0f8-11ef-84bd-525400877de3', '2e1c2466-d0f7-11ef-84bd-525400877de3', 'https://media.karousell.com/media/photos/products/2024/8/31/ib_maths_aa_sl_textbook_1725097242_eaf10587.jpg', '2025-01-12 15:19:13');

-- --------------------------------------------------------

--
-- Table structure for table `listing_tags`
--

CREATE TABLE `listing_tags` (
  `listing_id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `listing_tags`
--

INSERT INTO `listing_tags` (`listing_id`, `tag_id`) VALUES
('2e1c2466-d0f7-11ef-84bd-525400877de3', 1),
('c2ee8252-d286-11ef-8aac-525400877de3', 1),
('2e1c2a28-d0f7-11ef-84bd-525400877de3', 2),
('74c17b2c-dbe6-11ef-9e01-525400877de3', 2),
('98690bdc-d2f7-11ef-8aac-525400877de3', 2),
('c5f91e21-dbef-11ef-9e01-525400877de3', 2),
('ff7fa716-dbd4-11ef-9e01-525400877de3', 2),
('c5f91e21-dbef-11ef-9e01-525400877de3', 3),
('1ae91acc-d304-11ef-9f4b-525400877de3', 5),
('c5f91e21-dbef-11ef-9e01-525400877de3', 8),
('58184c05-d305-11ef-9f4b-525400877de3', 23);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `listing_id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `offer_id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `listing_id` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `buyer_id` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `offer_price` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','accepted','rejected') COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saved_listings`
--

CREATE TABLE `saved_listings` (
  `user_id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `listing_id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `saved_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `saved_listings`
--

INSERT INTO `saved_listings` (`user_id`, `listing_id`, `saved_at`) VALUES
('61ea19f9-d882-11ef-8a13-525400877de3', '2e1c2466-d0f7-11ef-84bd-525400877de3', '2025-01-22 05:45:52'),
('a06fae5f-c74e-11ef-967c-704d7bc0', '2e1c2466-d0f7-11ef-84bd-525400877de3', '2025-01-20 15:35:56'),
('a06fae5f-c74e-11ef-967c-704d7bc0', '2e1c2a28-d0f7-11ef-84bd-525400877de3', '2025-01-20 15:45:08'),
('a06fae5f-c74e-11ef-967c-704d7bc0', '58184c05-d305-11ef-9f4b-525400877de3', '2025-01-22 05:20:38');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL,
  `tag_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tag_id`, `tag_name`) VALUES
(27, 'Ab Initio'),
(28, 'Additional Resource'),
(13, 'Art'),
(11, 'Biology'),
(9, 'Chemistry'),
(7, 'Chinese'),
(12, 'Computer Science'),
(19, 'Design Technology'),
(5, 'Electronics'),
(6, 'English'),
(20, 'ESS'),
(14, 'Extended Essay'),
(23, 'French'),
(26, 'HL'),
(2, 'IB'),
(1, 'IGCSE'),
(24, 'Japanese'),
(8, 'Mathematics'),
(50, 'Others'),
(17, 'Philosophy'),
(10, 'Physics'),
(18, 'PRS'),
(16, 'Psychology'),
(25, 'SL'),
(22, 'Spanish'),
(21, 'Sports Science'),
(4, 'Stationary'),
(3, 'Textbook'),
(15, 'TOK');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1',
  `profile_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `hashed_password`, `created_at`, `updated_at`, `is_active`, `profile_picture`) VALUES
('113b6688-d305-11ef-9f4b-525400877de3', 'Gemma Kim', 'gemma.kim@online.island.edu.hk', '$2y$10$23JrFJcunnLx3Phri9NzVOQDO4QFCquK3DZxMVz3jkj9n/TF6DUGe', '2025-01-15 05:53:38', '2025-01-27 05:01:29', 1, NULL),
('2fe03eed-dc14-11ef-9e01-525400877de3', 'Monster Energy', 'monster.energy@online.island.edu.hk', '$2y$10$PQUcgd.XSpXrX.v.lOodauoIIwvnZROZoN9pCECX0wENi43Rq60n2', '2025-01-26 18:34:33', '2025-01-26 18:34:33', 1, NULL),
('3d109ccc-c2a3-11ef-9633-0a002700', 'library', 'library@online.island.edu.hk', '$2y$10$QckALjZP1n/7JQAO02WKFef9mcI9Ao2bM/cWo4blU8pjlPGvcSl/q', '2024-12-25 09:32:41', '2024-12-25 09:32:41', 1, NULL),
('4be3ebbf-d302-11ef-9f4b-525400877de3', 'Matthew Xiao', 'matthew.xiao@online.island.edu.hk', '$2y$10$AItHjw2uRkZbnXemmS63.OhaUvEthMuPpfEpGnJSKD6ELlVBjtqI.', '2025-01-15 05:33:48', '2025-01-27 05:01:38', 1, NULL),
('61ea19f9-d882-11ef-8a13-525400877de3', 'Kevin Lester', 'kevin.lester@online.island.edu.hk', '$2y$10$wyiWwTLnJUGsh1yR9Xx5/etoB7ntkdK9UBV2hvIi2ri09FFEQqFsS', '2025-01-22 05:33:17', '2025-01-27 05:01:42', 1, NULL),
('a06fae5f-c74e-11ef-967c-704d7bc0', 'Luciano Suen', 'luciano.suen@online.island.edu.hk', '$2y$10$2MQz3xEochkNjteLDHopoufNpVUKpHLFIsTaJDiQr1To7nIAZAbIi', '2024-12-31 08:09:58', '2025-01-27 05:01:48', 1, NULL),
('b6ef8af0-d305-11ef-9f4b-525400877de3', 'Colin Chan', 'colin.chan@online.island.edu.hk', '$2y$10$yZYRtvs72ep.R0jwlOmTyuO6h33TuLJglXERu0QrXOVFAUddgn0Pe', '2025-01-15 05:58:16', '2025-01-27 05:01:52', 1, NULL),
('c19d5548-d303-11ef-9f4b-525400877de3', 'Anthony Wong', 'anthony.wong@online.island.edu.hk', '$2y$10$LnV9zFZR5jAEomjJTChN3.gS3xWGPRYaKLsa4I2OL6D5bMGQWVgKC', '2025-01-15 05:44:15', '2025-01-27 05:01:56', 1, NULL),
('d5f557dd-d2f6-11ef-8aac-525400877de3', 'Michael Yip', 'michael.yip@online.island.edu.hk', '$2y$10$IznV0B08fvZnFDrztskO6.LB/mjiNMVHURghJaJDlj6GvnTpF/Dwu', '2025-01-15 04:11:46', '2025-01-27 05:02:00', 1, NULL),
('d9529c97-d881-11ef-8a13-525400877de3', 'Andy Chan', 'andy.chan@online.island.edu.hk', '$2y$10$/xfC2ciJwo/EJu5MXM63GOnmsH8QMQ.l23iWrYhlqTZWEQNQhOnly', '2025-01-22 05:29:28', '2025-01-27 05:02:03', 1, NULL),
('eee96d21-d880-11ef-8a13-525400877de3', 'Natalie Healy', 'natalie.healy@online.island.edu.hk', '$2y$10$nGDBYUSjLr1VQpxNKZMSJeXqP8rtwNjl.mGG8I.RK5VxLIQrhsxCi', '2025-01-22 05:22:54', '2025-01-27 05:02:06', 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `conditions`
--
ALTER TABLE `conditions`
  ADD PRIMARY KEY (`condition_id`),
  ADD UNIQUE KEY `condition_name` (`condition_name`);

--
-- Indexes for table `listings`
--
ALTER TABLE `listings`
  ADD PRIMARY KEY (`listing_id`),
  ADD KEY `condition_id` (`condition_id`),
  ADD KEY `user_id` (`seller_id`),
  ADD KEY `price` (`price`,`condition_id`,`created_at`);
ALTER TABLE `listings` ADD FULLTEXT KEY `title` (`title`,`description`);

--
-- Indexes for table `listing_images`
--
ALTER TABLE `listing_images`
  ADD PRIMARY KEY (`img_id`),
  ADD KEY `listing_id` (`listing_id`);

--
-- Indexes for table `listing_tags`
--
ALTER TABLE `listing_tags`
  ADD PRIMARY KEY (`listing_id`,`tag_id`) USING BTREE,
  ADD KEY `tag_id` (`tag_id`),
  ADD KEY `listing_id` (`listing_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD UNIQUE KEY `sender_id` (`sender_id`,`recipient_id`,`listing_id`),
  ADD KEY `messages_ibfk_2` (`recipient_id`),
  ADD KEY `listing_id` (`listing_id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`offer_id`),
  ADD KEY `listing_id` (`listing_id`),
  ADD KEY `user_id` (`buyer_id`);

--
-- Indexes for table `saved_listings`
--
ALTER TABLE `saved_listings`
  ADD PRIMARY KEY (`user_id`,`listing_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `listing_id` (`listing_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`),
  ADD UNIQUE KEY `tag_name` (`tag_name`);
ALTER TABLE `tags` ADD FULLTEXT KEY `tag_name_2` (`tag_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `conditions`
--
ALTER TABLE `conditions`
  MODIFY `condition_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `listing_images`
--
ALTER TABLE `listing_images`
  ADD CONSTRAINT `listing_images_ibfk_1` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`listing_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `listing_tags`
--
ALTER TABLE `listing_tags`
  ADD CONSTRAINT `fk_listing_tags_listing_id` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`listing_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `listing_tags_ibfk_1` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`listing_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `listing_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `listing_tags_ibfk_3` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`listing_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `listings` (`seller_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`listing_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `saved_listings`
--
ALTER TABLE `saved_listings`
  ADD CONSTRAINT `fk_saved_listings_listing_id` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`listing_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `saved_listings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `saved_listings_ibfk_2` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`listing_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
