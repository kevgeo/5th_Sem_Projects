-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2016 at 05:52 PM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `net_banking`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `Account_number` bigint(20) NOT NULL,
  `Account_type` varchar(1) NOT NULL,
  `Branch_id` int(11) NOT NULL,
  `Balance` bigint(20) NOT NULL,
  `Modified_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`Account_number`, `Account_type`, `Branch_id`, `Balance`, `Modified_date`) VALUES
(900000000000, 'S', 2, 8800, '2016-10-28'),
(900000000001, 'S', 3, 63200, '2016-10-28'),
(900000000002, 'S', 9, 880000, '2016-10-28'),
(900000000003, 'C', 2, 25200, '2016-10-28'),
(900000000004, 'S', 13, 10095, '2016-10-28'),
(900000000005, 'S', 1, 170000, '2016-05-10'),
(900000000006, 'C', 2, 900000, '2016-04-13'),
(900000000007, 'C', 3, 550000, '2015-06-12');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Name` varchar(20) DEFAULT NULL,
  `Login_password` varchar(20) DEFAULT NULL,
  `Transaction_password` varchar(20) DEFAULT NULL,
  `Account_number` bigint(20) NOT NULL,
  `Customer_id` int(11) NOT NULL,
  `Mobile number` bigint(20) NOT NULL,
  `Email_id` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`Name`, `Login_password`, `Transaction_password`, `Account_number`, `Customer_id`, `Mobile number`, `Email_id`) VALUES
('Divya', 'aaa', 'hello', 900000000000, 400000000, 9874531246, 'n.divi2009@gmail.com'),
('Dhanya', 'aaa', 'great', 900000000001, 400000001, 9900044859, 'dhan@gmail.com'),
('Swathi', 'hello', 'askkl', 900000000002, 400000002, 9968054321, 'swathi@gmail.com'),
('Rupa', 'aaa', 'aaa', 900000000003, 400000003, 9923412367, 'rup@gmail.com'),
('dsjk', 'aaa', 'aaa', 900000000004, 400000004, 7811234910, 's@gmail.com'),
('jk', 'aaA2', 'aaa', 900000000005, 400000005, 7742912322, 'jk@gmail.com'),
('fdg', 'A12a', 'aaa', 900000000006, 400000006, 8339133015, 'jk@gmail.com'),
('yw', 'AAAaa123', 'AAAaa123', 900000000007, 400000007, 9420142448, 'a@gmail.com'),
(NULL, NULL, NULL, 912318939040, 438953930, 7790413394, NULL),
(NULL, NULL, NULL, 912318939041, 438953931, 8992012321, NULL),
(NULL, NULL, NULL, 912318939042, 438953932, 8772098473, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `debit_card`
--

CREATE TABLE `debit_card` (
  `card_no` bigint(16) NOT NULL,
  `cvv` int(11) NOT NULL,
  `expiry_date` varchar(20) NOT NULL,
  `Account_number` bigint(20) NOT NULL,
  `block` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `debit_card`
--

INSERT INTO `debit_card` (`card_no`, `cvv`, `expiry_date`, `Account_number`, `block`) VALUES
(6018301062129731, 119, '30-12-2027', 900000000001, 'block'),
(6018301062129732, 717, '22-11-2019', 900000000003, ''),
(6018301062129733, 383, '21-12-2027', 900000000001, '');

-- --------------------------------------------------------

--
-- Table structure for table `deposit`
--

CREATE TABLE `deposit` (
  `Account_number` bigint(20) NOT NULL,
  `Type` varchar(2) NOT NULL,
  `Amount` double NOT NULL,
  `date_of_issue` date NOT NULL,
  `date_of_expiry` date NOT NULL,
  `id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deposit`
--

INSERT INTO `deposit` (`Account_number`, `Type`, `Amount`, `date_of_issue`, `date_of_expiry`, `id`) VALUES
(900000000001, 'RD', 100, '2016-10-21', '2017-04-21', 15),
(900000000003, 'FD', 2000, '2016-10-24', '2017-04-24', 16),
(900000000003, 'RD', 3000, '2016-10-24', '2017-04-24', 17),
(900000000003, 'RD', 2000, '2016-10-24', '2017-07-24', 18),
(900000000003, 'RD', 2000, '2016-10-24', '2017-07-24', 19),
(900000000003, 'RD', 4000, '2016-10-24', '2017-10-24', 20),
(900000000001, 'FD', 1000, '2016-10-25', '2017-04-25', 24),
(900000000001, 'FD', 1000, '2016-10-25', '2017-04-25', 25),
(900000000001, 'FD', 1000, '2016-10-25', '2017-04-25', 26),
(900000000001, 'FD', 1000, '2016-10-25', '2017-04-25', 27),
(900000000001, 'FD', 1000, '2016-10-25', '2017-04-25', 28),
(900000000001, 'FD', 1000, '2016-10-25', '2017-04-25', 29),
(900000000001, 'FD', 1000, '2016-10-28', '2017-10-28', 30);

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE `loan` (
  `Lid` int(11) NOT NULL,
  `Account_number` bigint(20) NOT NULL,
  `Type` varchar(10) NOT NULL,
  `Start_date` date NOT NULL,
  `Payment_date` date NOT NULL,
  `Tenure` int(11) NOT NULL,
  `Amount` double NOT NULL,
  `EMI` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loan`
--

INSERT INTO `loan` (`Lid`, `Account_number`, `Type`, `Start_date`, `Payment_date`, `Tenure`, `Amount`, `EMI`) VALUES
(1, 900000000000, 'Home', '2007-08-30', '2012-10-30', 5, 700000, 17063),
(2, 900000000000, 'Car', '2016-02-15', '2016-10-15', 2, 400000, 20667),
(3, 900000000001, 'Home', '2016-10-18', '2016-10-18', 4, 800000, 10000),
(4, 900000000003, 'Home', '2016-10-24', '2016-10-24', 5, 600000, 14625),
(5, 900000000004, 'Home', '2013-10-25', '2016-10-25', 6, 500000, 10799),
(6, 900000000000, 'Car', '2016-10-28', '2016-10-28', 2, 200000, 10333);

-- --------------------------------------------------------

--
-- Table structure for table `login_details`
--

CREATE TABLE `login_details` (
  `login_date` varchar(20) NOT NULL,
  `Account_number` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login_details`
--

INSERT INTO `login_details` (`login_date`, `Account_number`) VALUES
('2016-10-28', 900000000001),
('2016-10-28', 900000000003),
('2016-10-28', 900000000004),
('2016-10-28', 900000000000),
('2016-10-28', 900000000007);

-- --------------------------------------------------------

--
-- Table structure for table `payee`
--

CREATE TABLE `payee` (
  `Account_number` bigint(20) NOT NULL,
  `Payee_number` bigint(20) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Nickname` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payee`
--

INSERT INTO `payee` (`Account_number`, `Payee_number`, `Name`, `Nickname`) VALUES
(900000000000, 900000000001, 'dj', 'l'),
(900000000000, 900000000002, 'rani', 'jh'),
(900000000000, 900000000003, 'sdjk', 'kds'),
(900000000000, 900000000004, 'lala', 'aa'),
(900000000001, 900000000002, 'Sally', 'sa'),
(900000000003, 900000000001, 'Dhanya', 'Dhan'),
(900000000003, 900000000002, 'Swathi', 'Swats'),
(900000000004, 900000000000, 'Harish', 'Hari'),
(900000000004, 900000000001, 'rani', 'j'),
(900000000004, 900000000002, 'Tina', 'Ti');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `Tid` int(11) NOT NULL,
  `Customer_id` int(11) NOT NULL,
  `Amount` double NOT NULL,
  `Date` date NOT NULL,
  `Remark` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`Tid`, `Customer_id`, `Amount`, `Date`, `Remark`) VALUES
(1, 400000000, 200, '2016-10-17', 'INR 200 transferred to dj'),
(2, 400000000, 2000, '2016-10-17', 'INR 2000 transferred to rani'),
(3, 400000000, 100, '2016-10-17', 'INR 100 transferred to rani'),
(4, 400000003, 4000, '2016-10-17', 'INR 4000 transferred to Dhanya'),
(5, 400000003, 200, '2016-10-17', 'INR 200 transferred to Swathi'),
(6, 400000003, 800, '2016-10-17', 'INR 800 transferred to Dhanya'),
(7, 400000004, 5000, '2016-10-17', 'INR 5000 transferred to Harish'),
(8, 400000000, 30125, '2016-10-21', 'INR 30125 debited from account for loan payment'),
(23, 400000000, 20667, '2016-03-15', 'INR 20667 debited from account for loan payment'),
(24, 400000000, 20667, '2016-04-15', 'INR 20667 debited from account for loan payment'),
(25, 400000000, 20667, '2016-05-15', 'INR 20667 debited from account for loan payment'),
(26, 400000000, 20667, '2016-06-15', 'INR 20667 debited from account for loan payment'),
(27, 400000000, 20667, '2016-07-15', 'INR 20667 debited from account for loan payment'),
(28, 400000000, 20667, '2016-08-15', 'INR 20667 debited from account for loan payment'),
(29, 400000000, 20667, '2016-09-15', 'INR 20667 debited from account for loan payment'),
(30, 400000000, 20667, '2016-10-15', 'INR 20667 debited from account for loan payment'),
(31, 400000003, 3000, '2016-10-24', 'recurring deposit of INR 3000'),
(32, 400000003, 2000, '2016-10-24', 'recurring deposit of INR 2000'),
(33, 400000003, 2000, '2016-10-24', 'recurring deposit of INR 2000'),
(34, 400000003, 2000, '2016-10-24', 'recurring deposit of INR 2000'),
(35, 400000001, 1000, '2016-10-24', 'recurring deposit of INR 1000'),
(36, 400000003, 1000, '2016-10-25', 'Mobile bill payment of INR 1000'),
(37, 400000004, 10799, '2016-09-25', 'INR 10799 debited from account for loan payment'),
(38, 400000004, 10799, '2016-10-25', 'INR 10799 debited from account for loan payment'),
(39, 400000004, 613407, '2016-10-25', 'INR 613407 transferred to Harish'),
(40, 400000000, 670000, '2016-10-28', 'INR 670000 transferred to rani'),
(41, 400000001, 100, '2016-10-28', 'Mobile bill payment of INR 100'),
(42, 400000000, 100, '2016-10-28', 'INR 100 transferred to dj'),
(43, 400000000, 100, '2016-10-28', 'INR 100 transferred to dj'),
(44, 400000000, 100, '2016-10-28', 'INR 100 transferred to lala'),
(45, 400000000, 100, '2016-10-28', 'INR 100 transferred to dj'),
(46, 400000000, 200, '2016-10-28', 'INR 200 transferred to sdjk');

-- --------------------------------------------------------

--
-- Stand-in structure for view `user`
--
CREATE TABLE `user` (
`Name` varchar(20)
,`Login_password` varchar(20)
,`Transaction_password` varchar(20)
,`Account_number` bigint(20)
,`Customer_id` int(11)
,`Mobile number` bigint(20)
,`Email_id` varchar(30)
);

-- --------------------------------------------------------

--
-- Structure for view `user`
--
DROP TABLE IF EXISTS `user`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user`  AS  select `customer`.`Name` AS `Name`,`customer`.`Login_password` AS `Login_password`,`customer`.`Transaction_password` AS `Transaction_password`,`customer`.`Account_number` AS `Account_number`,`customer`.`Customer_id` AS `Customer_id`,`customer`.`Mobile number` AS `Mobile number`,`customer`.`Email_id` AS `Email_id` from `customer` where (`customer`.`Name` is not null) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`Account_number`),
  ADD KEY `Account_number` (`Account_number`),
  ADD KEY `Account_number_2` (`Account_number`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Account_number`),
  ADD UNIQUE KEY `Customer_id` (`Customer_id`);

--
-- Indexes for table `debit_card`
--
ALTER TABLE `debit_card`
  ADD PRIMARY KEY (`card_no`);

--
-- Indexes for table `deposit`
--
ALTER TABLE `deposit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`Lid`);

--
-- Indexes for table `payee`
--
ALTER TABLE `payee`
  ADD PRIMARY KEY (`Account_number`,`Payee_number`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`Tid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `debit_card`
--
ALTER TABLE `debit_card`
  MODIFY `card_no` bigint(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483647;
--
-- AUTO_INCREMENT for table `deposit`
--
ALTER TABLE `deposit`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `loan`
--
ALTER TABLE `loan`
  MODIFY `Lid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `Tid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
