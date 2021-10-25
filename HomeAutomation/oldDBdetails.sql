-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 07, 2019 at 07:20 AM
-- Server version: 5.7.26
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `Automation`
--

-- --------------------------------------------------------

--
-- Table structure for table `NodeMaster`
--

CREATE TABLE `NodeMaster` (
  `NodeID` bigint(20) NOT NULL,
  `NodeCode` varchar(100) NOT NULL,
  `ProductID` bigint(20) NOT NULL,
  `NodeAddress` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `NodeMaster`
--

INSERT INTO `NodeMaster` (`NodeID`, `NodeCode`, `ProductID`, `NodeAddress`) VALUES
(21, 'n01', 5, '192.168.2.107'),
(22, 'n02', 5, '192.168.1.202'),
(23, 'n03', 5, '192.168.1.203'),
(24, 'n04', 5, '192.168.1.204'),
(25, 'n05', 5, '192.168.1.205'),
(26, 'n06', 5, '192.168.1.206'),
(27, 'n07', 5, '192.168.1.207'),
(28, 'h01', 5, '192.168.1.211'),
(29, 'h02', 5, '192.168.1.212');

-- --------------------------------------------------------

--
-- Table structure for table `ProductMaster`
--

CREATE TABLE `ProductMaster` (
  `ProductID` bigint(20) NOT NULL,
  `ProductCode` varchar(100) NOT NULL,
  `ProductName` varchar(200) NOT NULL,
  `AdminID` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ProductMaster`
--

INSERT INTO `ProductMaster` (`ProductID`, `ProductCode`, `ProductName`, `AdminID`) VALUES
(5, 'AAKRUTI', 'PR101', 2);

-- --------------------------------------------------------

--
-- Table structure for table `ProductOTPTable`
--

CREATE TABLE `ProductOTPTable` (
  `ProductID` bigint(20) NOT NULL,
  `OTP` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `RemoteDetails`
--

CREATE TABLE `RemoteDetails` (
  `RemoteID` bigint(20) NOT NULL,
  `SwitchCode` varchar(100) NOT NULL,
  `RemoteButtonCode` varchar(100) NOT NULL,
  `ButtonType` varchar(50) NOT NULL,
  `RemoteType` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `RemoteDetails`
--

INSERT INTO `RemoteDetails` (`RemoteID`, `SwitchCode`, `RemoteButtonCode`, `ButtonType`, `RemoteType`) VALUES
(8, 'N1S1', 'frfrffrfrfrfrfrfrfrfrfrfrf', 'btnSource', 'TV'),
(9, 'N1S1', 'dwew4rf3f34f34ff3ff3f3f', 'btnPower', 'TV'),
(10, 'N1S1', 'dwew4rf3f34fd34ff3ff3f3f', 'btnMute', 'TV'),
(11, 'N1S1', 'dwew4rf3f34f34ff3ff3f3f', 'menuButton', 'TV'),
(12, 'N1S1', 'dwew4rf3f34f34dff3ff3f3f', 'btnVolUp', 'TV'),
(13, 'N1S1', 'dwew4rf3f34f34ssff3ff3f3f', 'btnVolMinus', 'TV'),
(14, 'N1S1', 'dwew4rf3f34f34fdef3ff3f3f', 'btnOK', 'TV'),
(15, 'N1S1', 'dwew4rf3f34f34ff3ededff3f3f', 'arrow_up', 'TV'),
(16, 'N1S1', 'dwew4rf3f34f34ff3ff3ssf3f', 'arrow_down', 'TV'),
(17, 'N1S1', 'dwew4rf3f34f34ff3ff3f3f', 'arrow_right', 'TV'),
(18, 'N1S1', 'dwew4rf3f34f34ff3ff3fss3f', 'arrow_left', 'TV'),
(19, 'N1S1', 'dwew4rf3f34f34ff3ffsd3f3f', 'btnCHUp', 'TV'),
(20, 'N1S1', 'dwew4rf3f34f34ff3ff3sf3f', 'btnCHDown', 'TV'),
(21, 'N1S1', 'dwew4rf3f34f34ffsd3ff3f3f', 'numberBtn', 'TV'),
(22, 'N1S1', 'dwew4rf3f34f34ff3ff3f3f', 'numbers', 'TV'),
(23, 'N1S1', 'dwew4rf3f34f34ff3fwwf3f3f', 'numberOne', 'TV'),
(24, 'N1S1', 'dwew4rf3f34f34ff3ff333f3f', 'numberTwo', 'TV'),
(25, 'N1S1', 'dwew4rf3f34f34ff3ff3e3f3f', 'numberThree', 'TV'),
(26, 'N1S1', 'dwew4rf3f34f34ff3ff3f3e3e3f', 'numberFour', 'TV'),
(27, 'N1S1', 'dw66ew4rf3f34f34ff3ff3f3f', 'numberFive', 'TV'),
(28, 'N1S1', 'dwew4r8f3f34f34ff3ff3f3f', 'numberSix', 'TV'),
(29, 'N1S1', 'dwew4rf893f34f34ff3ff3f3f', 'numberSeven', 'TV'),
(30, 'N1S1', 'dwew4rf3fio34f34ff3ff3f3f', 'numberNine', 'TV'),
(31, 'N1S1', 'dwew4rf3f34iuf34ff3ff3f3f', 'numberZero', 'TV'),
(32, 'N1S1', 'dwew4rf3f34f3uyt4ff3ff3f3f', 'customButton1', 'TV'),
(33, 'N1S1', 'dwew4rf3f34f34fftty3ff3f3f', 'customButton2', 'TV'),
(34, 'N1S1', 'dwew4rf3f34f34ff3fftjn3f3f', 'customButton3', 'TV'),
(35, 'N1S1', 'dwew4rf3f34f34ff3ff3ffv3f', 'customButton4', 'TV'),
(36, 'N1S1', 'dwew4rf3f34f34ff3ff3fcvty3f', 'customButton5', 'TV'),
(37, 'N1S1', 'dwew4rf3f34f34ff3ff3f3ytt9f', 'customButton6', 'TV'),
(38, 'N1S1', 'custom 7 hjbugbkjkjgjk', 'customButton7', 'TV'),
(39, 'N1S1', 'custom 8 hjbugbkjkjgjk', 'customButton8', 'TV');

-- --------------------------------------------------------

--
-- Table structure for table `RoomMaster`
--

CREATE TABLE `RoomMaster` (
  `RoomID` bigint(20) NOT NULL,
  `RoomName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `SensorMaster`
--

CREATE TABLE `SensorMaster` (
  `SensorID` bigint(20) NOT NULL,
  `SensorCode` varchar(100) NOT NULL,
  `SensorName` varchar(1000) NOT NULL,
  `SensorStatus` varchar(100) NOT NULL,
  `SensorType` varchar(100) NOT NULL,
  `NodeCode` varchar(100) NOT NULL,
  `DeviceStatus` varchar(100) NOT NULL,
  `device1` varchar(100) NOT NULL,
  `device2` varchar(100) NOT NULL,
  `device3` varchar(100) NOT NULL,
  `device4` varchar(100) NOT NULL,
  `device5` varchar(100) NOT NULL,
  `ProductID` bigint(20) NOT NULL,
  `NodeAddress` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `SensorMaster`
--

INSERT INTO `SensorMaster` (`SensorID`, `SensorCode`, `SensorName`, `SensorStatus`, `SensorType`, `NodeCode`, `DeviceStatus`, `device1`, `device2`, `device3`, `device4`, `device5`, `ProductID`, `NodeAddress`) VALUES
(1, 'h01', 'Hall sensor', 'no', 'pir', 'h01', 'on', 'n01sa01', 'null', 'null', 'null', 'null', 5, '192.168.1.118'),
(2, 'h02ss01', 'Bedroom sensor', 'off', 'pir', 'h02', 'on', 'n01sl01', 'n01sl02', 'n01sa01', 'n01sw01', 'null', 5, 'http://192.168.1.119/');

-- --------------------------------------------------------

--
-- Table structure for table `SwitchMaster`
--

CREATE TABLE `SwitchMaster` (
  `SwitchID` bigint(20) NOT NULL,
  `SwitchCode` varchar(100) NOT NULL,
  `SwitchName` varchar(1000) NOT NULL,
  `NodeID` bigint(20) NOT NULL,
  `SwitchStatus` varchar(22) NOT NULL DEFAULT 'off',
  `SwitchType` varchar(10) NOT NULL DEFAULT 'Toggle'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `SwitchMaster`
--

INSERT INTO `SwitchMaster` (`SwitchID`, `SwitchCode`, `SwitchName`, `NodeID`, `SwitchStatus`, `SwitchType`) VALUES
(42, 'n01sl01', 'Light-1', 21, 'off', 'Toggle'),
(43, 'n01sl02', 'Light-2', 21, 'off', 'Toggle'),
(44, 'n01sa01', 'A.C.', 21, 'off', 'Toggle'),
(45, 'n01sw01', 'Socket', 21, 'off', 'Toggle'),
(46, 'n01sf01', 'Fan', 21, 'off', 'regulator'),
(47, 'n02sl01', 'Light-1', 22, 'on', 'Toggle'),
(48, 'n02sl02', 'Light-2', 22, 'off', 'Toggle'),
(49, 'n02sa01', 'A.C.', 22, 'on', 'Toggle'),
(50, 'n02sw01', 'Socket', 22, 'on', 'Toggle'),
(51, 'n02sf01', 'Fan', 22, 'on', 'regulator'),
(52, 'n03sl01', 'n03sl01', 23, 'on', 'Toggle'),
(53, 'n03sl02', 'n03sl02', 23, 'off', 'Toggle'),
(54, 'n03sa01', 'n03sa01', 23, 'off', 'Toggle'),
(55, 'n03sw01', 'n03sw01', 23, 'off', 'Toggle'),
(56, 'n03sf01', 'n03sf01', 23, 'off', 'regulator'),
(57, 'n04sl01', 'n04sl01', 24, 'off', 'Toggle'),
(58, 'n04sl02', 'n04sl02', 24, 'off', 'Toggle'),
(59, 'n04sa01', 'n04sa01', 24, 'off', 'Toggle'),
(60, 'n04sw01', 'n04sw01', 24, 'off', 'Toggle'),
(61, 'n04sf01', 'n04sf01', 24, 'off', 'regulator'),
(62, 'n05sl01', 'n05sl01', 25, 'on', 'Toggle'),
(63, 'n05sl02', 'n05sl02', 25, 'off', 'Toggle'),
(64, 'n05sa01', 'n05sa01', 25, 'off', 'Toggle'),
(65, 'n05sw01', 'n05sw01', 25, 'off', 'Toggle'),
(66, 'n05sf01', 'n05sf01', 25, 'off', 'regulator'),
(67, 'n06sl01', 'n06sl01', 26, 'on', 'Toggle'),
(68, 'n06sl02', 'n06sl02', 26, 'on', 'Toggle'),
(69, 'n06sa01', 'n06sa01', 26, 'on', 'Toggle'),
(70, 'n06sw01', 'n06sw01', 26, 'on', 'Toggle'),
(71, 'n06sf01', 'n06sf01', 26, 'l4', 'regulator'),
(72, 'n07sl01', 'n07sl01', 27, 'off', 'Toggle'),
(73, 'n07sl02', 'n07sl02', 27, 'off', 'Toggle'),
(74, 'n07sa01', 'n07sa01', 27, 'off', 'Toggle'),
(75, 'n07sw01', 'n07sw01', 27, 'off', 'Toggle'),
(76, 'n07sf01', 'n07sf01', 27, 'off', 'regulator'),
(77, 'N1S1', 'TVremote', 21, 'off', 'Remote'),
(78, 'n01su01', 'UP', 21, 'off', 'Toggle');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `NodeMaster`
--
ALTER TABLE `NodeMaster`
  ADD PRIMARY KEY (`NodeID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `ProductMaster`
--
ALTER TABLE `ProductMaster`
  ADD PRIMARY KEY (`ProductID`),
  ADD KEY `AdminID` (`AdminID`);

--
-- Indexes for table `ProductOTPTable`
--
ALTER TABLE `ProductOTPTable`
  ADD PRIMARY KEY (`ProductID`),
  ADD UNIQUE KEY `ProductID` (`ProductID`);

--
-- Indexes for table `RemoteDetails`
--
ALTER TABLE `RemoteDetails`
  ADD PRIMARY KEY (`RemoteID`);

--
-- Indexes for table `RoomMaster`
--
ALTER TABLE `RoomMaster`
  ADD PRIMARY KEY (`RoomID`);

--
-- Indexes for table `SensorMaster`
--
ALTER TABLE `SensorMaster`
  ADD PRIMARY KEY (`SensorID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `SwitchMaster`
--
ALTER TABLE `SwitchMaster`
  ADD PRIMARY KEY (`SwitchID`),
  ADD KEY `NodeID` (`NodeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `NodeMaster`
--
ALTER TABLE `NodeMaster`
  MODIFY `NodeID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `ProductMaster`
--
ALTER TABLE `ProductMaster`
  MODIFY `ProductID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `RemoteDetails`
--
ALTER TABLE `RemoteDetails`
  MODIFY `RemoteID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `RoomMaster`
--
ALTER TABLE `RoomMaster`
  MODIFY `RoomID` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `SensorMaster`
--
ALTER TABLE `SensorMaster`
  MODIFY `SensorID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `SwitchMaster`
--
ALTER TABLE `SwitchMaster`
  MODIFY `SwitchID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `NodeMaster`
--
ALTER TABLE `NodeMaster`
  ADD CONSTRAINT `nodemaster_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `ProductMaster` (`ProductID`);

--
-- Constraints for table `ProductMaster`
--
ALTER TABLE `ProductMaster`
  ADD CONSTRAINT `productmaster_ibfk_1` FOREIGN KEY (`AdminID`) REFERENCES `UserMaster` (`userId`);

--
-- Constraints for table `ProductOTPTable`
--
ALTER TABLE `ProductOTPTable`
  ADD CONSTRAINT `productotptable_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `ProductMaster` (`ProductID`);

--
-- Constraints for table `SensorMaster`
--
ALTER TABLE `SensorMaster`
  ADD CONSTRAINT `SensorMaster_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `ProductMaster` (`ProductID`);

--
-- Constraints for table `SwitchMaster`
--
ALTER TABLE `SwitchMaster`
  ADD CONSTRAINT `switchmaster_ibfk_1` FOREIGN KEY (`NodeID`) REFERENCES `NodeMaster` (`NodeID`);
