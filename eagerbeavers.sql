-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2020 at 08:26 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eagerbeavers`
--

-- --------------------------------------------------------

--
-- Table structure for table `cert`
--
DROP TABLE `cert`;
DROP TABLE `mem_cert`;
DROP TABLE `cord_cert`;
DROP TABLE `core_cert`;
DROP TABLE `log`;
DROP TABLE `event`;




CREATE TABLE `cert` (
  `id` int(11) NOT NULL,
  `EName` varchar(100) NOT NULL,
  `Date` date NOT NULL,
  `Rank` varchar(50) NOT NULL,
  `Chash` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cord_cert`
--

CREATE TABLE `cord_cert` (
  `id` int(11) NOT NULL,
  `EName` varchar(100) NOT NULL,
  `Date` date NOT NULL,
  `Chash` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `core_cert`
--

CREATE TABLE `core_cert` (
  `id` int(11) NOT NULL,
  `Email` varchar(500) NOT NULL,
  `Chash` varchar(500) NOT NULL,
  `Year` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `Name` varchar(1000) NOT NULL,
  `Date` date NOT NULL,
  `s_details` text NOT NULL,
  `f_details` longtext NOT NULL,
  `img_folder` varchar(500) NOT NULL,
  `coordinators` longtext,
  `img_type` varchar(100) NOT NULL,
  `total_image` int(11) NOT NULL,
  `faicon` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `Name`, `Date`, `s_details`, `f_details`, `img_folder`, `coordinators`, `img_type`, `total_image`, `faicon`) VALUES
(1, 'BLOGGING BASICS: DEVELOPING BLOG SKILL', '2020-03-05', 'This was fun-filled workshop for expressing your dreams, perspectives, ideologies in the form of words and create your own identity in the online universe', 'This was fun-filled workshop for expressing your dreams, perspectives, ideologies in the form of words and to create your own identity in the online universe. All the inquisitive minds attended a session of the expert, the idea-churning activities started, to which event coordinators and expert had a one-to-one discussion on and they started creating their very first blogs in different domains. All the blogs were presented by respective students and were applauded by the audience.', 'BGB', '{\"0\":\"Vishal Ahuja\",\"1\":\"Shalvi Desai\",\"2\":\"Vasu Gamdha\",\"3\":\"Vedanshu Joshi\"}', 'JPG', 5, 'fa fa-desktop'),
(2, 'EAGER BEAVERS X CODECHEF ILLUMINATI SEASON V', '2020-03-12', 'This was a competitive programming contest was organised on 14th of March, 2020. The contest was open for all the CHARUSAT students', 'Eager Beavers x CodeChef Illuminati Season V, competitive programming contest was organised on 14th of March, 2020. The contest was open for all the CHARUSAT students. The contest was of 3 hours and had 5 problems ranging from cakewalk to medium-hard problems. There were more than 90 participants. The programming problems were set by Jaymeet Mehta, Vasu Gamdha and Mann Mehta. Many creative minds among the participants came up with interesting solutions to the problems. The total number of submissions was 945. There was a neck-and-neck competition for top 10 on the leaderboards. After the restless 3-hour battle, NIHAL MITTAL, BHISHM PATEL and YOGESH RAISINGHANI secured their position at the top of the leaderboard, respectively.\r\n\r\nContest link: https://www.codechef.com/ILS52020/', 'code_chef', '{\"0\":\"Vishal Ahuja\",\"1\":\"Vasu Gamdha\",\"2\":\"Vedanshu Joshi\"}', 'jpg', 1, 'fa fa-code'),
(3, 'QUARANTINE GOT TALENT', '2020-05-08', 'This event was conducted to excavate the hidden talents of students via representing them through 10 soulful themes', 'This event was conducted to excavate the hidden talents of students via representing them through 10 soulful themes. Here students were given ten odd themes as an inspiration, they could perform in any form/domain such dance, music, painting, UI/UX, poetry, cookery, play writing etc. The best piece which could justify the theme correctly using their flawless skills, would grab the prize. Students whole heartedly performed using their creativity and gracefully participated in the event.', 'QGT', '{\"0\":\"Vishal Ahuja\",\"1\":\"Shalvi Desai\",\"2\":\"Vasu Gamdha\",\"3\":\"Vedanshu Joshi\"}', 'jpg', 11, 'fa fa-paper-plane');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `access_token` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `given_name` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `login_info`
--



-- --------------------------------------------------------

--
-- Table structure for table `mem_cert`
--

CREATE TABLE `mem_cert` (
  `id` int(11) NOT NULL,
  `Email` varchar(500) NOT NULL,
  `Chash` varchar(500) NOT NULL,
  `Year` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cert`
--
ALTER TABLE `cert`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cord_cert`
--
ALTER TABLE `cord_cert`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_cert`
--
ALTER TABLE `core_cert`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_info`
--
ALTER TABLE `login_info`
  ADD PRIMARY KEY (`access_token`);

--
-- Indexes for table `mem_cert`
--
ALTER TABLE `mem_cert`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cert`
--
ALTER TABLE `cert`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cord_cert`
--
ALTER TABLE `cord_cert`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `core_cert`
--
ALTER TABLE `core_cert`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mem_cert`
--
ALTER TABLE `mem_cert`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
