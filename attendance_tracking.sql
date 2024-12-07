-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2020 at 10:05 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `attendance_tracking`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(60) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `created_on` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `firstname`, `lastname`, `photo`, `created_on`) VALUES
(1, 'admin', '$2y$10$fCOiMky4n5hCJx3cpsG20Od4wHtlkCLKmO6VLobJNRIg9ooHTkgjK', 'Harry', 'Den', 'download (1).jpg', '2018-04-30');

-- --------------------------------------------------------

--
-- Table structure for table `employee_attendance`
--

CREATE TABLE `employee_attendance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date_attendance` date NOT NULL,
  `time_in` time NOT NULL,
  `status` int(1) NOT NULL,
  `time_out` time NOT NULL,
  `number_of_hour` double NOT NULL,
  `photo_path` VARCHAR(255) NULL  -- New column for photo path
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



--
-- Dumping data for table `employee_attendance`
--

INSERT INTO `employee_attendance` (`id`, `employee_id`, `date_attendance`, `time_in`, `status`, `time_out`, `number_of_hour`) VALUES
(1, 4, '2020-08-31', '18:02:41', 0, '18:02:52', 1.0333333333333),
(2, 2, '2020-08-31', '18:03:05', 0, '00:00:00', 0),
(3, 1, '2020-09-02', '13:58:30', 0, '00:00:00', 0),
(4, 3, '2020-09-09', '14:55:06', 0, '14:55:22', 2.0666666666667),
(5, 5, '2020-09-09', '14:58:30', 0, '14:58:44', 2.0166666666667),
(6, 4, '2020-09-09', '15:04:02', 0, '15:04:11', 1.9166666666667);

-- --------------------------------------------------------

--
-- Table structure for table `employee_cashadvance`
--

CREATE TABLE `employee_cashadvance` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `date_created` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee_cashadvance`
--

INSERT INTO `employee_cashadvance` (`id`, `employee_id`, `amount`, `date_created`) VALUES
(1, '3', '4000', '2020-09-09 15:00:51');

-- --------------------------------------------------------

--
-- Table structure for table `employee_deductions`
--

CREATE TABLE `employee_deductions` (
  `id` int(11) NOT NULL,
  `deduction_name` varchar(255) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `date_create` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee_deductions`
--

INSERT INTO `employee_deductions` (`id`, `deduction_name`, `amount`, `date_create`) VALUES
(1, 'SSS', 1000, '2020-08-31 18:36:57'),
(3, 'Pag-ibig', 100, '2020-08-31 18:59:06'),
(4, 'Philhealth', 230, '2020-08-31 18:59:23'),
(5, 'Item damage', 500, '2020-08-31 19:01:12'),
(6, 'Nawalan nang GF', 5000, '2020-09-09 15:01:46');

-- --------------------------------------------------------

--
-- Table structure for table `employee_overtime`
--

CREATE TABLE `employee_overtime` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(15) NOT NULL,
  `overtime_hours` double NOT NULL,
  `overtime_mins` double NOT NULL,
  `overtime_date` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee_overtime`
--

INSERT INTO `employee_overtime` (`id`, `employee_id`, `overtime_hours`, `overtime_mins`, `overtime_date`) VALUES
(1, '3', 2.5666666666667, 47, '2020-09-16');

-- --------------------------------------------------------

--
-- Table structure for table `employee_position`
--

CREATE TABLE `employee_position` (
  `id` int(11) NOT NULL,
  `emp_position` varchar(255) DEFAULT NULL,
  `rate_per_hour` double DEFAULT NULL,
  `date_added` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee_position`
--

INSERT INTO `employee_position` (`id`, `emp_position`, `rate_per_hour`, `date_added`) VALUES
(1, 'Accountancy', 120, '2020-08-31 16:43:36'),
(4, 'Graphics designer', 140, '2020-08-31 16:51:33'),
(5, 'Tech support', 150, '2020-08-31 17:45:08'),
(6, 'Hr Manager', 130, '2020-08-31 17:45:50'),
(7, 'Hr assistant', 120, '2020-08-31 17:46:13'),
(8, 'Encoder', 110, '2020-08-31 17:46:35'),
(9, 'Office staff', 130, '2020-08-31 17:46:50'),
(10, 'Accounting Supervisor', 180, '2020-08-31 18:07:17'),
(11, 'Seaman', 150, '2020-09-09 15:01:23');

-- --------------------------------------------------------

--
-- Table structure for table `employee_records`
--

CREATE TABLE `employee_records` (
  `emp_id` int(11) NOT NULL,
  `employee_id` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `complete_address` varchar(255) DEFAULT NULL,
  `birth_date` varchar(255) DEFAULT NULL,
  `Mobile_number` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `marital_status` varchar(255) DEFAULT NULL,
  `schedule_id` int(11) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `date_created` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee_records`
--

INSERT INTO `employee_records` (`emp_id`, `employee_id`, `first_name`, `middle_name`, `last_name`, `complete_address`, `birth_date`, `Mobile_number`, `gender`, `position_id`, `marital_status`, `schedule_id`, `profile_pic`, `date_created`) VALUES
(2, 'XAP120', 'Maria cecilia', 'O', 'Toledo', 'mambugan antipolo', '2020-08-19', '09756756765', 'Female', 1, 'Married', 4, '../../../images/202008311598867892_48413847_2077039949023571_4434128222120050688_n.jpg', '2020-08-31 17:57:53'),
(3, 'QNS120', 'Maria erlin', 'R', 'Nedia', 'quezon city', '2020-08-12', '09797898797', 'Female', 9, 'Single', 4, '../../../images/202008311598868012_69077692_182177326142770_7124400332225904640_n.jpg', '2020-08-31 18:00:12'),
(4, 'CET102', 'Zedrick', 'R', 'Fabros', 'antipolo rizal', '2020-08-15', '09567567567', 'Male', 5, 'Single', 4, '../../../images/202008311598868096_86392940_2994183360645842_2168096703587024896_o.jpg', '2020-08-31 18:01:21'),
(5, 'QCK021', 'Marimar', 'A', 'Gonzales', 'purok saksakan makailag swerte', '2020-09-16', '09789789789', 'Female', 1, 'Married', 4, '../../../images/202009091599634671_12294716_1027663247286011_8146311966866952948_n.jpg', '2020-09-09 14:57:52');

-- --------------------------------------------------------

--
-- Table structure for table `employee_schedule`
--

CREATE TABLE `employee_schedule` (
  `id` int(11) NOT NULL,
  `time_in` varchar(255) DEFAULT NULL,
  `time_out` varchar(255) DEFAULT NULL,
  `date_added` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee_schedule`
--

INSERT INTO `employee_schedule` (`id`, `time_in`, `time_out`, `date_added`) VALUES
(4, '08:00 AM', '05:00 PM', '2020-08-31 17:47:52'),
(5, '10:00 AM', '07:00 PM', '2020-08-31 17:49:05'),
(6, '07:00 PM', '04:00 AM', '2020-08-31 17:49:46'),
(7, '12:00 PM', '06:00 PM', '2020-09-09 15:00:31');

-- --------------------------------------------------------

--
-- Table structure for table `history_log`
--

CREATE TABLE `history_log` (
  `log_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `email_address` text NOT NULL,
  `action` varchar(100) NOT NULL,
  `actions` varchar(200) NOT NULL DEFAULT 'Has LoggedOut the system at',
  `ip` text NOT NULL,
  `host` text NOT NULL,
  `login_time` varchar(200) NOT NULL,
  `logout_time` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `history_log`
--

INSERT INTO `history_log` (`log_id`, `id`, `email_address`, `action`, `actions`, `ip`, `host`, `login_time`, `logout_time`) VALUES
(0, 5, 'user@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'buhayko-PC', 'Aug-31-2020 05:13 PM', 'Sep-09-2020 03:03 PM'),
(0, 5, 'user@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'buhayko-PC', 'Aug-31-2020 05:14 PM', 'Sep-09-2020 03:03 PM'),
(0, 5, 'user@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'buhayko-PC', 'Aug-31-2020 06:04 PM', 'Sep-09-2020 03:03 PM'),
(0, 5, 'user@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'buhayko-PC', 'Aug-31-2020 06:09 PM', 'Sep-09-2020 03:03 PM'),
(0, 5, 'user@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'buhayko-PC', 'Aug-31-2020 10:48 PM', 'Sep-09-2020 03:03 PM'),
(0, 5, 'user@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'buhayko-PC', 'Sep-02-2020 10:21 PM', 'Sep-09-2020 03:03 PM'),
(0, 5, 'user@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'buhayko-PC', 'Sep-09-2020 02:23 PM', 'Sep-09-2020 03:03 PM'),
(0, 5, 'user@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'buhayko-PC', 'Sep-09-2020 02:55 PM', 'Sep-09-2020 03:03 PM'),
(0, 5, 'user@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'buhayko-PC', 'Sep-09-2020 02:59 PM', 'Sep-09-2020 03:03 PM'),
(0, 5, 'user@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'buhayko-PC', 'Sep-09-2020 03:51 PM', '');

-- --------------------------------------------------------

--
-- Table structure for table `login_admin`
--

CREATE TABLE `login_admin` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `email_address` text NOT NULL,
  `user_password` text NOT NULL,
  `user_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login_admin`
--

INSERT INTO `login_admin` (`id`, `name`, `email_address`, `user_password`, `user_status`) VALUES
(1, 'Email Luis Nario', 'nario@gmail.com', '$2y$12$xxxzp5LySIAgobcyOnpp1uW6VzGdrZXTuqXrj3V7rA5ZP8RbpzNtW', 'Employee'),
(3, 'jonhrey', 'dj@gmail.com', '$2y$12$YcN5/QpCFsV4U6FArIG9uOT/9iIX1zpD/kOfBR9eujbYumUEJuqL.', 'Employee'),
(4, 'john doe', 'johndoe@yahoo.com', '$2y$12$uT.Q4rmJ5t3BUCFgN/DpROfoClDT6KY2d6X.R4Go4/KeXs55qs.Ey', 'Employee'),
(5, 'Julius Maru', 'user@gmail.com', '$2y$12$YQot8hdViq2Bn..NaOC8W..3Zou/ohIXhD.lEasZJDSxhoBP6EBKe', 'Employee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_cashadvance`
--
ALTER TABLE `employee_cashadvance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_deductions`
--
ALTER TABLE `employee_deductions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_overtime`
--
ALTER TABLE `employee_overtime`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_position`
--
ALTER TABLE `employee_position`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_records`
--
ALTER TABLE `employee_records`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `employee_schedule`
--
ALTER TABLE `employee_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_admin`
--
ALTER TABLE `login_admin`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employee_cashadvance`
--
ALTER TABLE `employee_cashadvance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_deductions`
--
ALTER TABLE `employee_deductions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employee_overtime`
--
ALTER TABLE `employee_overtime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_position`
--
ALTER TABLE `employee_position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `employee_records`
--
ALTER TABLE `employee_records`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employee_schedule`
--
ALTER TABLE `employee_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `login_admin`
--
ALTER TABLE `login_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
