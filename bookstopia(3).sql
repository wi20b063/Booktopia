-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2023 at 08:02 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookstopia`
--

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `id` int(10) NOT NULL,
  `image_url` text NOT NULL,
  `titel` text NOT NULL,
  `autor` text NOT NULL,
  `preis` int(11) NOT NULL,
  `bewertung` int(11) DEFAULT NULL,
  `kategorie` enum('Biographie','Sachbuch','Krimi','Allgemein') NOT NULL,
  `language` enum('Deutsch','Englisch','Französisch','Andere') NOT NULL,
  `isbn` varchar(17) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT 'No description available',
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`id`, `image_url`, `titel`, `autor`, `preis`, `bewertung`, `kategorie`, `language`, `isbn`, `description`, `stock`) VALUES
(1, '../res/img/Queen Charlotte.PNG', 'Queen Charlotte', 'Julia Quinn + weitere', 13, 5, 'Biographie', 'Englisch', '978-344215147', 'Ein schrecklicher Fund im Büro des Commissioner stellt alle vor ein...', 20),
(2, '../res/img/West Well.PNG', 'West Well', 'Lena Kiefer', 15, 4, '', 'Deutsch', '', 'No description available', 16),
(3, '../res/img/Die 1 Methode.PNG', 'Die 1% Methode', 'James Clear', 14, 4, 'Sachbuch', 'Englisch', '', 'Abnehmen für dummies', 5),
(4, '../res/img/Wald Wissen.PNG', 'Wald Wissen', 'Peter Wohlleben + weitere', 30, 1, 'Sachbuch', 'Englisch', '', 'No description available', 3),
(5, 'cafeaEdW', 'Das Café am Rande der Welt: eine Erzählung über den Sinn des Lebens', 'John Strelecky', 11, 4, 'Allgemein', 'Deutsch', '978-3423209694', 'Ein Buch das zum Nachdenken anregt', 6);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderId` int(10) NOT NULL,
  `order_detail_id` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `totalPrice` float NOT NULL,
  `orderDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `CreditCard` int(11) NOT NULL,
  `deliveryMethod` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_mysql500_ci NOT NULL,
  `deliveryAddress` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_mysql500_ci NOT NULL,
  `deliveryStatus` int(1) NOT NULL,
  `deliveryDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `detailId` int(10) NOT NULL,
  `orderId` int(11) NOT NULL,
  `itemId` int(11) NOT NULL,
  `voucherID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `paymentitems`
--

CREATE TABLE `paymentitems` (
  `itemId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `userPayMethodId` int(11) NOT NULL,
  `paymentMethod` enum('Kreditkarte','Paypal','Zahlschein','') NOT NULL,
  `payMethodDetail` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `paymentitems`
--

INSERT INTO `paymentitems` (`itemId`, `userId`, `userPayMethodId`, `paymentMethod`, `payMethodDetail`) VALUES
(1, 20, 1, 'Kreditkarte', 123453677),
(3, 20, 2, 'Paypal', 5555555),
(5, 20, 3, 'Zahlschein', 887878),
(6, 30, 1, 'Kreditkarte', 87878655),
(9, 38, 1, 'Paypal', 654611),
(11, 36, 1, 'Zahlschein', 74444);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` int(10) NOT NULL,
  `salutation` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `postcode` int(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `creditCard` int(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `salutation`, `firstName`, `lastName`, `address`, `postcode`, `location`, `creditCard`, `email`, `username`, `password`, `active`, `admin`) VALUES
(20, 'Frau', 'Alex', 'Maier', 'Test 1', 1000, 'Wien', 1, 'alex@test.at', 'testUser1', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(29, 'Herr', 'Lukas', 'Maier', 'Weg 1', 1000, 'Wien', 1, 'lukas@test.at', 'testUser2', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(30, 'Frau', 'Klara', 'Weiss', 'Weg 1', 1000, 'Wien', 1, 'klara@test.at', 'testUser3', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(31, 'Divers', 'Gil', 'Maurer', 'Straße 1', 3000, 'Wien', 1, 'gil@test.at', 'testUser4', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(32, 'Frau', 'Mia', 'Gruber', 'Test 1', 1000, 'Wien', 1, 'mia@test.at', 'testUser6', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(33, 'Frau', 'Deni', 'Deni', 'Deni 1', 1010, 'Wien', 1234567, 'deni@deni.at', 'deni', 'a2385d91b09bd4562f61f7a378f375ea400a593f6aca1a9a1bb6c066d95203b849df13c092fadc475df26b8beaf16c07a99e10898e246e94ee016ec885f59817', 1, 0),
(36, 'Herr', 'Tom', 'Tailor', 'DresdnerStr 33-4-1', 1220, 'Wien', 2147483647, 'tom@tailor.at', 'tom', 'tom', 1, 1),
(37, 'Frau', 'Lucy', 'Lu', 'SunsetDr 22105', 21568, 'Los Angeles', 2147483647, 'lucy@lu.us', 'lucy', 'lucy', 1, 1),
(38, 'Frau', 'Macy', 'Mae', 'EastridgeDr 55105', 44568, 'Reno', 2147483647, ',macy@mae.us', 'macy', 'macy', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `voucher`
--

CREATE TABLE `voucher` (
  `id` int(11) NOT NULL,
  `vcode` varchar(8) NOT NULL,
  `personid` int(11) DEFAULT NULL,
  `orderDetail` int(11) DEFAULT NULL,
  `value` int(11) NOT NULL,
  `valid_from` date NOT NULL,
  `valid_to` date NOT NULL,
  `consumed` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `voucher`
--

INSERT INTO `voucher` (`id`, `vcode`, `personid`, `orderDetail`, `value`, `valid_from`, `valid_to`, `consumed`) VALUES
(1, 'BX4H9', 30, NULL, 8, '2023-06-14', '2023-07-15', 0),
(2, 'BX4H9', 30, NULL, 8, '2023-06-15', '2023-07-15', 0),
(3, 'BX4H9', 31, NULL, 8, '2023-06-20', '2023-07-21', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `oder2detail` (`order_detail_id`),
  ADD KEY `order2user` (`userId`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`detailId`),
  ADD KEY `product2orderdetail` (`itemId`);

--
-- Indexes for table `paymentitems`
--
ALTER TABLE `paymentitems`
  ADD PRIMARY KEY (`itemId`),
  ADD KEY `user2paymentopt` (`userId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `userid` (`userid`);

--
-- Indexes for table `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`id`),
  ADD KEY `personid` (`personid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `detailId` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paymentitems`
--
ALTER TABLE `paymentitems`
  MODIFY `itemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `voucher`
--
ALTER TABLE `voucher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `oder2detail` FOREIGN KEY (`order_detail_id`) REFERENCES `order_details` (`detailId`),
  ADD CONSTRAINT `order2user` FOREIGN KEY (`userId`) REFERENCES `user` (`userid`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `product2orderdetail` FOREIGN KEY (`itemId`) REFERENCES `book` (`id`);

--
-- Constraints for table `paymentitems`
--
ALTER TABLE `paymentitems`
  ADD CONSTRAINT `user2paymentopt` FOREIGN KEY (`userId`) REFERENCES `user` (`userid`);

--
-- Constraints for table `voucher`
--
ALTER TABLE `voucher`
  ADD CONSTRAINT `voucher_ibfk_1` FOREIGN KEY (`personid`) REFERENCES `user` (`userid`) ON DELETE SET NULL ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
