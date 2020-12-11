--
-- Database: `meraki`
--
CREATE DATABASE IF NOT EXISTS `meraki` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `meraki`;

-- --------------------------------------------------------

--
-- Table structure for table `meraki_observations`
--

CREATE TABLE `meraki_observations` (
  `observation_id` int(26) NOT NULL,
  `apMac` varchar(50) NOT NULL,
  `apFloors` varchar(100) DEFAULT NULL,
  `apTags` varchar(100) DEFAULT NULL,
  `ipv4` varchar(50) DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `unc` decimal(10,0) DEFAULT NULL,
  `x` decimal(10,0) DEFAULT NULL,
  `y` decimal(10,0) DEFAULT NULL,
  `seenTime` datetime NOT NULL DEFAULT current_timestamp(),
  `ssid` varchar(100) NOT NULL,
  `os` varchar(100) NOT NULL,
  `clientMac` varchar(100) NOT NULL,
  `seenEpoch` varchar(100) NOT NULL,
  `rssi` int(11) NOT NULL,
  `ipv6` varchar(100) NOT NULL,
  `manufacturer` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `meraki_visits`
--

CREATE TABLE `meraki_visits` (
  `observation_id` int(26) NOT NULL,
  `apMac` varchar(50) NOT NULL,
  `apFloors` varchar(100) DEFAULT NULL,
  `apTags` varchar(100) DEFAULT NULL,
  `ipv4` varchar(50) DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `unc` decimal(10,0) DEFAULT NULL,
  `x` decimal(10,0) DEFAULT NULL,
  `y` decimal(10,0) DEFAULT NULL,
  `startTime` datetime NOT NULL DEFAULT current_timestamp(),
  `endTime` datetime NOT NULL DEFAULT current_timestamp(),
  `visitTime` time GENERATED ALWAYS AS (timediff(`endTime`,`startTime`)) STORED,
  `ssid` varchar(100) NOT NULL,
  `os` varchar(100) NOT NULL,
  `clientMac` varchar(100) NOT NULL,
  `seenEpoch` varchar(100) NOT NULL,
  `rssi` int(11) NOT NULL,
  `ipv6` varchar(100) NOT NULL,
  `manufacturer` varchar(100) NOT NULL,
  `lastUpdated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `meraki_observations`
--
ALTER TABLE `meraki_observations`
  ADD PRIMARY KEY (`observation_id`);

--
-- Indexes for table `meraki_visits`
--
ALTER TABLE `meraki_visits`
  ADD PRIMARY KEY (`observation_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `meraki_observations`
--
ALTER TABLE `meraki_observations`
  MODIFY `observation_id` int(26) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meraki_visits`
--
ALTER TABLE `meraki_visits`
  MODIFY `observation_id` int(26) NOT NULL AUTO_INCREMENT;
COMMIT;
