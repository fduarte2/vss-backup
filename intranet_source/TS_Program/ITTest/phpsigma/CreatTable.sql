-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jun 27, 2008 at 09:39 AM
-- Server version: 5.0.45
-- PHP Version: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `sigma_grid_server`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `orders`
-- 

CREATE TABLE `orders` (
  `order_no` int(11) NOT NULL auto_increment,
  `employee` varchar(31) NOT NULL,
  `country` varchar(2) NOT NULL,
  `customer` varchar(31) NOT NULL,
  `order2005` float NOT NULL,
  `order2006` float NOT NULL,
  `order2007` float NOT NULL,
  `order2008` float NOT NULL,
  `delivery_date` datetime NOT NULL,
  PRIMARY KEY  (`order_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

-- 
-- Dumping data for table `orders`
-- 

INSERT INTO `orders` (`order_no`, `employee`, `country`, `customer`, `order2005`, `order2006`, `order2007`, `order2008`, `delivery_date`) VALUES 
(1, 'Dent Carry', 'US', 'Sigmasoft L', 33.9, 56.9, 453.9, 120.7, '2008-06-24 14:10:46'),
(5, 'RA', 'Ed', 'Sidney', 61, 31, 80, 47, '2008-10-16 00:00:00'),
(6, 'CA', 'Pa', 'Solomon', 82, 70, 33, 38, '2008-04-21 00:00:00'),
(7, 'MA', 'Le', 'Glendon', 90, 77, 98, 36, '2008-08-07 00:00:00'),
(8, 'SP', 'Te', 'Edwin', 64, 47, 84, 41, '2008-10-04 00:00:00'),
(9, 'SP', 'Br', 'Mike', 35, 73, 97, 83, '2008-03-02 00:00:00'),
(10, 'MA', 'Je', 'Keith', 50, 57, 80, 46, '2008-10-02 00:00:00'),
(11, 'CA', 'Ev', 'Chris', 66, 76, 43, 63, '2008-04-04 00:00:00'),
(12, 'IN', 'Ro', 'Samuel', 63, 49, 85, 68, '2008-07-11 00:00:00'),
(13, 'MA', 'Gl', 'Oscar', 94, 96, 62, 33, '2008-11-16 00:00:00'),
(14, 'MA', 'Co', 'Peter', 76, 86, 77, 67, '2008-12-12 00:00:00'),
(15, 'MA', 'Ja', 'Benson', 88, 96, 56, 33, '2008-11-16 00:00:00'),
(16, 'UK', 'Fr', 'Lance', 87, 85, 47, 66, '2008-10-21 00:00:00'),
(17, 'MA', 'Bi', 'Donald', 64, 70, 76, 87, '2008-03-06 00:00:00'),
(18, 'CA', 'Se', 'Hunk', 50, 45, 46, 72, '2008-09-18 00:00:00'),
(19, 'IN', 'Ke', 'Bob', 62, 64, 61, 61, '2008-01-19 00:00:00'),
(20, 'US', 'Ja', 'Coli', 72, 92, 56, 41, '2008-11-20 00:00:00'),
(21, 'IN', 'Br', 'Ivan', 68, 62, 66, 78, '2008-02-13 00:00:00'),
(22, 'SP', 'Sa', 'Vern', 93, 66, 85, 71, '2008-12-18 00:00:00'),
(23, 'US', 'Br', 'Leopold', 45, 70, 52, 39, '2008-11-07 00:00:00'),
(24, 'CA', 'Le', 'Caspar', 62, 94, 53, 88, '2008-03-18 00:00:00'),
(25, 'SP', 'Ju', 'Joe', 38, 97, 35, 74, '2008-07-31 00:00:00'),
(26, 'UK', 'Co', 'Thomas', 55, 58, 62, 46, '2008-07-19 00:00:00'),
(27, 'MA', 'Gi', 'Andrew', 33, 52, 68, 67, '2008-11-11 00:00:00'),
(28, 'SP', 'Br', 'Gary', 61, 54, 62, 42, '2008-08-16 00:00:00'),
(29, 'RA', 'Ry', 'Bob', 83, 50, 34, 46, '2008-12-01 00:00:00'),
(30, 'US', 'Ga', 'Johnny', 45, 56, 36, 79, '2008-02-23 00:00:00'),
(31, 'CA', 'Ra', 'Carl', 59, 42, 65, 47, '2008-09-20 00:00:00'),
(42, 'MA', 'Je', 'Keith', 50, 57, 80, 46, '2008-10-02 00:00:00'),
(43, 'SP', 'Ch', 'Marks', 79, 37, 40, 90, '2008-04-24 00:00:00'),
(45, 'RA', 'Ed', 'Sidney', 61, 31, 80, 47, '2008-10-16 00:00:00');
