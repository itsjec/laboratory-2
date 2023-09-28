-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 28, 2023 at 12:44 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lab2`
--

-- --------------------------------------------------------

--
-- Table structure for table `music_tracks`
--

CREATE TABLE `music_tracks` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `artist` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `music_tracks`
--

INSERT INTO `music_tracks` (`id`, `title`, `artist`, `file_path`) VALUES
(1, 'TWICE _YES or YES_ MV.mp3', 'Unknown', 'uploads/TWICE _YES or YES_ MV.mp3'),
(2, 'Nxde - (G) IDLE.mp3', 'Unknown', 'uploads/Nxde - (G) IDLE.mp3'),
(3, '(여자)아이들((G)I-DLE) - \'퀸카 (Queencard)\' Official Music Video.mp3', 'Unknown', 'uploads/(여자)아이들((G)I-DLE) - \'퀸카 (Queencard)\' Official Music Video.mp3'),
(4, 'Lihim - Arthur Miguel Lyric Video.mp3', 'Unknown', 'uploads/Lihim - Arthur Miguel Lyric Video.mp3'),
(5, 'Rhodessa - Kisame (Lyrics).mp3', 'Unknown', 'uploads/Rhodessa - Kisame (Lyrics).mp3');

-- --------------------------------------------------------

--
-- Table structure for table `playlists`
--

CREATE TABLE `playlists` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `playlists`
--

INSERT INTO `playlists` (`id`, `name`) VALUES
(1, 'TWICE'),
(2, 'FAVORITES');

-- --------------------------------------------------------

--
-- Table structure for table `playlist_tracks`
--

CREATE TABLE `playlist_tracks` (
  `id` int NOT NULL,
  `playlist_id` int NOT NULL,
  `track_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `music_tracks`
--
ALTER TABLE `music_tracks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `playlists`
--
ALTER TABLE `playlists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `playlist_tracks`
--
ALTER TABLE `playlist_tracks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `playlist_id` (`playlist_id`),
  ADD KEY `track_id` (`track_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `music_tracks`
--
ALTER TABLE `music_tracks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `playlists`
--
ALTER TABLE `playlists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `playlist_tracks`
--
ALTER TABLE `playlist_tracks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `playlist_tracks`
--
ALTER TABLE `playlist_tracks`
  ADD CONSTRAINT `playlist_tracks_ibfk_1` FOREIGN KEY (`playlist_id`) REFERENCES `playlists` (`id`),
  ADD CONSTRAINT `playlist_tracks_ibfk_2` FOREIGN KEY (`track_id`) REFERENCES `music_tracks` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
