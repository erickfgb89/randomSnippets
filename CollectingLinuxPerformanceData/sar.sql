-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 10, 2012 at 03:38 PM
-- Server version: 5.5.19
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sar`
--

-- --------------------------------------------------------

--
-- Table structure for table `CPUUtilisation`
--

CREATE TABLE IF NOT EXISTS `CPUUtilisation` (
  `hostname` text COLLATE latin1_general_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `CPU` text COLLATE latin1_general_ci NOT NULL,
  `prcUser` float NOT NULL,
  `prcNice` float NOT NULL,
  `prcSystem` float NOT NULL,
  `prcIOWait` float NOT NULL,
  `prcSteal` float NOT NULL,
  `prcIRQ` float NOT NULL,
  `prcSoft` float NOT NULL,
  `prcGuest` float NOT NULL,
  `prcIdle` float NOT NULL,
  KEY `date` (`datetime`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hugepageUtilisation`
--

CREATE TABLE IF NOT EXISTS `hugepageUtilisation` (
  `hostname` text COLLATE latin1_general_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `kbHugFree` bigint(20) NOT NULL,
  `kbHugUsed` bigint(20) NOT NULL,
  `prcHugUsed` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inodeFileKernalTableStatistics`
--

CREATE TABLE IF NOT EXISTS `inodeFileKernalTableStatistics` (
  `hostname` text COLLATE latin1_general_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `dentUnused` float NOT NULL,
  `fileNR` float NOT NULL,
  `inodeNR` float NOT NULL,
  `ptyNR` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `IOandTransferRateStatistics`
--

CREATE TABLE IF NOT EXISTS `IOandTransferRateStatistics` (
  `hostname` text COLLATE latin1_general_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `tps` float NOT NULL,
  `rtps` float NOT NULL,
  `wtps` float NOT NULL,
  `bytesReadPerSec` float NOT NULL,
  `bytesWrittenPerSec` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `memoryStatistics`
--

CREATE TABLE IF NOT EXISTS `memoryStatistics` (
  `hostname` text COLLATE latin1_general_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `frmPagesPerSec` float NOT NULL,
  `bufPagesPerSec` float NOT NULL,
  `camPagesPerSec` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `memoryUtilisation`
--

CREATE TABLE IF NOT EXISTS `memoryUtilisation` (
  `hostname` text COLLATE latin1_general_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `kbMemFree` bigint(20) NOT NULL,
  `kbMemUsed` bigint(20) NOT NULL,
  `prcMemUsed` float NOT NULL,
  `kbBuffers` bigint(20) NOT NULL,
  `kbCached` bigint(20) NOT NULL,
  `kbCommit` bigint(20) NOT NULL,
  `prcCommit` float NOT NULL,
  `kbActive` bigint(20) NOT NULL,
  `kbInactive` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `networkDeviceErrors`
--

CREATE TABLE IF NOT EXISTS `networkDeviceErrors` (
  `hostname` text COLLATE latin1_general_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `IFACE` text COLLATE latin1_general_ci NOT NULL,
  `rxErrorsPerSec` float NOT NULL,
  `txErrorsPerSec` float NOT NULL,
  `collsPerSec` float NOT NULL,
  `rxDropsPerSec` float NOT NULL,
  `txDropsPerSec` float NOT NULL,
  `txCarrPerSec` float NOT NULL,
  `rxFramesPerSec` float NOT NULL,
  `rxFifoPerSec` float NOT NULL,
  `txFifoPerSec` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `networkInterfaceStatistics`
--

CREATE TABLE IF NOT EXISTS `networkInterfaceStatistics` (
  `hostname` text COLLATE latin1_general_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `IFACE` text COLLATE latin1_general_ci NOT NULL,
  `rxpckPerSec` float NOT NULL,
  `txpckPerSec` float NOT NULL,
  `rxkBytesPerSec` float NOT NULL,
  `txkBytesPerSec` float NOT NULL,
  `rxcmpPerSec` float NOT NULL,
  `txcmpPerSec` float NOT NULL,
  `rxmsctPerSec` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `NFSClientActivity`
--

CREATE TABLE IF NOT EXISTS `NFSClientActivity` (
  `hostname` text COLLATE latin1_general_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `callPerSec` float NOT NULL,
  `retransPerSec` float NOT NULL,
  `readsPerSec` float NOT NULL,
  `writesPerSec` float NOT NULL,
  `accessPerSec` float NOT NULL,
  `getattPerSec` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `NFSServerActivity`
--

CREATE TABLE IF NOT EXISTS `NFSServerActivity` (
  `hostname` text COLLATE latin1_general_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `scallPerSec` float NOT NULL,
  `badCallPerSec` float NOT NULL,
  `packetPerSec` float NOT NULL,
  `udpPerSec` float NOT NULL,
  `tcpPerSec` float NOT NULL,
  `hitPerSec` float NOT NULL,
  `missPerSec` float NOT NULL,
  `sreadPerSec` float NOT NULL,
  `swritePerSec` float NOT NULL,
  `saccessPerSec` float NOT NULL,
  `sgetattPerSec` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pagingStatistics`
--

CREATE TABLE IF NOT EXISTS `pagingStatistics` (
  `hostname` text COLLATE latin1_general_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `pgpginPerSec` float NOT NULL,
  `pgpgoutPerSec` float NOT NULL,
  `faultPerSec` float NOT NULL,
  `majfltPerSec` float NOT NULL,
  `pgfreePerSec` float NOT NULL,
  `pgscankPerSec` float NOT NULL,
  `pgscandPerSec` float NOT NULL,
  `pgstealPerSec` float NOT NULL,
  `prcVmeff` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `queueLengthAndLoadStatistics`
--

CREATE TABLE IF NOT EXISTS `queueLengthAndLoadStatistics` (
  `hostname` text COLLATE latin1_general_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `runqSize` bigint(20) NOT NULL,
  `plistSize` bigint(20) NOT NULL,
  `ldAvg1` float NOT NULL,
  `ldAvg5` float NOT NULL,
  `ldAvg15` float NOT NULL,
  `blocked` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `socketUtilisation`
--

CREATE TABLE IF NOT EXISTS `socketUtilisation` (
  `hostname` text COLLATE latin1_general_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `totsck` bigint(20) NOT NULL,
  `tcpsck` bigint(20) NOT NULL,
  `updsck` bigint(20) NOT NULL,
  `rawsck` bigint(20) NOT NULL,
  `ipFrag` bigint(20) NOT NULL,
  `tcptw` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `swappingStatistics`
--

CREATE TABLE IF NOT EXISTS `swappingStatistics` (
  `hostname` text COLLATE latin1_general_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `pswpinPerSec` float NOT NULL,
  `pswpoutPerSec` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `swapSpaceUtilisation`
--

CREATE TABLE IF NOT EXISTS `swapSpaceUtilisation` (
  `hostname` text COLLATE latin1_general_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `kbswapfree` bigint(20) NOT NULL,
  `kbswapused` bigint(20) NOT NULL,
  `prcSwapUsed` float NOT NULL,
  `kbswapcad` bigint(20) NOT NULL,
  `prcSwapcad` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taskCreationAndSystemSwitchingActivity`
--

CREATE TABLE IF NOT EXISTS `taskCreationAndSystemSwitchingActivity` (
  `hostname` text COLLATE latin1_general_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `procPerSec` float NOT NULL,
  `cswchPerSec` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `TTYDeviceStatistics`
--

CREATE TABLE IF NOT EXISTS `TTYDeviceStatistics` (
  `hostname` text COLLATE latin1_general_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `TTY` bigint(20) NOT NULL,
  `rcvinPerSec` float NOT NULL,
  `xmtinPerSec` float NOT NULL,
  `frameErrPerSec` float NOT NULL,
  `prtyErrPersec` float NOT NULL,
  `brkPerSec` float NOT NULL,
  `ovRunPerSec` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
