-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql:3306
-- Generation Time: Jul 11, 2026 at 01:04 PM
-- Server version: 12.3.2-MariaDB-ubu2404
-- PHP Version: 8.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lestscoring`
--

-- --------------------------------------------------------

--
-- Table structure for table `courts`
--

CREATE TABLE `courts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tournament_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `draws`
--

CREATE TABLE `draws` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `type` varchar(100) NOT NULL,
  `size` int(11) NOT NULL,
  `tournament_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `tournament_id` int(11) NOT NULL,
  `scoring_type` varchar(5) NOT NULL,
  `draw_id` int(11) NOT NULL,
  `draw_position` int(11) NOT NULL,
  `teamA_Player1_id` int(11) DEFAULT NULL,
  `teamA_Player2_id` int(11) DEFAULT NULL,
  `teamB_Player1_id` int(11) DEFAULT NULL,
  `teamB_Player2_id` int(11) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `winner` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `nationality` varchar(3) DEFAULT NULL,
  `rank` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `court_id` int(11) DEFAULT NULL,
  `umpire_id` int(11) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `event` varchar(255) DEFAULT NULL,
  `start_time` int(100) DEFAULT NULL,
  `match_time` int(100) NOT NULL,
  `teamA_Player1_id` int(11) NOT NULL,
  `teamA_Player2_id` int(11) DEFAULT NULL,
  `teamB_Player1_id` int(11) NOT NULL,
  `teamB_Player2_id` int(11) DEFAULT NULL,
  `service` int(11) DEFAULT NULL,
  `teamA_points` varchar(255) DEFAULT NULL,
  `teamB_points` varchar(255) DEFAULT NULL,
  `teamA_set1` int(3) DEFAULT NULL,
  `teamA_tie1` int(3) DEFAULT NULL,
  `teamA_set2` int(3) DEFAULT NULL,
  `teamA_tie2` int(3) DEFAULT NULL,
  `teamA_set3` int(3) DEFAULT NULL,
  `teamA_tie3` int(3) DEFAULT NULL,
  `teamB_set1` int(3) DEFAULT NULL,
  `teamB_tie1` int(3) DEFAULT NULL,
  `teamB_set2` int(3) DEFAULT NULL,
  `teamB_tie2` int(3) DEFAULT NULL,
  `teamB_set3` int(3) DEFAULT NULL,
  `teamB_tie3` int(3) DEFAULT NULL,
  `set1_time` int(100) DEFAULT NULL,
  `set2_time` int(100) DEFAULT NULL,
  `set3_time` int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

CREATE TABLE `tournaments` (
  `id` int(11) NOT NULL,
  `club` varchar(255) NULL,
  `city` varchar(255) NULL,
  `name` varchar(255) NULL,
  `start_time` int(100) DEFAULT NULL,
  `end_time` int(100) DEFAULT NULL,
  `director_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `umpires`
--

CREATE TABLE `umpires` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `tournament_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('ADMIN','DIRECTOR','UMPIRE','USER') NOT NULL,
  `is_permanent` int(1) NOT NULL,
  `is_suspended` int(1) NOT NULL,
  UNIQUE KEY `uk_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Indexes for dumped tables
--

--
-- Indexes for table `courts`
--
ALTER TABLE `courts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_courts_tournament_id` (`tournament_id`);

--
-- Indexes for table `draws`
--
ALTER TABLE `draws`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_draws_tournament_id` (`tournament_id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_matches_tournament_id` (`tournament_id`),
  ADD KEY `idx_matches_draw_id` (`draw_id`),
  ADD KEY `idx_matches_winner` (`winner`),
  ADD KEY `idx_matches_teamA_p1` (`teamA_Player1_id`),
  ADD KEY `idx_matches_teamB_p1` (`teamB_Player1_id`),
  ADD KEY `idx_matches_teamA_p2` (`teamA_Player2_id`),
  ADD KEY `idx_matches_teamB_p2` (`teamB_Player2_id`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_scores_court_id` (`court_id`),
  ADD KEY `idx_scores_umpire_id` (`umpire_id`),
  ADD KEY `idx_scores_match_id` (`match_id`),
  ADD KEY `idx_scores_teamA_p1` (`teamA_Player1_id`),
  ADD KEY `idx_scores_teamA_p2` (`teamA_Player2_id`),
  ADD KEY `idx_scores_teamB_p1` (`teamB_Player1_id`),
  ADD KEY `idx_scores_teamB_p2` (`teamB_Player2_id`);

--
-- Indexes for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tournaments_director_id` (`director_id`);

--
-- Indexes for table `umpires`
--
ALTER TABLE `umpires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_umpires_tournament_id` (`tournament_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courts`
--
ALTER TABLE `courts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `draws`
--
ALTER TABLE `draws`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tournaments`
--
ALTER TABLE `tournaments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `umpires`
--
ALTER TABLE `umpires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courts`
--
ALTER TABLE `courts`
  ADD CONSTRAINT `fk_courts_tournament` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `draws`
--
ALTER TABLE `draws`
  ADD CONSTRAINT `fk_draws_tournament` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `fk_matches_draw` FOREIGN KEY (`draw_id`) REFERENCES `draws` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_matches_teamA_p1` FOREIGN KEY (`teamA_Player1_id`) REFERENCES `players` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_matches_teamA_p2` FOREIGN KEY (`teamA_Player2_id`) REFERENCES `players` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_matches_teamB_p1` FOREIGN KEY (`teamB_Player1_id`) REFERENCES `players` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_matches_teamB_p2` FOREIGN KEY (`teamB_Player2_id`) REFERENCES `players` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_matches_tournament` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_matches_winner` FOREIGN KEY (`winner`) REFERENCES `players` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `fk_scores_court` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_scores_match` FOREIGN KEY (`match_id`) REFERENCES `matches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_scores_teamA_p1` FOREIGN KEY (`teamA_Player1_id`) REFERENCES `players` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_scores_teamA_p2` FOREIGN KEY (`teamA_Player2_id`) REFERENCES `players` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_scores_teamB_p1` FOREIGN KEY (`teamB_Player1_id`) REFERENCES `players` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_scores_teamB_p2` FOREIGN KEY (`teamB_Player2_id`) REFERENCES `players` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_scores_umpire` FOREIGN KEY (`umpire_id`) REFERENCES `umpires` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD CONSTRAINT `fk_tournaments_director` FOREIGN KEY (`director_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `umpires`
--
ALTER TABLE `umpires`
  ADD CONSTRAINT `fk_umpires_tournament` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
