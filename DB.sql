-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 18, 2021 at 03:21 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `KugaraBookings`
--

-- --------------------------------------------------------

--
-- Table structure for table `Bookings`
--

CREATE TABLE `Bookings` (
  `BookingID` int(11) NOT NULL,
  `fName` varchar(255) NOT NULL,
  `sName` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `sDate` date NOT NULL,
  `eDate` date NOT NULL,
  `RoomID` int(11) NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Rooms`
--

CREATE TABLE `Rooms` (
  `RoomID` int(11) NOT NULL,
  `nOccupants` int(255) NOT NULL,
  `Facilities` varchar(255) NOT NULL,
  `usdNight` int(10) NOT NULL,
  `Photos` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Rooms`
--

INSERT INTO `Rooms` (`RoomID`, `nOccupants`, `Facilities`, `usdNight`, `Photos`) VALUES
(1, 2, 'Tea and Coffee, Ensuite Shower and Toilet, WiFi, Double Bed', 40, ''),
(2, 2, 'Tea and Coffee, Ensuite Shower and Toilet, WiFi, Double Bed', 40, ''),
(3, 2, 'Tea and Coffee, Ensuite Shower and Toilet, WiFi, Double Bed', 40, ''),
(4, 2, 'Tea and Coffee, Ensuite Shower and Toilet, WiFi, Double Bed', 40, ''),
(5, 2, ' Shared Shower and Toilet, WiFi, Double Bed', 30, ''),
(6, 2, ' Shared Shower and Toilet, WiFi, 2 Single Beds', 30, ''),
(7, 2, ' Shared Shower and Toilet, WiFi, Double Bed', 30, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Bookings`
--
ALTER TABLE `Bookings`
  ADD PRIMARY KEY (`BookingID`);

--
-- Indexes for table `Rooms`
--
ALTER TABLE `Rooms`
  ADD UNIQUE KEY `RoomID` (`RoomID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Bookings`
--
ALTER TABLE `Bookings`
  MODIFY `BookingID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;