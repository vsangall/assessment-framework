-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 25, 2022 at 06:05 AM
-- Server version: 10.3.28-MariaDB-log
-- PHP Version: 7.2.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spider`
--

-- --------------------------------------------------------

--
-- Table structure for table `client_scoring`
--

CREATE TABLE `client_scoring` (
  `hash` varchar(100) DEFAULT NULL,
  `architecture_total` float NOT NULL DEFAULT 0,
  `architecture_area_1` float NOT NULL DEFAULT 0,
  `architecture_area_2` float NOT NULL DEFAULT 0,
  `architecture_area_4` float NOT NULL DEFAULT 0,
  `automation_area_1` float NOT NULL DEFAULT 0,
  `automation_area_2` float NOT NULL DEFAULT 0,
  `automation_area_3` float NOT NULL DEFAULT 0,
  `automation_area_4` float NOT NULL DEFAULT 0,
  `environment_area_1` float NOT NULL DEFAULT 0,
  `environment_area_2` float NOT NULL DEFAULT 0,
  `environment_area_3` float NOT NULL DEFAULT 0,
  `environment_area_4` float NOT NULL DEFAULT 0,
  `wow_area_1` float NOT NULL DEFAULT 0,
  `wow_area_2` float NOT NULL DEFAULT 0,
  `wow_area_3` float NOT NULL DEFAULT 0,
  `wow_area_4` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

CREATE TABLE `data` (
  `id` int(3) NOT NULL,
  `user` varchar(100) NOT NULL,
  `client` varchar(50) NOT NULL,
  `project` varchar(100) NOT NULL,
  `rhEmail` varchar(50) NOT NULL,
  `country` varchar(100) NOT NULL,
  `region` varchar(20) DEFAULT NULL,
  `lob` varchar(100) NOT NULL,
  `hash` varchar(50) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `share` varchar(3) NOT NULL DEFAULT 'on',
  `demo` varchar(3) NOT NULL DEFAULT 'No',
  `comments` text DEFAULT NULL,
  `comments_automation` text DEFAULT NULL,
  `comments_wayOfWorking` text DEFAULT NULL,
  `comments_architecture` text DEFAULT NULL,
  `comments_environment` text DEFAULT NULL,
  `comments_visionLeadership` text DEFAULT NULL,
  `need_architecture_1` varchar(3) NOT NULL,
  `need_architecture_2` varchar(3) NOT NULL,
  `need_automation_1` varchar(3) NOT NULL,
  `need_automation_2` varchar(3) NOT NULL,
  `need_environment_1` varchar(3) NOT NULL,
  `need_environment_2` varchar(3) NOT NULL,
  `need_wow_1` varchar(3) NOT NULL,
  `need_wow_2` varchar(3) NOT NULL,
  `need_architecture_3` varchar(3) NOT NULL,
  `need_architecture_4` varchar(3) NOT NULL,
  `need_automation_3` varchar(3) NOT NULL,
  `need_automation_4` varchar(3) NOT NULL,
  `need_wow_3` varchar(3) NOT NULL,
  `need_environment_3` varchar(3) NOT NULL,
  `need_environment_4` varchar(3) NOT NULL,
  `need_wow_4` varchar(3) NOT NULL,
  `goal_architecture_1` varchar(3) NOT NULL,
  `goal_architecture_2` varchar(3) NOT NULL,
  `goal_architecture_3` varchar(3) NOT NULL,
  `goal_architecture_4` varchar(3) NOT NULL,
  `goal_automation_1` varchar(3) NOT NULL,
  `goal_automation_2` varchar(3) NOT NULL,
  `goal_automation_3` varchar(3) NOT NULL,
  `goal_automation_4` varchar(3) NOT NULL,
  `goal_wow_1` varchar(3) NOT NULL,
  `goal_wow_2` varchar(3) NOT NULL,
  `goal_wow_3` varchar(3) NOT NULL,
  `goal_environment_1` varchar(3) NOT NULL,
  `goal_environment_2` varchar(3) NOT NULL,
  `goal_environment_3` varchar(3) NOT NULL,
  `goal_environment_4` varchar(3) NOT NULL,
  `goal_wow_4` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lov_client_scoring`
--

CREATE TABLE `lov_client_scoring` (
  `id` int(3) NOT NULL,
  `pov` varchar(100) DEFAULT NULL,
  `score` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lov_client_scoring`
--

INSERT INTO `lov_client_scoring` (`id`, `pov`, `score`) VALUES
(1, 'goal meet need', 2),
(2, 'need unaddressed', 1.5),
(3, 'future ambition not current need', 1),
(4, 'not an area of interest', 0);

-- --------------------------------------------------------

--
-- Table structure for table `maturity_scoring`
--

CREATE TABLE `maturity_scoring` (
  `hash` varchar(100) DEFAULT NULL,
  `architecture_total` float DEFAULT NULL,
  `maturity_architecture_1` float DEFAULT NULL,
  `maturity_architecture_2` float DEFAULT NULL,
  `maturity_architecture_3` float DEFAULT NULL,
  `maturity_architecture_4` float DEFAULT NULL,
  `maturity_automation_1` float DEFAULT NULL,
  `automation_total` float DEFAULT NULL,
  `environment_total` float DEFAULT NULL,
  `wow_total` float DEFAULT NULL,
  `maturity_automation_2` float DEFAULT NULL,
  `maturity_automation_3` float DEFAULT NULL,
  `maturity_automation_4` float DEFAULT NULL,
  `maturity_environment_1` float DEFAULT NULL,
  `maturity_environment_2` float DEFAULT NULL,
  `maturity_environment_3` float DEFAULT NULL,
  `maturity_environment_4` float DEFAULT NULL,
  `maturity_wow_1` float DEFAULT NULL,
  `maturity_wow_2` float DEFAULT NULL,
  `maturity_wow_3` float DEFAULT NULL,
  `maturity_wow_4` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(3) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` text NOT NULL,
  `uuid` varchar(50) NOT NULL,
  `lastUpdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hash` (`hash`);

--
-- Indexes for table `lov_client_scoring`
--
ALTER TABLE `lov_client_scoring`
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
-- AUTO_INCREMENT for table `data`
--
ALTER TABLE `data`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lov_client_scoring`
--
ALTER TABLE `lov_client_scoring`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
