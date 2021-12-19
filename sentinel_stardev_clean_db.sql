-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 19, 2021 at 05:30 PM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sentinel_ph_stardev`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `master_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `title`, `master_id`, `created`, `created_by`, `updated`, `updated_by`) VALUES
(2, 'Customers', 1, '2019-10-01 16:15:42', 1, '0000-00-00 00:00:00', 0),
(3, 'Dealers', 1, '2019-10-01 16:22:16', 1, '0000-00-00 00:00:00', 0),
(4, 'Vendors', 1, '2019-10-01 16:22:26', 1, '0000-00-00 00:00:00', 0),
(1, 'Head Office', 0, '2019-12-10 13:03:37', 1, '0000-00-00 00:00:00', 0),
(5, 'Land Owners', 1, '2020-01-20 19:23:55', 5, '0000-00-00 00:00:00', 0),
(6, 'Investors', 1, '2020-01-20 19:28:13', 5, '0000-00-00 00:00:00', 0),
(7, 'Partners', 1, '2020-01-20 19:28:56', 5, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `short_title` varchar(10) NOT NULL,
  `account_number` varchar(100) NOT NULL,
  `branch_code` varchar(10) NOT NULL,
  `branch_address` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `title`, `short_title`, `account_number`, `branch_code`, `branch_address`, `created`, `created_by`, `updated`, `updated_by`) VALUES
(1, 'Muslim Commercial Bank', 'MCB-ST', '', '', 'Satellite Town, Bahawalpur', '2012-03-13 15:12:57', 1, '2012-03-13 15:13:59', 1),
(2, 'Habib Bank Ltd.', 'HBL-FG', '', '', 'Farid Gate Branch, Bahawalpur', '2012-03-13 15:13:52', 1, '0000-00-00 00:00:00', 0),
(3, 'Muslim Commercial Bank', 'MCB-FG', '', '', 'Farid Gate Branch, Bahawalpur', '2012-03-13 15:14:11', 1, '0000-00-00 00:00:00', 0),
(4, 'Faysal Bank', 'FBL', '', '', 'Cantt Road, Bahawalpur', '2012-03-13 15:14:38', 1, '2021-07-07 08:25:08', 2),
(5, 'Allied Bank', 'ABL-OU', '', '', 'One Unit Chowk, Bahawalpur', '2012-03-13 15:15:05', 1, '0000-00-00 00:00:00', 0),
(6, 'KASB Bank', 'KASB-CR', '', '', 'Circular Road, Bahawalpur', '2012-03-13 15:15:22', 1, '0000-00-00 00:00:00', 0),
(7, 'Meezan Bank', 'MBL-MC', '', '', 'Meelad Chowk, Bahawalpur', '2012-03-13 15:15:51', 1, '0000-00-00 00:00:00', 0),
(8, 'Allied Bank', 'ABL-CR', '', '', 'Circular Road, Bahawalpur', '2012-03-13 15:16:08', 1, '0000-00-00 00:00:00', 0),
(9, 'Silk Bank', 'Silk', '', '', 'Circular Road, Bahawalpur', '2012-03-13 15:16:19', 1, '2021-02-26 05:14:45', 2),
(10, 'Soneri Bank', 'Soneri', '', '', 'Circular Road, Bahawalpur', '2012-03-13 15:17:04', 1, '2021-06-11 16:30:28', 2),
(11, 'United Bank', 'UBL-FG', '', '', 'Farid Gate Branch, Bahawalpur', '2012-03-13 15:17:22', 1, '0000-00-00 00:00:00', 0),
(12, 'Bank Al-Habib', 'BAH', '', '', 'Circular Road, Bahawalpur', '2012-03-13 15:17:36', 1, '2021-06-19 08:22:33', 2),
(13, 'Bank Alfalah', 'Alfalah-MT', '', '', 'Model Town B, Bahawalpur', '2012-03-14 16:15:41', 1, '0000-00-00 00:00:00', 0),
(14, 'Muslim Commercial Bank', 'MCB-RYK', '', '0808', 'Adda Jan Pur, Rahim Yar Khan', '2012-03-17 12:57:09', 1, '0000-00-00 00:00:00', 0),
(15, 'Muslim Commercial Bank', 'MCB', '', '', 'Unknown', '2012-03-19 17:03:21', 1, '0000-00-00 00:00:00', 0),
(16, 'United Bank', 'UBL', '', '', 'Unknown', '2012-03-19 17:03:37', 1, '0000-00-00 00:00:00', 0),
(17, 'Bank of Punjab', 'BOP', '', '', 'Circular Road, Bahawalpur', '2012-03-29 16:43:17', 1, '0000-00-00 00:00:00', 0),
(18, 'Habib Bank Ltd.', 'HBL', '', '', 'Unknown', '2012-04-02 14:01:06', 1, '0000-00-00 00:00:00', 0),
(19, 'First MicroFinance Bank', 'First-MFB', '', '', 'Ahmedpur Gate Branch, Bahawalpur', '2012-04-17 12:18:47', 1, '0000-00-00 00:00:00', 0),
(20, 'National Bank of Pakistan', 'NBP', '', '', 'Kahror Pakka', '2012-05-19 13:26:35', 1, '0000-00-00 00:00:00', 0),
(21, 'HSBC Ltd.', 'HSBC', '', '', 'Bahawalpur', '2012-06-22 17:00:52', 1, '0000-00-00 00:00:00', 0),
(22, 'Noor Islamic Bank', 'NIB', '', '', 'Bahawalpur', '2012-06-22 17:02:32', 12, '0000-00-00 00:00:00', 0),
(23, 'Dubai Islamic Bank', 'DIB', '', '', 'N/A', '2012-07-09 10:07:39', 1, '0000-00-00 00:00:00', 0),
(24, 'JS Bank Limited', 'JS Bank', '', '9056', 'Property No. 128, Model Town- B, Ghala  Mandi Road, Bahawalpur', '2012-12-31 16:01:56', 12, '0000-00-00 00:00:00', 0),
(25, 'Bank Islami Pakistan Limited', 'BIP', '', '', 'Circular Road', '2013-01-21 12:36:52', 12, '0000-00-00 00:00:00', 0),
(26, 'National Savings Center', 'NSC', '', '', 'N/A', '2013-02-06 14:43:20', 12, '0000-00-00 00:00:00', 0),
(27, 'Bank of Khaiber', 'BOK', '', '', 'Liabrary Chowk', '2013-02-16 13:14:07', 12, '0000-00-00 00:00:00', 0),
(28, 'Khushali Bank Limited', 'Khushali B', '', '', 'N/A', '2013-04-13 11:08:11', 12, '0000-00-00 00:00:00', 0),
(29, 'Sindh Bank', 'SB', '', '', 'N/A', '2014-03-11 15:23:23', 12, '2021-02-26 05:15:02', 2),
(30, 'Askari Bank', 'Askari', '', '', 'Circular Road Bahawalpur', '2017-11-01 11:58:33', 16, '2017-11-01 12:00:17', 16),
(31, 'Alfalah Bank', 'BAF', '', '', 'Bahawalpur', '2019-10-04 12:28:52', 2, '2021-10-15 07:47:01', 2),
(32, 'NRSP Micro finance Bank Limited,Bahawalpur. ', 'NRSP', '', '', 'Bahawalpur', '2020-01-31 08:05:14', 3, '0000-00-00 00:00:00', 0),
(33, 'AL BARKA', 'ALBARKA', '', '', 'Bahawalpur', '2020-04-09 10:58:25', 2, '0000-00-00 00:00:00', 0),
(34, 'FINCA Microfinance Bank', 'FINCA', '', '', 'Bahawalpur', '2020-05-09 12:15:03', 2, '0000-00-00 00:00:00', 0),
(35, 'Apna Bank Pvt Ltd.', 'APNA-BANK', '', '', 'Lodhran Branch', '2020-06-18 12:28:43', 3, '2020-06-18 12:29:19', 2),
(36, 'Meezan Bank', 'MBL-ST', '', '', 'one unit chowk', '2021-06-06 20:40:25', 1, '0000-00-00 00:00:00', 0),
(37, 'Allied Bank', 'ABL', '', '', 'Bank', '2021-06-10 13:44:54', 1, '0000-00-00 00:00:00', 0),
(38, 'Bank Islami', 'B-Islami', '', '', 'Bahawalpur', '2021-06-30 17:58:19', 1, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `title`, `created`, `created_by`, `updated`, `updated_by`) VALUES
(1, 'Bahawalpur', '2012-03-13 15:17:44', 1, '0000-00-00 00:00:00', 0),
(2, 'Hasilpur', '2012-03-13 15:17:48', 1, '0000-00-00 00:00:00', 0),
(3, 'Lodhran', '2012-03-13 15:17:52', 1, '0000-00-00 00:00:00', 0),
(4, 'Ahmad Pur East', '2012-03-13 15:30:39', 1, '0000-00-00 00:00:00', 0),
(5, 'Rahim Yar Khan', '2012-03-13 15:30:47', 1, '0000-00-00 00:00:00', 0),
(6, 'Channi Goth', '2012-03-13 15:30:52', 1, '0000-00-00 00:00:00', 0),
(7, 'Dera Bakha', '2012-03-13 15:30:57', 1, '0000-00-00 00:00:00', 0),
(8, 'Multan', '2012-03-13 15:31:04', 1, '0000-00-00 00:00:00', 0),
(9, 'Chishtian', '2012-03-13 15:31:17', 1, '2012-03-13 15:31:26', 1),
(10, 'Bahawal Nagar', '2012-03-13 15:32:10', 1, '0000-00-00 00:00:00', 0),
(11, 'Lahore', '2012-03-15 18:35:27', 1, '0000-00-00 00:00:00', 0),
(12, 'Karachi', '2012-03-15 18:35:35', 1, '0000-00-00 00:00:00', 0),
(13, 'Islamabad', '2012-03-15 18:35:40', 1, '0000-00-00 00:00:00', 0),
(14, 'Peshawar', '2012-03-15 18:35:43', 1, '0000-00-00 00:00:00', 0),
(15, 'Rawalpindi', '2012-03-15 18:35:48', 1, '0000-00-00 00:00:00', 0),
(16, 'Faisalabad', '2012-03-15 18:35:52', 1, '0000-00-00 00:00:00', 0),
(17, 'Sheikhupura', '2012-03-15 18:36:02', 1, '0000-00-00 00:00:00', 0),
(18, 'Fort Abbas', '2012-03-15 18:36:25', 1, '0000-00-00 00:00:00', 0),
(19, 'Sakhar', '2012-03-15 18:36:36', 1, '0000-00-00 00:00:00', 0),
(20, 'Haiderabad', '2012-03-15 18:36:40', 1, '0000-00-00 00:00:00', 0),
(21, 'Nawab Shah', '2012-03-15 18:36:44', 1, '0000-00-00 00:00:00', 0),
(22, 'Khanpur', '2012-03-15 18:36:53', 1, '0000-00-00 00:00:00', 0),
(23, 'Liaqatpur', '2012-03-15 18:37:00', 1, '2012-03-15 18:37:10', 1),
(24, 'Mian Channu', '2012-03-20 13:40:11', 1, '0000-00-00 00:00:00', 0),
(25, 'Quetta', '2012-03-20 13:57:18', 1, '0000-00-00 00:00:00', 0),
(26, 'Yazman', '2012-03-20 14:42:10', 1, '0000-00-00 00:00:00', 0),
(27, 'Dunya Pur', '2012-03-20 14:58:58', 1, '0000-00-00 00:00:00', 0),
(28, 'Rajan Pur', '2012-03-20 15:07:17', 1, '0000-00-00 00:00:00', 0),
(29, 'Noor Pur Noranga', '2012-03-20 15:55:41', 1, '0000-00-00 00:00:00', 0),
(30, 'Jamalpur', '2012-03-29 15:21:53', 1, '0000-00-00 00:00:00', 0),
(31, 'Kehror Pakka', '2012-10-10 11:20:07', 12, '0000-00-00 00:00:00', 0),
(32, 'Mian Wali', '2014-10-10 10:20:25', 12, '0000-00-00 00:00:00', 0),
(33, 'Bakhar', '2014-12-30 15:28:50', 12, '0000-00-00 00:00:00', 0),
(34, 'Kalor Kot', '2014-12-30 15:29:18', 12, '0000-00-00 00:00:00', 0),
(35, 'Dera Ghazi Khan', '2018-04-16 10:22:49', 16, '0000-00-00 00:00:00', 0),
(36, 'Layyah', '2019-01-02 14:35:34', 16, '0000-00-00 00:00:00', 0),
(37, 'Naushahro Feroze', '2019-10-28 16:51:39', 2, '0000-00-00 00:00:00', 0),
(38, 'Khairpur Tamaiwali', '2019-10-28 17:25:35', 2, '0000-00-00 00:00:00', 0),
(39, 'Jalalpur Pirwala', '2019-10-29 12:43:42', 3, '0000-00-00 00:00:00', 0),
(40, 'Gojra', '2019-10-29 15:05:39', 2, '0000-00-00 00:00:00', 0),
(41, 'Khoshab', '2019-10-29 15:09:13', 2, '0000-00-00 00:00:00', 0),
(42, 'Sialkot', '2019-10-30 12:37:03', 2, '0000-00-00 00:00:00', 0),
(43, 'Gujranwala', '2019-10-31 11:19:22', 2, '0000-00-00 00:00:00', 0),
(44, 'Chicha Watni', '2019-10-31 16:45:15', 3, '0000-00-00 00:00:00', 0),
(45, 'Sahiwal', '2019-10-31 16:45:21', 3, '0000-00-00 00:00:00', 0),
(46, 'Sadiqabad', '2019-11-06 16:12:52', 3, '0000-00-00 00:00:00', 0),
(47, 'Melsi', '2020-01-21 07:13:50', 3, '0000-00-00 00:00:00', 0),
(48, 'Gujrat', '2020-02-11 05:55:58', 2, '0000-00-00 00:00:00', 0),
(49, 'Muzaffargarh', '2020-03-02 07:54:38', 2, '0000-00-00 00:00:00', 0),
(50, 'Okara', '2021-04-19 08:09:58', 2, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `title`, `created`, `created_by`, `updated`, `updated_by`) VALUES
(1, 'Pakistan', '2019-10-02 16:28:53', 1, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT '0',
  `account_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` int(11) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `state` varchar(30) NOT NULL,
  `country` int(11) NOT NULL,
  `phone_1` varchar(20) NOT NULL,
  `phone_2` varchar(20) NOT NULL,
  `mobile_1` varchar(20) NOT NULL,
  `mobile_2` varchar(20) NOT NULL,
  `fax_1` varchar(20) NOT NULL,
  `fax_2` varchar(20) NOT NULL,
  `email_1` varchar(50) NOT NULL,
  `email_2` varchar(50) NOT NULL,
  `id_num` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers_nominees`
--

CREATE TABLE `customers_nominees` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `relationship` varchar(30) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` int(11) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `state` varchar(30) NOT NULL,
  `country` int(11) NOT NULL,
  `phone_1` varchar(20) NOT NULL,
  `phone_2` varchar(20) NOT NULL,
  `mobile_1` varchar(20) NOT NULL,
  `mobile_2` varchar(20) NOT NULL,
  `fax_1` varchar(20) NOT NULL,
  `fax_2` varchar(20) NOT NULL,
  `email_1` varchar(50) NOT NULL,
  `email_2` varchar(50) NOT NULL,
  `id_num` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dealers`
--

CREATE TABLE `dealers` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT '0',
  `account_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` int(11) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `state` varchar(30) NOT NULL,
  `country` int(11) NOT NULL,
  `phone_1` varchar(20) NOT NULL,
  `phone_2` varchar(20) NOT NULL,
  `mobile_1` varchar(20) NOT NULL,
  `mobile_2` varchar(20) NOT NULL,
  `fax_1` varchar(20) NOT NULL,
  `fax_2` varchar(20) NOT NULL,
  `email_1` varchar(50) NOT NULL,
  `email_2` varchar(50) NOT NULL,
  `id_num` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `commission` decimal(9,2) NOT NULL,
  `commission_type` enum('P','F') NOT NULL DEFAULT 'F',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `deposit_accounts`
--

CREATE TABLE `deposit_accounts` (
  `id` int(11) NOT NULL,
  `account_number` varchar(100) NOT NULL,
  `account_title` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deposit_accounts`
--

INSERT INTO `deposit_accounts` (`id`, `account_number`, `account_title`, `created`, `created_by`, `updated`, `updated_by`) VALUES
(1, 'MCB/1150/DC CHK', 'SATLUJ VALLEY', '2020-03-22 19:44:29', 5, NULL, NULL),
(2, 'MCB/6437/FG', 'VALENCIA CITY', '2020-03-22 19:45:45', 5, NULL, NULL),
(3, 'MCB/7659/FG', 'PELICAN HOMES PVT LTD', '2020-03-22 19:46:16', 5, NULL, NULL),
(4, 'MCB/4986/DC CHK', 'TALAT MEHMOOD', '2020-03-22 19:46:46', 5, NULL, NULL),
(5, 'ABL/0010', 'AMAR MEHMOOD', '2020-03-22 19:55:33', 5, NULL, NULL),
(6, 'MCB/8121', 'TALAT MEHMOOD', '2020-03-22 19:56:51', 5, NULL, NULL),
(7, 'ASKARI/1579/LB CHK', 'TALAT MEHMOOD', '2020-03-22 19:58:28', 5, NULL, NULL),
(8, 'MCB/0234', 'TALAT MEHMOOD', '2020-03-22 19:59:45', 5, NULL, NULL),
(9, 'MBL/0555', 'TALAT MEHMOOD', '2020-03-22 20:01:48', 5, NULL, NULL),
(10, 'MCB/1620', 'BAHAWAL TOWN', '2020-03-22 20:03:56', 5, NULL, NULL),
(11, 'HANDOVER/AM', 'AMAR MEHMOOD', '2020-03-22 20:07:58', 5, NULL, NULL),
(12, 'HANDOVER/SN', 'SHAHNAWAZ', '2020-03-22 20:09:46', 5, NULL, NULL),
(13, 'HANDOVER/TM', 'TALAT MEHMOOD', '2020-03-22 20:10:21', 5, NULL, NULL),
(14, 'MCB/3772/FG', 'TALAT MEHMOOD', '2020-07-04 13:41:04', 1, NULL, NULL),
(15, 'FBL/0365/C-AREA', 'AMAR MEHMOOD', '2020-08-20 12:52:41', 1, NULL, NULL),
(16, 'MCB/8290/FG', 'TALAT MEHMOOD', '2020-08-20 12:53:02', 1, NULL, NULL),
(17, 'ASKARI/6845/LB CHK', 'AMAR MEHMOOD', '2020-09-01 08:59:15', 2, NULL, NULL),
(18, 'Adjustment', 'Amount Adjustment', '2020-09-09 13:32:00', 2, NULL, NULL),
(19, 'MCB/8804/FG', 'AMAR MEHMOOD', '2020-12-17 09:50:05', 2, NULL, NULL),
(20, 'HBL/1355/FG', 'AMAR MEHMOOD	', '2021-04-29 08:44:37', 2, NULL, NULL),
(21, 'MBL/1444/C-AREA', 'AMAR MEHMOOD', '2021-05-25 12:13:40', 2, NULL, NULL),
(22, 'SILK/5245', 'AMAR MEHMOOD', '2021-06-10 14:00:36', 2, NULL, NULL),
(23, 'HBL/1203/FG', 'AMAR MEHMOOD	', '2021-06-10 15:50:45', 2, NULL, NULL),
(24, 'SILK/5627', 'AMAR MEHMOOD	', '2021-06-10 16:39:35', 2, NULL, NULL),
(25, 'ABL/0141', 'TALAT MEHMOOD	', '2021-09-02 07:03:14', 2, NULL, NULL),
(26, 'ABL/0026', 'AMAR MEHMOOD	', '2021-09-02 09:08:19', 2, NULL, NULL),
(27, 'BAF/6015', 'TALAT MEHMOOD	', '2021-10-04 13:20:14', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `loan_account` int(11) NOT NULL,
  `salary_account` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` int(11) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `state` varchar(30) NOT NULL,
  `country` int(11) NOT NULL,
  `phone_1` varchar(20) NOT NULL,
  `phone_2` varchar(20) NOT NULL,
  `mobile_1` varchar(20) NOT NULL,
  `mobile_2` varchar(20) NOT NULL,
  `email_1` varchar(50) NOT NULL,
  `email_2` varchar(50) NOT NULL,
  `id_num` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `joining_date` date NOT NULL,
  `resign_date` date NOT NULL,
  `status` enum('EMPLOYED','RESIGNED','FIRED') NOT NULL DEFAULT 'EMPLOYED',
  `basic_salary` decimal(7,2) NOT NULL,
  `incentives` decimal(7,2) NOT NULL,
  `employee_id` varchar(10) NOT NULL,
  `desig` varchar(30) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `investor`
--

CREATE TABLE `investor` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT '0',
  `account_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` int(11) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `state` varchar(30) NOT NULL,
  `country` int(11) NOT NULL,
  `phone_1` varchar(20) NOT NULL,
  `phone_2` varchar(20) NOT NULL,
  `mobile_1` varchar(20) NOT NULL,
  `mobile_2` varchar(20) NOT NULL,
  `fax_1` varchar(20) NOT NULL,
  `fax_2` varchar(20) NOT NULL,
  `email_1` varchar(50) NOT NULL,
  `email_2` varchar(50) NOT NULL,
  `id_num` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `landowner`
--

CREATE TABLE `landowner` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT '0',
  `account_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` int(11) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `state` varchar(30) NOT NULL,
  `country` int(11) NOT NULL,
  `phone_1` varchar(20) NOT NULL,
  `phone_2` varchar(20) NOT NULL,
  `mobile_1` varchar(20) NOT NULL,
  `mobile_2` varchar(20) NOT NULL,
  `fax_1` varchar(20) NOT NULL,
  `fax_2` varchar(20) NOT NULL,
  `email_1` varchar(50) NOT NULL,
  `email_2` varchar(50) NOT NULL,
  `id_num` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `landowner_projects`
--

CREATE TABLE `landowner_projects` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `landowner_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `landowner_projects_dues`
--

CREATE TABLE `landowner_projects_dues` (
  `id` int(11) NOT NULL,
  `landowner_projects_id` int(11) NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `due_on` date NOT NULL,
  `notes` varchar(255) NOT NULL,
  `status` enum('DUE','CLEARED') NOT NULL DEFAULT 'DUE',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lookup_plot_features`
--

CREATE TABLE `lookup_plot_features` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `price` decimal(11,2) NOT NULL,
  `price_type` enum('P','F') NOT NULL DEFAULT 'P',
  `notes` text NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `partner`
--

CREATE TABLE `partner` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT '0',
  `account_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` int(11) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `state` varchar(30) NOT NULL,
  `country` int(11) NOT NULL,
  `phone_1` varchar(20) NOT NULL,
  `phone_2` varchar(20) NOT NULL,
  `mobile_1` varchar(20) NOT NULL,
  `mobile_2` varchar(20) NOT NULL,
  `fax_1` varchar(20) NOT NULL,
  `fax_2` varchar(20) NOT NULL,
  `email_1` varchar(50) NOT NULL,
  `email_2` varchar(50) NOT NULL,
  `id_num` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `plots`
--

CREATE TABLE `plots` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `dealer_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `commission_account` int(11) NOT NULL,
  `expense_account` int(11) NOT NULL,
  `plot_number` varchar(10) NOT NULL,
  `plot_type` enum('Residential','Shop','Other') NOT NULL DEFAULT 'Residential',
  `size` decimal(4,2) NOT NULL,
  `size_type` enum('Marla','Kanal') NOT NULL DEFAULT 'Marla',
  `width` decimal(5,2) NOT NULL,
  `length` decimal(5,2) NOT NULL,
  `rate_per_marla` int(11) NOT NULL,
  `status` enum('VACANT','SOLD','RESERVED','REGISTERED') NOT NULL DEFAULT 'VACANT',
  `notes` text NOT NULL,
  `discount` decimal(9,2) NOT NULL,
  `discount_type` enum('P','F') NOT NULL DEFAULT 'F',
  `commission` decimal(9,2) NOT NULL,
  `commission_type` enum('P','F') NOT NULL DEFAULT 'F',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `plots_dues`
--

CREATE TABLE `plots_dues` (
  `id` int(11) NOT NULL,
  `plot_id` int(11) NOT NULL,
  `dues_type` enum('INSTALMENT','OTHER','ADVANCE') NOT NULL DEFAULT 'INSTALMENT',
  `amount` decimal(11,2) NOT NULL,
  `due_on` date NOT NULL,
  `notes` varchar(255) NOT NULL,
  `status` enum('DUE','CLEARED') NOT NULL DEFAULT 'DUE',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `plots_features`
--

CREATE TABLE `plots_features` (
  `id` int(11) NOT NULL,
  `plot_id` int(11) NOT NULL,
  `feature_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `rate_per_marla` int(11) NOT NULL,
  `short_code` char(2) NOT NULL,
  `expense_percentage` text,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `account_id`, `title`, `description`, `rate_per_marla`, `short_code`, `expense_percentage`, `created`, `created_by`, `updated`, `updated_by`) VALUES
(1, 1, 'Head Office', '', 400000, 'HO', NULL, '2021-12-19 17:21:56', 5, '2021-12-19 17:23:45', 5);

-- --------------------------------------------------------

--
-- Table structure for table `system_log`
--

CREATE TABLE `system_log` (
  `id` int(11) UNSIGNED NOT NULL,
  `module_id` varchar(10) NOT NULL,
  `operation` enum('ACCESS','CREATE','MODIFY','DELETE','VIEW','PRINT','REPRINT','LOGIN','LOGOUT') NOT NULL,
  `details` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `access_ip` varchar(30) NOT NULL,
  `host_name` varchar(255) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `system_log`
--

INSERT INTO `system_log` (`id`, `module_id`, `operation`, `details`, `user_id`, `access_ip`, `host_name`, `created`) VALUES
(1, 'AUTH', 'LOGIN', 'Login attempt. Array\n(\n    [username] => developer\n    [passwd] => alpha1\n)\n', 0, '182.185.207.149', '182.185.207.149', '2021-12-19 17:09:09'),
(2, 'AUTH', 'LOGIN', 'User logged in. Array\n(\n    [username] => developer\n    [passwd] => alpha1\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:09:09'),
(3, 'DASHBOARD', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:09:09'),
(4, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:09:36'),
(5, 'PRJ003', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => plots.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:09:39'),
(6, 'ACT006', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => employees.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:09:43'),
(7, 'ACT003', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => invoices.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:09:46'),
(8, 'ACT004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:09:49'),
(9, 'ACT004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:09:57'),
(10, 'CST001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => customers.add\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:10:06'),
(11, 'CST002', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => customers.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:10:08'),
(12, 'CST004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => nominees.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:10:11'),
(13, 'DLR002', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => dealers.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:10:16'),
(14, 'LDO002', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => landowner.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:10:19'),
(15, 'INV002', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => investor.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:10:22'),
(16, 'PTR002', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => partner.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:10:27'),
(17, 'RPT011', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => rpt.projects.statement\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:10:30'),
(18, 'RPT009', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => rpt.due_installments\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:10:38'),
(19, 'SYS002', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => users.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:10:45'),
(20, 'LUD001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => lookup.banks\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:10:55'),
(21, 'LUD002', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => lookup.cities\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:11:02'),
(22, 'LUD003', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => lookup.countries\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:11:07'),
(23, 'LUD004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => lookup.plot_features\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:11:10'),
(24, 'LUD005', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => lookup.head_office_percentage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:11:14'),
(25, 'LUD006', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => lookup.deposit_accounts\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:11:18'),
(26, 'SYS006', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => users.tasks\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:11:26'),
(27, 'SYS007', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => users.assigned_tasks\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:11:29'),
(28, 'SYS008', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => database_backup\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:11:32'),
(29, 'DASHBOARD', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:11:36'),
(30, 'ACT004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:11:38'),
(31, 'ACT004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 2786\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:11:43'),
(32, 'ACT004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 2786\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:20:51'),
(33, 'ACT004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:20:55'),
(34, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:21:04'),
(35, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [cmd] => ADD\n    [id] => \n    [title] => Head Office\n    [rate_per_marla] => \n    [master_id] => \n    [short_code] => HO\n    [description] => \n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:21:35'),
(36, 'PRJ001', 'CREATE', 'Operation failed. [Reason: Required fields were empty.] Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [cmd] => ADD\n    [id] => \n    [title] => Head Office\n    [rate_per_marla] => \n    [master_id] => \n    [short_code] => HO\n    [description] => \n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:21:35'),
(37, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [cmd] => ADD\n    [id] => \n    [title] => Head Office\n    [rate_per_marla] => 0\n    [master_id] => \n    [short_code] => HO\n    [description] => \n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:21:42'),
(38, 'PRJ001', 'CREATE', 'Operation failed. [Reason: Required fields were empty.] Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [cmd] => ADD\n    [id] => \n    [title] => Head Office\n    [rate_per_marla] => 0\n    [master_id] => \n    [short_code] => HO\n    [description] => \n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:21:42'),
(39, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [cmd] => ADD\n    [id] => \n    [title] => Head Office\n    [rate_per_marla] => 0\n    [master_id] => \n    [new_account] => Y\n    [short_code] => HO\n    [description] => \n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:21:47'),
(40, 'PRJ001', 'CREATE', 'Operation failed. [Reason: Required fields were empty.] Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [cmd] => ADD\n    [id] => \n    [title] => Head Office\n    [rate_per_marla] => 0\n    [master_id] => \n    [new_account] => Y\n    [short_code] => HO\n    [description] => \n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:21:47'),
(41, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [cmd] => ADD\n    [id] => \n    [title] => Head Office\n    [rate_per_marla] => 100000\n    [master_id] => \n    [short_code] => HO\n    [description] => \n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:21:56'),
(42, 'PRJ001', 'CREATE', 'Head Office created. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [cmd] => ADD\n    [id] => \n    [title] => Head Office\n    [rate_per_marla] => 100000\n    [master_id] => \n    [short_code] => HO\n    [description] => \n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:21:56'),
(43, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:22:01'),
(44, 'PRJ001', 'MODIFY', '1 loaded for editing. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:22:01'),
(45, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:22:04'),
(46, 'PRJ001', 'MODIFY', '1 loaded for editing. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:22:04'),
(47, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:22:21'),
(48, 'PRJ001', 'MODIFY', '1 loaded for editing. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:22:21'),
(49, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [cmd] => UPDATE\n    [id] => 1\n    [title] => Head Office\n    [rate_per_marla] => 100000\n    [master_id] => \n    [new_account] => Y\n    [short_code] => HO\n    [description] => \n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:22:24'),
(50, 'PRJ001', 'MODIFY', 'New account (Head Office - 8) created. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [cmd] => UPDATE\n    [id] => 1\n    [title] => Head Office\n    [rate_per_marla] => 100000\n    [master_id] => \n    [new_account] => Y\n    [short_code] => HO\n    [description] => \n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:22:24'),
(51, 'PRJ001', 'MODIFY', 'Head Office updated. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [cmd] => UPDATE\n    [id] => 1\n    [title] => Head Office\n    [rate_per_marla] => 100000\n    [master_id] => \n    [new_account] => Y\n    [short_code] => HO\n    [description] => \n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:22:24'),
(52, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:22:27'),
(53, 'PRJ001', 'MODIFY', '1 loaded for editing. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:22:27'),
(54, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:22:53'),
(55, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:22:56'),
(56, 'PRJ001', 'MODIFY', '1 loaded for editing. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:22:56'),
(57, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [cmd] => UPDATE\n    [id] => 1\n    [title] => Head Office\n    [rate_per_marla] => 400000\n    [master_id] => 8\n    [short_code] => HO\n    [description] => \n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:23:45'),
(58, 'PRJ001', 'MODIFY', 'Head Office updated. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [cmd] => UPDATE\n    [id] => 1\n    [title] => Head Office\n    [rate_per_marla] => 400000\n    [master_id] => 8\n    [short_code] => HO\n    [description] => \n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:23:45'),
(59, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:23:48'),
(60, 'PRJ001', 'MODIFY', '1 loaded for editing. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:23:48'),
(61, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:24:48'),
(62, 'PRJ001', 'MODIFY', '1 loaded for editing. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:24:48'),
(63, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:25:00'),
(64, 'PRJ001', 'MODIFY', '1 loaded for editing. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:25:00'),
(65, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:25:04'),
(66, 'ACT004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:25:10'),
(67, 'ACT004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:25:12'),
(68, 'ACT004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:25:23'),
(69, 'ACT004', 'MODIFY', '1 loaded for editing. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:25:23'),
(70, 'ACT004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:25:34'),
(71, 'ACT004', 'MODIFY', '1 loaded for editing. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:25:34'),
(72, 'ACT004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:26:07'),
(73, 'ACT004', 'MODIFY', '1 loaded for editing. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:26:07'),
(74, 'ACT004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:27:00'),
(75, 'ACT004', 'MODIFY', '1 loaded for editing. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:27:00'),
(76, 'ACT004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n    [id] => 6\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:27:11'),
(77, 'ACT004', 'MODIFY', '6 loaded for editing. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n    [id] => 6\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:27:11'),
(78, 'ACT004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n    [id] => 2\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:27:14'),
(79, 'ACT004', 'MODIFY', '2 loaded for editing. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n    [id] => 2\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:27:14'),
(80, 'ACT004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n    [id] => 4\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:27:17'),
(81, 'ACT004', 'MODIFY', '4 loaded for editing. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n    [id] => 4\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:27:17'),
(82, 'ACT004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:27:21'),
(83, 'ACT004', 'MODIFY', '1 loaded for editing. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:27:21'),
(84, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:27:27'),
(85, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:27:29'),
(86, 'PRJ001', 'MODIFY', '1 loaded for editing. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n    [id] => 1\n    [cmd] => EDIT\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:27:30'),
(87, 'DASHBOARD', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:27:36'),
(88, 'RPT011', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => rpt.projects.statement\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:27:43'),
(89, 'RPT011', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => rpt.projects.statement\n    [project_id] => 1\n    [report_month_from] => December 2021\n    [report_month_to] => December 2021\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:27:49'),
(90, 'DASHBOARD', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:27:56'),
(91, 'DASHBOARD', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:27:57'),
(92, 'LUD005', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => lookup.head_office_percentage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:28:55'),
(93, 'LUD005', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => lookup.head_office_percentage\n    [id] => 1\n    [cmd] => VIEW\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:28:57'),
(94, 'LUD005', 'MODIFY', '1 loaded for editing. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => lookup.head_office_percentage\n    [id] => 1\n    [cmd] => VIEW\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:28:57'),
(95, 'DASHBOARD', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:29:02'),
(96, 'PRJ001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => projects.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:29:15'),
(97, 'CST001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => customers.add\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:29:19'),
(98, 'CST001', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => customers.add\n    [project_id] => 1\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:29:24'),
(99, 'DASHBOARD', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:29:38'),
(100, 'ACT004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:29:44'),
(101, 'ACT004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n    [head_account] => 1\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:29:47'),
(102, 'ACT004', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => accounts.manage\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:29:51'),
(103, 'ACT002', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => invoices.add.wizard\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:29:55'),
(104, 'ACT002', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n    [mod] => invoices.add.wizard\n    [project_id] => 1\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:29:58'),
(105, 'DASHBOARD', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:30:16'),
(106, 'DASHBOARD', 'ACCESS', 'Module was accessed. Array\n(\n    [ss] => 0799bc62eff3174abb8eef7834b2243b\n)\n', 5, '182.185.207.149', '182.185.207.149', '2021-12-19 17:30:23');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `var_name` varchar(30) DEFAULT NULL,
  `var_value` text,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `var_name`, `var_value`, `created`, `updated`) VALUES
(1, 'CFG_SESSION_TIMEOUT', '3600', '2019-10-01 15:49:18', NULL),
(2, 'CFG_OFFICE_ADDRESS', 'Darbar Mahal Road, Near Sadiq Girls School, Bahawalpur', '2019-10-01 15:49:18', NULL),
(3, 'CFG_OFFICE_PHONE', '062-228-4411', '2019-10-01 15:49:18', NULL),
(4, 'CFG_OFFICE_EMAIL', 'info@stardevelopers.pk', '2019-10-01 15:49:18', NULL),
(5, 'CFG_OFFICE_WEB', 'www.StarDevelopers.pk', '2019-10-01 15:49:18', NULL),
(6, 'CFG_OFFICE_NAME', 'Star Developers', '2019-10-01 15:49:18', NULL),
(7, 'CFG_ACCOUNT_CUSTOMERS', '2', '2019-10-01 15:49:18', NULL),
(8, 'CFG_ACCOUNT_VENDORS', '4', '2019-10-01 15:49:18', NULL),
(9, 'CFG_ACCOUNT_DEALERS', '3', '2019-10-01 15:49:18', NULL),
(10, 'CFG_ACCOUNT_LANDOWNERS', '5', '2020-01-21 00:29:00', NULL),
(11, 'CFG_ACCOUNT_INVESTORS', '6', '2020-01-21 00:29:00', NULL),
(12, 'CFG_ACCOUNT_PARTNERS', '7', '2020-01-21 00:29:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) UNSIGNED NOT NULL,
  `project_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `voucher_id` varchar(20) NOT NULL,
  `transaction_type` enum('PAYMENT','RECEIPT') NOT NULL,
  `notes` text NOT NULL,
  `invoice_date` date NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `auth_status` enum('AUTH','PENDING') NOT NULL DEFAULT 'PENDING',
  `auth_by` int(11) NOT NULL,
  `auth_on` datetime NOT NULL,
  `handover_status` enum('HANDOVER','PENDING') NOT NULL DEFAULT 'PENDING',
  `received_by` int(11) NOT NULL,
  `received_on` datetime NOT NULL,
  `print_count` int(11) NOT NULL DEFAULT '0',
  `printed_by` int(11) NOT NULL,
  `printed_on` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transactions_details`
--

CREATE TABLE `transactions_details` (
  `id` int(11) UNSIGNED NOT NULL,
  `transaction_id` int(11) UNSIGNED NOT NULL,
  `account_id` int(11) NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `voucher_type` enum('CASH','BANK') NOT NULL,
  `bank_id` int(11) NOT NULL,
  `cheque_number` varchar(15) NOT NULL,
  `cheque_date` date NOT NULL,
  `cheque_name` varchar(100) NOT NULL,
  `cheque_total_amount` decimal(11,2) NOT NULL DEFAULT '0.00',
  `check_installment` enum('yes','no') NOT NULL DEFAULT 'no',
  `post_date` enum('Y','N') NOT NULL DEFAULT 'N',
  `clearance_status` enum('CLEARED','PENDING','BOUNCED') NOT NULL DEFAULT 'PENDING',
  `deposit_slip_num` varchar(20) NOT NULL,
  `deposit_date` date NOT NULL,
  `deposit_in` int(11) NOT NULL DEFAULT '0',
  `deposit_acc_title` varchar(100) DEFAULT NULL,
  `deposit_remarks` text NOT NULL,
  `cleared_on` datetime NOT NULL,
  `cleared_by` int(11) NOT NULL,
  `clearance_log` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `passwd` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `notes` text NOT NULL,
  `status` enum('ACTIVE','LOCKED','SUSPENDED') NOT NULL DEFAULT 'ACTIVE',
  `multi_login` enum('Y','N') NOT NULL DEFAULT 'N',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `passwd`, `full_name`, `mobile`, `notes`, `status`, `multi_login`, `created`, `created_by`, `updated`, `updated_by`) VALUES
(1, 'su', '915e28468973b839511614825fe58a30', 'Amar Mahmood', '1234567', 'Super User', 'ACTIVE', 'Y', '2019-01-28 10:52:17', 1, '2021-05-26 21:15:39', 1),
(2, 'umair1988', '3410c9c2b26954152aa17a4e4118c711', 'Umair Aslam', '03314962899', '', 'LOCKED', 'N', '2019-10-03 13:34:25', 1, '2021-10-22 12:58:13', 1),
(3, 'Masuod66', '4b76f6272fda46323824323169cb107d', 'M.Masuod Ahmad', '03005782051', '', 'ACTIVE', 'N', '2019-10-03 13:39:11', 1, '2021-04-17 08:45:04', 1),
(4, 'GM', '493d2d3604deea7f9df5c46b88ed009c', 'Abdul Jabbar', '03216804900', '', 'ACTIVE', 'N', '2019-10-10 14:07:35', 1, '2021-04-17 08:44:26', 1),
(5, 'developer', '8dbc2828a56856fc152437bd551628b5', 'Kamran Sarwar', '03366724648', 'Developer Access Account', 'ACTIVE', 'Y', '2019-11-28 18:21:10', 1, '2020-09-27 19:48:36', 5),
(6, 'Test User', '0c253085dfd6cfe9ca2e85e7153d3c50', 'Test User', '03008388449', '', 'ACTIVE', 'Y', '2020-02-10 12:41:23', 1, '2020-08-30 14:00:44', 1),
(7, 'Waqas Javed', 'ba35b012a91bbc72b953d1b4dad5c885', 'Waqas Javed', '03333618885', '', 'ACTIVE', 'N', '2020-06-22 14:49:20', 1, '2020-08-31 22:42:25', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_access_control`
--

CREATE TABLE `users_access_control` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `allowed_ip` varchar(30) NOT NULL DEFAULT '*',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_access_control`
--

INSERT INTO `users_access_control` (`id`, `user_id`, `allowed_ip`, `created`, `created_by`, `updated`, `updated_by`) VALUES
(71, 1, '*', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(88, 2, '182.176.123.35', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(86, 3, '182.176.123.35', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(85, 4, '182.176.123.35', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(84, 5, '*', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(74, 6, '*', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(79, 7, '182.176.123.35', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_messages`
--

CREATE TABLE `users_messages` (
  `id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `priority` enum('HIGH','MEDIUM','LOW') NOT NULL DEFAULT 'LOW',
  `status` enum('READ','UNREAD') NOT NULL DEFAULT 'UNREAD',
  `message_type` enum('NORMAL','ALERT','NOTICE','WARNING','SMS') NOT NULL DEFAULT 'NORMAL',
  `msisdn` varchar(20) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `ajax_notice` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users_permissions`
--

CREATE TABLE `users_permissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `module_id` varchar(10) NOT NULL,
  `operation` enum('ACCESS','CREATE','MODIFY','DELETE','VIEW','PRINT','REPRINT') NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_permissions`
--

INSERT INTO `users_permissions` (`id`, `user_id`, `module_id`, `operation`, `created`, `created_by`, `updated`, `updated_by`) VALUES
(13650, 1, 'LUD006', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13649, 1, 'LUD006', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13648, 1, 'LUD006', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13647, 1, 'LUD005', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13646, 1, 'LUD005', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13645, 1, 'LUD005', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13644, 1, 'LUD004', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13643, 1, 'LUD004', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13642, 1, 'LUD004', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13641, 1, 'LUD004', 'DELETE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13640, 1, 'LUD004', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13639, 1, 'LUD004', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13638, 1, 'LUD004', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13637, 1, 'LUD003', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13636, 1, 'LUD003', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13635, 1, 'LUD003', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13634, 1, 'LUD003', 'DELETE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13633, 1, 'LUD003', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13632, 1, 'LUD003', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13631, 1, 'LUD003', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13630, 1, 'LUD002', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13629, 1, 'LUD002', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13628, 1, 'LUD002', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13627, 1, 'LUD002', 'DELETE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13626, 1, 'LUD002', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13625, 1, 'LUD002', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13624, 1, 'LUD002', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13623, 1, 'LUD001', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13622, 1, 'LUD001', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13621, 1, 'LUD001', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13620, 1, 'LUD001', 'DELETE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13619, 1, 'LUD001', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13618, 1, 'LUD001', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13617, 1, 'LUD001', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13616, 1, 'SYS008', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13615, 1, 'SYS008', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13614, 1, 'SYS007', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13613, 1, 'SYS007', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13612, 1, 'SYS007', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13611, 1, 'SYS006', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13610, 1, 'SYS006', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13609, 1, 'SYS006', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13608, 1, 'SYS006', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13607, 1, 'SYS005', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13606, 1, 'SYS005', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13605, 1, 'SYS004', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13604, 1, 'SYS004', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13603, 1, 'SYS004', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13602, 1, 'SYS003', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13601, 1, 'SYS003', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13600, 1, 'SYS003', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13599, 1, 'SYS002', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13598, 1, 'SYS002', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13597, 1, 'SYS001', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13596, 1, 'SYS001', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13595, 1, 'SYS001', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13594, 1, 'SYS001', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13593, 1, 'SYS001', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13592, 1, 'SYS001', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13591, 1, 'RPT011', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13590, 1, 'RPT011', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13589, 1, 'RPT011', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13588, 1, 'RPT011', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13587, 1, 'RPT010', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13586, 1, 'RPT010', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13585, 1, 'RPT010', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13584, 1, 'RPT010', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13583, 1, 'RPT009', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13582, 1, 'RPT009', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13581, 1, 'RPT009', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13580, 1, 'RPT009', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13579, 1, 'RPT008', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13578, 1, 'RPT008', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13577, 1, 'RPT008', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13576, 1, 'RPT008', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13575, 1, 'RPT007', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13574, 1, 'RPT007', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13573, 1, 'RPT007', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13572, 1, 'RPT007', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13571, 1, 'RPT006', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13570, 1, 'RPT006', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13569, 1, 'RPT006', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13568, 1, 'RPT006', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13567, 1, 'RPT005', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13566, 1, 'RPT005', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13565, 1, 'RPT005', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13564, 1, 'RPT005', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13563, 1, 'RPT004', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13562, 1, 'RPT004', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13561, 1, 'RPT004', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13560, 1, 'RPT004', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13559, 1, 'RPT004', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13558, 1, 'RPT003', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13557, 1, 'RPT003', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13556, 1, 'RPT003', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13555, 1, 'RPT003', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13554, 1, 'RPT002', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13553, 1, 'RPT002', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13552, 1, 'RPT002', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13551, 1, 'RPT002', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13550, 1, 'RPT001', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13549, 1, 'RPT001', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13548, 1, 'RPT001', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13547, 1, 'RPT001', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13546, 1, 'PTR002', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13545, 1, 'PTR002', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13544, 1, 'PTR002', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13543, 1, 'PTR002', 'DELETE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13542, 1, 'PTR002', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13541, 1, 'PTR002', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13540, 1, 'PTR001', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13539, 1, 'PTR001', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13538, 1, 'PTR001', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13537, 1, 'PTR001', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13536, 1, 'PTR001', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13535, 1, 'PTR001', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13534, 1, 'INV002', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13533, 1, 'INV002', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13532, 1, 'INV002', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13531, 1, 'INV002', 'DELETE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13530, 1, 'INV002', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13529, 1, 'INV002', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13528, 1, 'INV001', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13527, 1, 'INV001', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13526, 1, 'INV001', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13525, 1, 'INV001', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13524, 1, 'INV001', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13523, 1, 'INV001', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13522, 1, 'LDO003', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13521, 1, 'LDO003', 'DELETE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13520, 1, 'LDO003', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13519, 1, 'LDO003', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13518, 1, 'LDO003', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13517, 1, 'LDO002', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13516, 1, 'LDO002', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13515, 1, 'LDO002', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13514, 1, 'LDO002', 'DELETE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13513, 1, 'LDO002', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13512, 1, 'LDO002', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13511, 1, 'LDO001', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13510, 1, 'LDO001', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13509, 1, 'LDO001', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13508, 1, 'LDO001', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13507, 1, 'LDO001', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13506, 1, 'LDO001', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13505, 1, 'DLR002', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13504, 1, 'DLR002', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13503, 1, 'DLR002', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13502, 1, 'DLR002', 'DELETE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13501, 1, 'DLR002', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13500, 1, 'DLR002', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13499, 1, 'DLR001', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13498, 1, 'DLR001', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13497, 1, 'DLR001', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13496, 1, 'DLR001', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13495, 1, 'DLR001', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13494, 1, 'DLR001', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13493, 1, 'CST004', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13492, 1, 'CST004', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13491, 1, 'CST004', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13490, 1, 'CST004', 'DELETE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13489, 1, 'CST004', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13488, 1, 'CST004', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13487, 1, 'CST003', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13486, 1, 'CST003', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13485, 1, 'CST003', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13484, 1, 'CST003', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13483, 1, 'CST003', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13482, 1, 'CST003', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13481, 1, 'CST002', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13480, 1, 'CST002', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13479, 1, 'CST002', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13478, 1, 'CST002', 'DELETE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13477, 1, 'CST002', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13476, 1, 'CST002', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13475, 1, 'CST001', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13474, 1, 'CST001', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13473, 1, 'CST001', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13472, 1, 'CST001', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13471, 1, 'CST001', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13470, 1, 'CST001', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13469, 1, 'ACT006', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13468, 1, 'ACT006', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13467, 1, 'ACT006', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13466, 1, 'ACT006', 'DELETE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(15900, 5, 'LUD006', 'DELETE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15899, 5, 'LUD006', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15898, 5, 'LUD006', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15897, 5, 'LUD006', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15896, 5, 'LUD005', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15895, 5, 'LUD005', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15894, 5, 'LUD005', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15893, 5, 'LUD004', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(16640, 2, 'LUD006', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16639, 2, 'LUD006', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16638, 2, 'LUD006', 'CREATE', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16637, 2, 'LUD006', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16636, 2, 'LUD004', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16635, 2, 'LUD004', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16634, 2, 'LUD004', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16633, 2, 'LUD004', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16632, 2, 'LUD004', 'CREATE', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16631, 2, 'LUD004', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16630, 2, 'LUD003', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16629, 2, 'LUD003', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16628, 2, 'LUD003', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16627, 2, 'LUD003', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16626, 2, 'LUD003', 'CREATE', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16625, 2, 'LUD003', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16624, 2, 'LUD002', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16623, 2, 'LUD002', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16622, 2, 'LUD002', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16621, 2, 'LUD002', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16620, 2, 'LUD002', 'CREATE', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16619, 2, 'LUD002', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16618, 2, 'LUD001', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16617, 2, 'LUD001', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16616, 2, 'LUD001', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16615, 2, 'LUD001', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16614, 2, 'LUD001', 'CREATE', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16613, 2, 'LUD001', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16612, 2, 'SYS006', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16611, 2, 'SYS006', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16610, 2, 'SYS006', 'CREATE', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16609, 2, 'SYS006', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16608, 2, 'SYS005', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16607, 2, 'SYS005', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16606, 2, 'SYS004', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16605, 2, 'SYS004', 'CREATE', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16604, 2, 'SYS004', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16603, 2, 'SYS003', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16602, 2, 'SYS003', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16601, 2, 'SYS003', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16600, 2, 'RPT010', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16599, 2, 'RPT010', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16598, 2, 'RPT010', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16597, 2, 'RPT010', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16596, 2, 'RPT009', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16595, 2, 'RPT009', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16594, 2, 'RPT009', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16593, 2, 'RPT009', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16592, 2, 'RPT008', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16591, 2, 'RPT008', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16590, 2, 'RPT008', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16589, 2, 'RPT008', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16588, 2, 'RPT007', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16587, 2, 'RPT007', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16586, 2, 'RPT007', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16585, 2, 'RPT007', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16584, 2, 'RPT006', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16583, 2, 'RPT006', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16582, 2, 'RPT006', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16581, 2, 'RPT006', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16580, 2, 'RPT005', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16579, 2, 'RPT005', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16578, 2, 'RPT005', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16577, 2, 'RPT005', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16576, 2, 'RPT004', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16575, 2, 'RPT004', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16574, 2, 'RPT004', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16573, 2, 'RPT004', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16572, 2, 'RPT004', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16571, 2, 'RPT003', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16570, 2, 'RPT003', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16569, 2, 'RPT003', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16568, 2, 'RPT003', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16567, 2, 'RPT002', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16566, 2, 'RPT002', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16565, 2, 'RPT002', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16564, 2, 'RPT002', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16563, 2, 'RPT001', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16562, 2, 'RPT001', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16561, 2, 'RPT001', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16560, 2, 'RPT001', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16559, 2, 'PTR002', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16558, 2, 'PTR002', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16557, 2, 'PTR002', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16556, 2, 'PTR002', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16555, 2, 'PTR002', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16554, 2, 'PTR001', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16553, 2, 'PTR001', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16552, 2, 'PTR001', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16551, 2, 'PTR001', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16550, 2, 'PTR001', 'CREATE', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16549, 2, 'PTR001', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16548, 2, 'INV002', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16547, 2, 'INV002', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16546, 2, 'INV002', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16545, 2, 'INV001', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16544, 2, 'INV001', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16543, 2, 'INV001', 'CREATE', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16542, 2, 'INV001', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16541, 2, 'LDO003', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16540, 2, 'LDO003', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16539, 2, 'LDO003', 'CREATE', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16538, 2, 'LDO003', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16537, 2, 'LDO002', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16536, 2, 'LDO002', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16535, 2, 'LDO002', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16534, 2, 'LDO002', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16533, 2, 'LDO002', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16532, 2, 'LDO001', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16531, 2, 'LDO001', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16530, 2, 'LDO001', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16529, 2, 'LDO001', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16528, 2, 'LDO001', 'CREATE', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16527, 2, 'LDO001', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16526, 2, 'DLR002', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16525, 2, 'DLR002', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16524, 2, 'DLR002', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16523, 2, 'DLR002', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16522, 2, 'DLR002', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16521, 2, 'DLR001', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16520, 2, 'DLR001', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16519, 2, 'DLR001', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16518, 2, 'DLR001', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16517, 2, 'DLR001', 'CREATE', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16516, 2, 'DLR001', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16515, 2, 'CST004', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16514, 2, 'CST004', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16513, 2, 'CST004', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16512, 2, 'CST004', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16511, 2, 'CST004', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16510, 2, 'CST003', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16509, 2, 'CST003', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16508, 2, 'CST003', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16507, 2, 'CST003', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16506, 2, 'CST003', 'CREATE', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16505, 2, 'CST003', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16504, 2, 'CST002', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16501, 2, 'CST002', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16502, 2, 'CST002', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16503, 2, 'CST002', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16246, 3, 'LUD004', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16245, 3, 'LUD004', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16244, 3, 'LUD004', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16243, 3, 'LUD004', 'DELETE', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16242, 3, 'LUD004', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16241, 3, 'LUD004', 'CREATE', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16240, 3, 'LUD004', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16239, 3, 'LUD003', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16238, 3, 'LUD003', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16237, 3, 'LUD003', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16236, 3, 'LUD003', 'DELETE', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16235, 3, 'LUD003', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16234, 3, 'LUD003', 'CREATE', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16233, 3, 'LUD003', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16232, 3, 'LUD002', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16231, 3, 'LUD002', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16230, 3, 'LUD002', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16229, 3, 'LUD002', 'DELETE', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16228, 3, 'LUD002', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16227, 3, 'LUD002', 'CREATE', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16226, 3, 'LUD002', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16225, 3, 'LUD001', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16224, 3, 'LUD001', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16223, 3, 'LUD001', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16222, 3, 'LUD001', 'DELETE', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16221, 3, 'LUD001', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16220, 3, 'LUD001', 'CREATE', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16219, 3, 'LUD001', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16218, 3, 'SYS006', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16217, 3, 'SYS006', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16216, 3, 'SYS006', 'CREATE', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16215, 3, 'SYS006', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16214, 3, 'SYS005', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16213, 3, 'SYS005', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16212, 3, 'SYS004', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16211, 3, 'SYS004', 'CREATE', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16210, 3, 'SYS004', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16209, 3, 'SYS003', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16208, 3, 'SYS003', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16207, 3, 'SYS003', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16206, 3, 'RPT010', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16205, 3, 'RPT010', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16204, 3, 'RPT010', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16203, 3, 'RPT010', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16202, 3, 'RPT009', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16201, 3, 'RPT009', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16200, 3, 'RPT009', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16199, 3, 'RPT009', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16198, 3, 'RPT008', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16197, 3, 'RPT008', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16196, 3, 'RPT008', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16195, 3, 'RPT008', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16194, 3, 'RPT007', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16193, 3, 'RPT007', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16192, 3, 'RPT007', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16191, 3, 'RPT007', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16190, 3, 'RPT006', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16189, 3, 'RPT006', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16188, 3, 'RPT006', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16187, 3, 'RPT006', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16186, 3, 'RPT005', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16185, 3, 'RPT005', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16184, 3, 'RPT005', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16183, 3, 'RPT005', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16182, 3, 'RPT004', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16181, 3, 'RPT004', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16180, 3, 'RPT004', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16179, 3, 'RPT004', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16178, 3, 'RPT004', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16177, 3, 'RPT003', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16176, 3, 'RPT003', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16175, 3, 'RPT003', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16174, 3, 'RPT003', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16173, 3, 'RPT002', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16172, 3, 'RPT002', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16171, 3, 'RPT002', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16170, 3, 'RPT002', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16169, 3, 'RPT001', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16168, 3, 'RPT001', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16167, 3, 'RPT001', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16166, 3, 'RPT001', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16165, 3, 'PTR002', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16164, 3, 'PTR002', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16163, 3, 'PTR001', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16162, 3, 'PTR001', 'CREATE', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16161, 3, 'PTR001', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16160, 3, 'LDO002', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16159, 3, 'LDO002', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16158, 3, 'LDO001', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16157, 3, 'LDO001', 'CREATE', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16156, 3, 'LDO001', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16155, 3, 'DLR002', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16154, 3, 'DLR002', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16153, 3, 'DLR002', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16152, 3, 'DLR002', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16151, 3, 'DLR002', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16150, 3, 'DLR001', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16149, 3, 'DLR001', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16148, 3, 'DLR001', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16147, 3, 'DLR001', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16146, 3, 'DLR001', 'CREATE', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16145, 3, 'DLR001', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16144, 3, 'CST004', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16143, 3, 'CST004', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16142, 3, 'CST004', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16141, 3, 'CST004', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16140, 3, 'CST004', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16139, 3, 'CST003', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16138, 3, 'CST003', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16137, 3, 'CST003', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16136, 3, 'CST003', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16135, 3, 'CST003', 'CREATE', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16134, 3, 'CST003', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16133, 3, 'CST002', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16132, 3, 'CST002', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16131, 3, 'CST002', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16130, 3, 'CST002', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16129, 3, 'CST002', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16128, 3, 'CST001', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16127, 3, 'CST001', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16126, 3, 'CST001', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16125, 3, 'CST001', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16124, 3, 'CST001', 'CREATE', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16123, 3, 'CST001', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16122, 3, 'ACT006', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16121, 3, 'ACT005', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16120, 3, 'ACT005', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16119, 3, 'ACT005', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16118, 3, 'ACT005', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16117, 3, 'ACT005', 'CREATE', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16116, 3, 'ACT005', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16115, 3, 'ACT004', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16114, 3, 'ACT004', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16113, 3, 'ACT004', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16112, 3, 'ACT004', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16111, 3, 'ACT004', 'CREATE', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16110, 3, 'ACT004', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16109, 3, 'ACT003', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16108, 3, 'ACT003', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16107, 3, 'ACT003', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16106, 3, 'ACT003', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16105, 3, 'ACT003', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16104, 3, 'ACT002', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16103, 3, 'ACT002', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16102, 3, 'ACT002', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16101, 3, 'ACT002', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16100, 3, 'ACT002', 'CREATE', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16099, 3, 'ACT002', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16098, 3, 'PRJ005', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16097, 3, 'PRJ005', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16096, 3, 'PRJ005', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16095, 3, 'PRJ005', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16094, 3, 'PRJ005', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16500, 2, 'CST002', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16499, 2, 'CST001', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16498, 2, 'CST001', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16497, 2, 'CST001', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16496, 2, 'CST001', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16495, 2, 'CST001', 'CREATE', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16494, 2, 'CST001', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16493, 2, 'ACT006', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16492, 2, 'ACT006', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16491, 2, 'ACT006', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16490, 2, 'ACT006', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16489, 2, 'ACT006', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16488, 2, 'ACT005', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16487, 2, 'ACT005', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16486, 2, 'ACT005', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16485, 2, 'ACT005', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16484, 2, 'ACT005', 'CREATE', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16481, 2, 'ACT004', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16482, 2, 'ACT004', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16483, 2, 'ACT005', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16076, 4, 'LUD005', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16075, 4, 'LUD005', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16074, 4, 'LUD005', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16073, 4, 'LUD004', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16072, 4, 'LUD004', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16071, 4, 'LUD004', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16070, 4, 'LUD004', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16069, 4, 'LUD004', 'CREATE', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16068, 4, 'LUD004', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16067, 4, 'LUD003', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16066, 4, 'LUD003', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16065, 4, 'LUD003', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16064, 4, 'LUD003', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16063, 4, 'LUD003', 'CREATE', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16062, 4, 'LUD003', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16061, 4, 'LUD002', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16060, 4, 'LUD002', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16059, 4, 'LUD002', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16058, 4, 'LUD002', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16057, 4, 'LUD002', 'CREATE', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16056, 4, 'LUD002', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16055, 4, 'LUD001', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16054, 4, 'LUD001', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16053, 4, 'LUD001', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16052, 4, 'LUD001', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16051, 4, 'LUD001', 'CREATE', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16050, 4, 'LUD001', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16049, 4, 'SYS006', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16048, 4, 'SYS006', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16047, 4, 'SYS006', 'CREATE', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16046, 4, 'SYS006', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16045, 4, 'SYS005', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16044, 4, 'SYS005', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16043, 4, 'SYS004', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16042, 4, 'SYS004', 'CREATE', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16041, 4, 'SYS004', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16040, 4, 'SYS003', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16039, 4, 'SYS003', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16038, 4, 'SYS003', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16037, 4, 'RPT011', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16036, 4, 'RPT011', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16035, 4, 'RPT011', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16034, 4, 'RPT011', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16033, 4, 'RPT010', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16032, 4, 'RPT010', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16031, 4, 'RPT010', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16030, 4, 'RPT010', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16029, 4, 'RPT009', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16028, 4, 'RPT009', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16027, 4, 'RPT009', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16026, 4, 'RPT009', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16025, 4, 'RPT008', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16024, 4, 'RPT008', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16023, 4, 'RPT008', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16022, 4, 'RPT008', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16021, 4, 'RPT007', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16020, 4, 'RPT007', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16019, 4, 'RPT007', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16018, 4, 'RPT007', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16017, 4, 'RPT006', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16016, 4, 'RPT006', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16015, 4, 'RPT006', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16014, 4, 'RPT006', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16013, 4, 'RPT005', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16012, 4, 'RPT005', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16011, 4, 'RPT005', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16010, 4, 'RPT005', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16009, 4, 'RPT004', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16008, 4, 'RPT004', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16007, 4, 'RPT004', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16006, 4, 'RPT004', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16005, 4, 'RPT004', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16004, 4, 'RPT003', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16003, 4, 'RPT003', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16002, 4, 'RPT003', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16001, 4, 'RPT003', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16000, 4, 'RPT002', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15999, 4, 'RPT002', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15998, 4, 'RPT002', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15997, 4, 'RPT002', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15996, 4, 'RPT001', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15995, 4, 'RPT001', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15994, 4, 'RPT001', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15993, 4, 'RPT001', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15992, 4, 'PTR002', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15991, 4, 'PTR002', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15990, 4, 'PTR002', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15989, 4, 'PTR002', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15988, 4, 'LDO002', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15987, 4, 'LDO002', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15986, 4, 'LDO002', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15985, 4, 'LDO002', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15984, 4, 'DLR002', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15983, 4, 'DLR002', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15982, 4, 'DLR002', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15981, 4, 'DLR002', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15980, 4, 'DLR002', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15979, 4, 'DLR001', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15978, 4, 'DLR001', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15977, 4, 'DLR001', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15976, 4, 'DLR001', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15975, 4, 'DLR001', 'CREATE', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15974, 4, 'DLR001', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15973, 4, 'CST004', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15972, 4, 'CST004', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0);
INSERT INTO `users_permissions` (`id`, `user_id`, `module_id`, `operation`, `created`, `created_by`, `updated`, `updated_by`) VALUES
(15971, 4, 'CST004', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15970, 4, 'CST004', 'DELETE', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15969, 4, 'CST004', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15968, 4, 'CST004', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15967, 4, 'CST003', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15966, 4, 'CST003', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15965, 4, 'CST003', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15964, 4, 'CST003', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15963, 4, 'CST003', 'CREATE', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15962, 4, 'CST003', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15961, 4, 'CST002', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15960, 4, 'CST002', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15959, 4, 'CST002', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15958, 4, 'CST002', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15957, 4, 'CST002', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15956, 4, 'CST001', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15955, 4, 'CST001', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15954, 4, 'CST001', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15953, 4, 'CST001', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15952, 4, 'CST001', 'CREATE', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15951, 4, 'CST001', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15950, 4, 'ACT006', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15949, 4, 'ACT006', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15948, 4, 'ACT006', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15947, 4, 'ACT006', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15946, 4, 'ACT006', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15945, 4, 'ACT005', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15944, 4, 'ACT005', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15943, 4, 'ACT005', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15942, 4, 'ACT005', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15941, 4, 'ACT005', 'CREATE', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15940, 4, 'ACT005', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15939, 4, 'ACT004', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15938, 4, 'ACT004', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15937, 4, 'ACT004', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15936, 4, 'ACT004', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15935, 4, 'ACT004', 'CREATE', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15934, 4, 'ACT004', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15933, 4, 'ACT003', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15932, 4, 'ACT003', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15931, 4, 'ACT003', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15930, 4, 'ACT003', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15929, 4, 'ACT003', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15928, 4, 'ACT002', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15927, 4, 'ACT002', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15926, 4, 'ACT002', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15925, 4, 'ACT002', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15924, 4, 'ACT002', 'CREATE', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15923, 4, 'ACT002', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15922, 4, 'PRJ005', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15921, 4, 'PRJ005', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15920, 4, 'PRJ005', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15919, 4, 'PRJ005', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15918, 4, 'PRJ005', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15917, 4, 'PRJ004', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15892, 5, 'LUD004', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15891, 5, 'LUD004', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15890, 5, 'LUD004', 'DELETE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15889, 5, 'LUD004', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15888, 5, 'LUD004', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15887, 5, 'LUD004', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15886, 5, 'LUD003', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15885, 5, 'LUD003', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15884, 5, 'LUD003', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15883, 5, 'LUD003', 'DELETE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15882, 5, 'LUD003', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15881, 5, 'LUD003', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15880, 5, 'LUD003', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15879, 5, 'LUD002', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15878, 5, 'LUD002', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15877, 5, 'LUD002', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15876, 5, 'LUD002', 'DELETE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15875, 5, 'LUD002', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15874, 5, 'LUD002', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15873, 5, 'LUD002', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15872, 5, 'LUD001', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15871, 5, 'LUD001', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15870, 5, 'LUD001', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15869, 5, 'LUD001', 'DELETE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15868, 5, 'LUD001', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15867, 5, 'LUD001', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15866, 5, 'LUD001', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15865, 5, 'SYS008', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15864, 5, 'SYS008', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15863, 5, 'SYS007', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15862, 5, 'SYS007', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15861, 5, 'SYS007', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15860, 5, 'SYS006', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15859, 5, 'SYS006', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15858, 5, 'SYS006', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15857, 5, 'SYS006', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15856, 5, 'SYS005', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15855, 5, 'SYS005', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15854, 5, 'SYS004', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15853, 5, 'SYS004', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15852, 5, 'SYS004', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15851, 5, 'SYS003', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15850, 5, 'SYS003', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15849, 5, 'SYS003', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15848, 5, 'SYS002', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15847, 5, 'SYS002', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15846, 5, 'SYS001', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15845, 5, 'SYS001', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15844, 5, 'SYS001', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15843, 5, 'SYS001', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15842, 5, 'SYS001', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15841, 5, 'SYS001', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15840, 5, 'RPT011', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15839, 5, 'RPT011', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15838, 5, 'RPT011', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15837, 5, 'RPT011', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15836, 5, 'RPT010', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15835, 5, 'RPT010', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15834, 5, 'RPT010', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15833, 5, 'RPT010', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15832, 5, 'RPT009', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15831, 5, 'RPT009', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15830, 5, 'RPT009', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15829, 5, 'RPT009', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15828, 5, 'RPT008', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15827, 5, 'RPT008', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15826, 5, 'RPT008', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15825, 5, 'RPT008', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15824, 5, 'RPT007', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15823, 5, 'RPT007', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15822, 5, 'RPT007', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15821, 5, 'RPT007', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15820, 5, 'RPT006', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15819, 5, 'RPT006', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15818, 5, 'RPT006', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15817, 5, 'RPT006', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15816, 5, 'RPT005', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15815, 5, 'RPT005', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15814, 5, 'RPT005', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15813, 5, 'RPT005', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15812, 5, 'RPT004', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15811, 5, 'RPT004', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15810, 5, 'RPT004', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15809, 5, 'RPT004', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15808, 5, 'RPT004', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15807, 5, 'RPT003', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15806, 5, 'RPT003', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15805, 5, 'RPT003', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15804, 5, 'RPT003', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15803, 5, 'RPT002', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15802, 5, 'RPT002', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15801, 5, 'RPT002', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15800, 5, 'RPT002', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15799, 5, 'RPT001', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15798, 5, 'RPT001', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15797, 5, 'RPT001', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15796, 5, 'RPT001', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15795, 5, 'PTR002', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15794, 5, 'PTR002', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15793, 5, 'PTR002', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15792, 5, 'PTR002', 'DELETE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15791, 5, 'PTR002', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15790, 5, 'PTR002', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15789, 5, 'PTR001', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15788, 5, 'PTR001', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15787, 5, 'PTR001', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15786, 5, 'PTR001', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15785, 5, 'PTR001', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15784, 5, 'PTR001', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15783, 5, 'INV002', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15782, 5, 'INV002', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15781, 5, 'INV002', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15780, 5, 'INV002', 'DELETE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15779, 5, 'INV002', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15778, 5, 'INV002', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15777, 5, 'INV001', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15776, 5, 'INV001', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15775, 5, 'INV001', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15774, 5, 'INV001', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15773, 5, 'INV001', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15772, 5, 'INV001', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15771, 5, 'LDO003', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15770, 5, 'LDO003', 'DELETE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15769, 5, 'LDO003', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15768, 5, 'LDO003', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15767, 5, 'LDO003', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15766, 5, 'LDO002', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15765, 5, 'LDO002', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15764, 5, 'LDO002', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15763, 5, 'LDO002', 'DELETE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15762, 5, 'LDO002', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15761, 5, 'LDO002', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15760, 5, 'LDO001', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15759, 5, 'LDO001', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15758, 5, 'LDO001', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15757, 5, 'LDO001', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15756, 5, 'LDO001', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15755, 5, 'LDO001', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15754, 5, 'DLR002', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15753, 5, 'DLR002', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15752, 5, 'DLR002', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15751, 5, 'DLR002', 'DELETE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15750, 5, 'DLR002', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15749, 5, 'DLR002', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15748, 5, 'DLR001', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15747, 5, 'DLR001', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15746, 5, 'DLR001', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15745, 5, 'DLR001', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15744, 5, 'DLR001', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15743, 5, 'DLR001', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15742, 5, 'CST004', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15741, 5, 'CST004', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15740, 5, 'CST004', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15739, 5, 'CST004', 'DELETE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15738, 5, 'CST004', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15737, 5, 'CST004', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15736, 5, 'CST003', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15735, 5, 'CST003', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15734, 5, 'CST003', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15733, 5, 'CST003', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15732, 5, 'CST003', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15731, 5, 'CST003', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15730, 5, 'CST002', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15729, 5, 'CST002', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15728, 5, 'CST002', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15727, 5, 'CST002', 'DELETE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15726, 5, 'CST002', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15725, 5, 'CST002', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15724, 5, 'CST001', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15723, 5, 'CST001', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15722, 5, 'CST001', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15721, 5, 'CST001', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15720, 5, 'CST001', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15719, 5, 'CST001', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15718, 5, 'ACT006', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15717, 5, 'ACT006', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15716, 5, 'ACT006', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15715, 5, 'ACT006', 'DELETE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15714, 5, 'ACT006', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15713, 5, 'ACT006', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15712, 5, 'ACT005', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15711, 5, 'ACT005', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15710, 5, 'ACT005', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15709, 5, 'ACT005', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15708, 5, 'ACT005', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15707, 5, 'ACT005', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15706, 5, 'ACT004', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15705, 5, 'ACT004', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15704, 5, 'ACT004', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15703, 5, 'ACT004', 'DELETE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15702, 5, 'ACT004', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15701, 5, 'ACT004', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15700, 5, 'ACT004', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15699, 5, 'ACT003', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15698, 5, 'ACT003', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15697, 5, 'ACT003', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15696, 5, 'ACT003', 'DELETE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15695, 5, 'ACT003', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15694, 5, 'ACT003', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15693, 5, 'ACT002', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15692, 5, 'ACT002', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15691, 5, 'ACT002', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15690, 5, 'ACT002', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15689, 5, 'ACT002', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15688, 5, 'ACT002', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15687, 5, 'PRJ005', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15686, 5, 'PRJ005', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15685, 5, 'PRJ005', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15684, 5, 'PRJ005', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15683, 5, 'PRJ005', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15682, 5, 'PRJ004', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15681, 5, 'PRJ004', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15680, 5, 'PRJ004', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(13465, 1, 'ACT006', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13464, 1, 'ACT006', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13463, 1, 'ACT005', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13462, 1, 'ACT005', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13461, 1, 'ACT005', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13460, 1, 'ACT005', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13459, 1, 'ACT005', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13458, 1, 'ACT005', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13457, 1, 'ACT004', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13456, 1, 'ACT004', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13455, 1, 'ACT004', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13454, 1, 'ACT004', 'DELETE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13453, 1, 'ACT004', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13452, 1, 'ACT004', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13451, 1, 'ACT004', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13450, 1, 'ACT003', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13449, 1, 'ACT003', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13448, 1, 'ACT003', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13447, 1, 'ACT003', 'DELETE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13446, 1, 'ACT003', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13445, 1, 'ACT003', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13444, 1, 'ACT002', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13443, 1, 'ACT002', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13442, 1, 'ACT002', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13441, 1, 'ACT002', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13440, 1, 'ACT002', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13439, 1, 'ACT002', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13438, 1, 'PRJ005', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13437, 1, 'PRJ005', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13436, 1, 'PRJ005', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13435, 1, 'PRJ005', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13434, 1, 'PRJ005', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13433, 1, 'PRJ004', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13432, 1, 'PRJ004', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13431, 1, 'PRJ004', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13430, 1, 'PRJ004', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13429, 1, 'PRJ004', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(16093, 3, 'PRJ004', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16092, 3, 'PRJ004', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16091, 3, 'PRJ004', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16090, 3, 'PRJ004', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16089, 3, 'PRJ004', 'CREATE', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16088, 3, 'PRJ004', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16087, 3, 'PRJ003', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16086, 3, 'PRJ003', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16085, 3, 'PRJ003', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16084, 3, 'PRJ003', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16083, 3, 'PRJ003', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16082, 3, 'PRJ001', 'REPRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16081, 3, 'PRJ001', 'PRINT', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16080, 3, 'PRJ001', 'VIEW', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16078, 3, 'PRJ001', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16480, 2, 'ACT004', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16479, 2, 'ACT004', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16478, 2, 'ACT004', 'CREATE', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16477, 2, 'ACT004', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16476, 2, 'ACT003', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16475, 2, 'ACT003', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16474, 2, 'ACT003', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16473, 2, 'ACT003', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16472, 2, 'ACT003', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16471, 2, 'ACT002', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16470, 2, 'ACT002', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16469, 2, 'ACT002', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16468, 2, 'ACT002', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16467, 2, 'ACT002', 'CREATE', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16466, 2, 'ACT002', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16465, 2, 'PRJ005', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16464, 2, 'PRJ005', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16463, 2, 'PRJ005', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16462, 2, 'PRJ005', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16461, 2, 'PRJ005', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16460, 2, 'PRJ004', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16459, 2, 'PRJ004', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16458, 2, 'PRJ004', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16457, 2, 'PRJ004', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16456, 2, 'PRJ004', 'CREATE', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16455, 2, 'PRJ004', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16454, 2, 'PRJ003', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16453, 2, 'PRJ003', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16452, 2, 'PRJ003', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16451, 2, 'PRJ003', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(14101, 6, 'LUD004', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14100, 6, 'LUD004', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14099, 6, 'LUD004', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14098, 6, 'LUD004', 'DELETE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14097, 6, 'LUD004', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14096, 6, 'LUD004', 'CREATE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14095, 6, 'LUD004', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14094, 6, 'LUD003', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14093, 6, 'LUD003', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14092, 6, 'LUD003', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14091, 6, 'LUD003', 'DELETE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14090, 6, 'LUD003', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14089, 6, 'LUD003', 'CREATE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14088, 6, 'LUD003', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14087, 6, 'LUD002', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14086, 6, 'LUD002', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14085, 6, 'LUD002', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14084, 6, 'LUD002', 'DELETE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14083, 6, 'LUD002', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14082, 6, 'LUD002', 'CREATE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14081, 6, 'LUD002', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14080, 6, 'LUD001', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14079, 6, 'LUD001', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14078, 6, 'LUD001', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14077, 6, 'LUD001', 'DELETE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14076, 6, 'LUD001', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14075, 6, 'LUD001', 'CREATE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14074, 6, 'LUD001', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14073, 6, 'SYS006', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14072, 6, 'SYS006', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14071, 6, 'SYS006', 'CREATE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14070, 6, 'SYS006', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14069, 6, 'SYS005', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14068, 6, 'SYS005', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14067, 6, 'SYS004', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14066, 6, 'SYS004', 'CREATE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14065, 6, 'SYS004', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14064, 6, 'SYS003', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14063, 6, 'SYS003', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14062, 6, 'SYS003', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14061, 6, 'SYS002', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14060, 6, 'SYS002', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14059, 6, 'SYS001', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14058, 6, 'SYS001', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14057, 6, 'SYS001', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14056, 6, 'SYS001', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14055, 6, 'SYS001', 'CREATE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14054, 6, 'SYS001', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14053, 6, 'RPT010', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14052, 6, 'RPT010', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14051, 6, 'RPT010', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14050, 6, 'RPT010', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14049, 6, 'RPT009', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14048, 6, 'RPT009', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14047, 6, 'RPT009', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14046, 6, 'RPT009', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14045, 6, 'RPT008', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14044, 6, 'RPT008', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14043, 6, 'RPT008', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14042, 6, 'RPT008', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14041, 6, 'RPT007', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14040, 6, 'RPT007', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14039, 6, 'RPT007', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14038, 6, 'RPT007', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14037, 6, 'RPT006', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14036, 6, 'RPT006', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14035, 6, 'RPT006', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14034, 6, 'RPT006', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14033, 6, 'RPT005', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14032, 6, 'RPT005', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14031, 6, 'RPT005', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14030, 6, 'RPT005', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14029, 6, 'RPT004', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14028, 6, 'RPT004', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14027, 6, 'RPT004', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14026, 6, 'RPT004', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14025, 6, 'RPT004', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14024, 6, 'RPT003', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14023, 6, 'RPT003', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14022, 6, 'RPT003', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14021, 6, 'RPT003', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14020, 6, 'RPT002', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14019, 6, 'RPT002', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14018, 6, 'RPT002', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14017, 6, 'RPT002', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14016, 6, 'RPT001', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14015, 6, 'RPT001', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14014, 6, 'RPT001', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14013, 6, 'RPT001', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14012, 6, 'PTR002', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14011, 6, 'PTR002', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14010, 6, 'PTR001', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14009, 6, 'PTR001', 'CREATE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14008, 6, 'PTR001', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14007, 6, 'INV002', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14006, 6, 'INV002', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14005, 6, 'INV002', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14004, 6, 'INV002', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14003, 6, 'INV002', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14002, 6, 'INV001', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14001, 6, 'INV001', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(14000, 6, 'INV001', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13999, 6, 'INV001', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13998, 6, 'INV001', 'CREATE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13997, 6, 'INV001', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13996, 6, 'LDO002', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13995, 6, 'LDO002', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13994, 6, 'LDO002', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13993, 6, 'LDO002', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13992, 6, 'LDO002', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13991, 6, 'LDO001', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13990, 6, 'LDO001', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13989, 6, 'LDO001', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13988, 6, 'LDO001', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13987, 6, 'LDO001', 'CREATE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13986, 6, 'LDO001', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13985, 6, 'DLR002', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13984, 6, 'DLR002', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13983, 6, 'DLR002', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13982, 6, 'DLR002', 'DELETE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13981, 6, 'DLR002', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13980, 6, 'DLR002', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13979, 6, 'DLR001', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13978, 6, 'DLR001', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13977, 6, 'DLR001', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13976, 6, 'DLR001', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13975, 6, 'DLR001', 'CREATE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13974, 6, 'DLR001', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13973, 6, 'CST004', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13972, 6, 'CST004', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13971, 6, 'CST004', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13970, 6, 'CST004', 'DELETE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13969, 6, 'CST004', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13968, 6, 'CST004', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13967, 6, 'CST003', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13966, 6, 'CST003', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13965, 6, 'CST003', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13964, 6, 'CST003', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13963, 6, 'CST003', 'CREATE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13962, 6, 'CST003', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13961, 6, 'CST002', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13960, 6, 'CST002', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13959, 6, 'CST002', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13958, 6, 'CST002', 'DELETE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13957, 6, 'CST002', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13956, 6, 'CST002', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13955, 6, 'CST001', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13954, 6, 'CST001', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13953, 6, 'CST001', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13952, 6, 'CST001', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13951, 6, 'CST001', 'CREATE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13950, 6, 'CST001', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13949, 6, 'ACT006', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13948, 6, 'ACT006', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13947, 6, 'ACT006', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13946, 6, 'ACT006', 'DELETE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13945, 6, 'ACT006', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13944, 6, 'ACT006', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13943, 6, 'ACT005', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13942, 6, 'ACT005', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13941, 6, 'ACT005', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13940, 6, 'ACT005', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13939, 6, 'ACT005', 'CREATE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13938, 6, 'ACT005', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13937, 6, 'ACT004', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13936, 6, 'ACT004', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13935, 6, 'ACT004', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13934, 6, 'ACT004', 'DELETE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13933, 6, 'ACT004', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13932, 6, 'ACT004', 'CREATE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13931, 6, 'ACT004', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13930, 6, 'ACT003', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13929, 6, 'ACT003', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13928, 6, 'ACT003', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13927, 6, 'ACT003', 'DELETE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13926, 6, 'ACT003', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13925, 6, 'ACT003', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13924, 6, 'ACT002', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13923, 6, 'ACT002', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13922, 6, 'ACT002', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13921, 6, 'ACT002', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13920, 6, 'ACT002', 'CREATE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13919, 6, 'ACT002', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13918, 6, 'PRJ005', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13917, 6, 'PRJ005', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13916, 6, 'PRJ005', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13915, 6, 'PRJ005', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13914, 6, 'PRJ005', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13913, 6, 'PRJ004', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13912, 6, 'PRJ004', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13911, 6, 'PRJ004', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13910, 6, 'PRJ004', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13909, 6, 'PRJ004', 'CREATE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13908, 6, 'PRJ004', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13907, 6, 'PRJ003', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13906, 6, 'PRJ003', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13905, 6, 'PRJ003', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13904, 6, 'PRJ003', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13903, 6, 'PRJ003', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13902, 6, 'PRJ002', 'CREATE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13901, 6, 'PRJ002', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13900, 6, 'PRJ001', 'REPRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13899, 6, 'PRJ001', 'PRINT', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13898, 6, 'PRJ001', 'VIEW', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13897, 6, 'PRJ001', 'MODIFY', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13896, 6, 'PRJ001', 'CREATE', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13895, 6, 'PRJ001', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(13894, 6, 'DASHBOARD', 'ACCESS', '2020-08-30 14:00:44', 1, '0000-00-00 00:00:00', 0),
(16450, 2, 'PRJ003', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16079, 3, 'PRJ001', 'MODIFY', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(15679, 5, 'PRJ004', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15678, 5, 'PRJ004', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15677, 5, 'PRJ004', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15676, 5, 'PRJ003', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15675, 5, 'PRJ003', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15674, 5, 'PRJ003', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15673, 5, 'PRJ003', 'DELETE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(13428, 1, 'PRJ004', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13427, 1, 'PRJ003', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13426, 1, 'PRJ003', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13425, 1, 'PRJ003', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13424, 1, 'PRJ003', 'DELETE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13423, 1, 'PRJ003', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13422, 1, 'PRJ003', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(15916, 4, 'PRJ004', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15915, 4, 'PRJ004', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15914, 4, 'PRJ004', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15913, 4, 'PRJ004', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15912, 4, 'PRJ003', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15911, 4, 'PRJ003', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15672, 5, 'PRJ003', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15671, 5, 'PRJ003', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15670, 5, 'PRJ002', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15669, 5, 'PRJ002', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15668, 5, 'PRJ001', 'REPRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(13421, 1, 'PRJ002', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13420, 1, 'PRJ002', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13419, 1, 'PRJ001', 'REPRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13418, 1, 'PRJ001', 'PRINT', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13417, 1, 'PRJ001', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(15667, 5, 'PRJ001', 'PRINT', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15666, 5, 'PRJ001', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15665, 5, 'PRJ001', 'DELETE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(13416, 1, 'PRJ001', 'DELETE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13415, 1, 'PRJ001', 'MODIFY', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13414, 1, 'PRJ001', 'CREATE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(15664, 5, 'PRJ001', 'MODIFY', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(13413, 1, 'PRJ001', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13412, 1, 'DASHBOARD', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13411, 1, 'DASHBOARD', 'ACCESS', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(15663, 5, 'PRJ001', 'CREATE', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15662, 5, 'PRJ001', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15661, 5, 'DASHBOARD', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(15910, 4, 'PRJ003', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(16077, 3, 'DASHBOARD', 'ACCESS', '2021-04-17 08:45:04', 1, '0000-00-00 00:00:00', 0),
(16449, 2, 'PRJ001', 'REPRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(13651, 1, 'LUD006', 'DELETE', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(13652, 1, 'LUD006', 'VIEW', '2020-04-09 18:37:54', 5, '0000-00-00 00:00:00', 0),
(15660, 5, 'DASHBOARD', 'ACCESS', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0),
(16448, 2, 'PRJ001', 'PRINT', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0);
INSERT INTO `users_permissions` (`id`, `user_id`, `module_id`, `operation`, `created`, `created_by`, `updated`, `updated_by`) VALUES
(16447, 2, 'PRJ001', 'VIEW', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16446, 2, 'PRJ001', 'MODIFY', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16445, 2, 'PRJ001', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(16444, 2, 'DASHBOARD', 'ACCESS', '2021-10-22 12:58:13', 1, '0000-00-00 00:00:00', 0),
(14873, 7, 'SYS007', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14872, 7, 'SYS007', 'CREATE', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14871, 7, 'SYS007', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14870, 7, 'SYS006', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14869, 7, 'SYS006', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14868, 7, 'SYS006', 'CREATE', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14867, 7, 'SYS006', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14866, 7, 'RPT010', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14865, 7, 'RPT010', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14864, 7, 'RPT010', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14863, 7, 'RPT010', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14862, 7, 'RPT009', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14861, 7, 'RPT009', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14860, 7, 'RPT009', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14859, 7, 'RPT009', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14858, 7, 'RPT007', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14857, 7, 'RPT007', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14856, 7, 'RPT007', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14855, 7, 'RPT007', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14854, 7, 'RPT006', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14853, 7, 'RPT006', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14852, 7, 'RPT006', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14851, 7, 'RPT006', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14850, 7, 'RPT005', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14849, 7, 'RPT005', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14848, 7, 'RPT005', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14847, 7, 'RPT005', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14846, 7, 'RPT004', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14845, 7, 'RPT004', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14844, 7, 'RPT004', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14843, 7, 'RPT004', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14842, 7, 'RPT003', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14841, 7, 'RPT003', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14840, 7, 'RPT003', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14839, 7, 'RPT003', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14838, 7, 'RPT002', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14837, 7, 'RPT002', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14836, 7, 'RPT002', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14835, 7, 'RPT002', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14834, 7, 'RPT001', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14833, 7, 'RPT001', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14832, 7, 'RPT001', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14831, 7, 'RPT001', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14830, 7, 'PTR002', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14829, 7, 'PTR002', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14828, 7, 'PTR001', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14827, 7, 'PTR001', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14826, 7, 'INV002', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14825, 7, 'INV002', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14824, 7, 'INV001', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14823, 7, 'INV001', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14822, 7, 'LDO002', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14821, 7, 'LDO002', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14820, 7, 'LDO001', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14819, 7, 'LDO001', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14818, 7, 'DLR002', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14817, 7, 'DLR002', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14816, 7, 'DLR002', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14815, 7, 'DLR002', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14814, 7, 'DLR002', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14813, 7, 'DLR001', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14812, 7, 'DLR001', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14811, 7, 'DLR001', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14810, 7, 'DLR001', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14809, 7, 'DLR001', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14808, 7, 'CST004', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14807, 7, 'CST004', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14806, 7, 'CST004', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14805, 7, 'CST004', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14804, 7, 'CST004', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14803, 7, 'CST003', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14802, 7, 'CST003', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14801, 7, 'CST003', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14800, 7, 'CST003', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14799, 7, 'CST003', 'CREATE', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14798, 7, 'CST003', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14797, 7, 'CST002', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14796, 7, 'CST002', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14795, 7, 'CST002', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14794, 7, 'CST002', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14793, 7, 'CST002', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14792, 7, 'CST001', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14791, 7, 'CST001', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14790, 7, 'CST001', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14789, 7, 'CST001', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14788, 7, 'CST001', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14787, 7, 'ACT006', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14786, 7, 'ACT006', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14785, 7, 'ACT006', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14784, 7, 'ACT006', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14783, 7, 'ACT006', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14782, 7, 'ACT005', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14781, 7, 'ACT005', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14780, 7, 'ACT005', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14779, 7, 'ACT005', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14778, 7, 'ACT005', 'CREATE', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14777, 7, 'ACT005', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14776, 7, 'ACT004', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14775, 7, 'ACT004', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14774, 7, 'ACT004', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14773, 7, 'ACT004', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14772, 7, 'ACT004', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14771, 7, 'ACT003', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14770, 7, 'ACT003', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14769, 7, 'ACT003', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14768, 7, 'ACT003', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14767, 7, 'ACT003', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14766, 7, 'ACT002', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14765, 7, 'ACT002', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14764, 7, 'ACT002', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14763, 7, 'ACT002', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14762, 7, 'ACT002', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14761, 7, 'PRJ005', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14760, 7, 'PRJ005', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14759, 7, 'PRJ005', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14758, 7, 'PRJ005', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14757, 7, 'PRJ005', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14756, 7, 'PRJ004', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14755, 7, 'PRJ004', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14754, 7, 'PRJ004', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14753, 7, 'PRJ004', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14752, 7, 'PRJ004', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14751, 7, 'PRJ003', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14750, 7, 'PRJ003', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14749, 7, 'PRJ003', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14748, 7, 'PRJ003', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14747, 7, 'PRJ003', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14746, 7, 'PRJ001', 'REPRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14745, 7, 'PRJ001', 'PRINT', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14744, 7, 'PRJ001', 'VIEW', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14743, 7, 'PRJ001', 'MODIFY', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14742, 7, 'PRJ001', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(14741, 7, 'DASHBOARD', 'ACCESS', '2020-08-31 22:42:25', 1, '0000-00-00 00:00:00', 0),
(15909, 4, 'PRJ003', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15908, 4, 'PRJ003', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15907, 4, 'PRJ001', 'REPRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15906, 4, 'PRJ001', 'PRINT', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15905, 4, 'PRJ001', 'VIEW', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15904, 4, 'PRJ001', 'MODIFY', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15903, 4, 'PRJ001', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15902, 4, 'DASHBOARD', 'ACCESS', '2021-04-17 08:44:26', 1, '0000-00-00 00:00:00', 0),
(15901, 5, 'LUD006', 'VIEW', '2020-09-27 19:48:36', 5, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_quick_links`
--

CREATE TABLE `users_quick_links` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `is_public` enum('Y','N') NOT NULL DEFAULT 'N',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users_sessions`
--

CREATE TABLE `users_sessions` (
  `id` int(11) UNSIGNED NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_time` varchar(30) NOT NULL,
  `session_time` varchar(30) NOT NULL,
  `status` enum('ACTIVE','EXPIRED') NOT NULL DEFAULT 'ACTIVE',
  `ip` varchar(30) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_sessions`
--

INSERT INTO `users_sessions` (`id`, `session_id`, `user_id`, `login_time`, `session_time`, `status`, `ip`, `created`, `updated`) VALUES
(1, '0799bc62eff3174abb8eef7834b2243b', 5, '1639933749', '1639935023', 'ACTIVE', '182.185.207.149', '2021-12-19 17:09:09', '2021-12-19 17:30:23');

-- --------------------------------------------------------

--
-- Table structure for table `users_tasks`
--

CREATE TABLE `users_tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `status` enum('DUE','COMPLETE') NOT NULL DEFAULT 'DUE',
  `due_on` date NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers_nominees`
--
ALTER TABLE `customers_nominees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dealers`
--
ALTER TABLE `dealers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposit_accounts`
--
ALTER TABLE `deposit_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `investor`
--
ALTER TABLE `investor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `landowner`
--
ALTER TABLE `landowner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `landowner_projects`
--
ALTER TABLE `landowner_projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `landowner_projects_dues`
--
ALTER TABLE `landowner_projects_dues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lookup_plot_features`
--
ALTER TABLE `lookup_plot_features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `partner`
--
ALTER TABLE `partner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plots`
--
ALTER TABLE `plots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plots_dues`
--
ALTER TABLE `plots_dues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plots_features`
--
ALTER TABLE `plots_features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_log`
--
ALTER TABLE `system_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `voucher_id` (`voucher_id`);

--
-- Indexes for table `transactions_details`
--
ALTER TABLE `transactions_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_name` (`user_name`);

--
-- Indexes for table `users_access_control`
--
ALTER TABLE `users_access_control`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_messages`
--
ALTER TABLE `users_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_permissions`
--
ALTER TABLE `users_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_quick_links`
--
ALTER TABLE `users_quick_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_sessions`
--
ALTER TABLE `users_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_id` (`session_id`);

--
-- Indexes for table `users_tasks`
--
ALTER TABLE `users_tasks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers_nominees`
--
ALTER TABLE `customers_nominees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dealers`
--
ALTER TABLE `dealers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposit_accounts`
--
ALTER TABLE `deposit_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investor`
--
ALTER TABLE `investor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `landowner`
--
ALTER TABLE `landowner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `landowner_projects`
--
ALTER TABLE `landowner_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `landowner_projects_dues`
--
ALTER TABLE `landowner_projects_dues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lookup_plot_features`
--
ALTER TABLE `lookup_plot_features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `partner`
--
ALTER TABLE `partner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plots`
--
ALTER TABLE `plots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plots_dues`
--
ALTER TABLE `plots_dues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plots_features`
--
ALTER TABLE `plots_features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `system_log`
--
ALTER TABLE `system_log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions_details`
--
ALTER TABLE `transactions_details`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users_access_control`
--
ALTER TABLE `users_access_control`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `users_messages`
--
ALTER TABLE `users_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_permissions`
--
ALTER TABLE `users_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16641;

--
-- AUTO_INCREMENT for table `users_quick_links`
--
ALTER TABLE `users_quick_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_sessions`
--
ALTER TABLE `users_sessions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_tasks`
--
ALTER TABLE `users_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
