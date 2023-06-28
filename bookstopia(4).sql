-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2023 at 05:35 PM
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
  `paymentId` int(11) NOT NULL,
  `deliveryMethod` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_mysql500_ci NOT NULL,
  `deliveryAddress` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_mysql500_ci NOT NULL,
  `deliveryStatus` enum('cancelled','ordered','delivered','') NOT NULL,
  `deliveryDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderId`, `order_detail_id`, `userId`, `quantity`, `totalPrice`, `orderDate`, `paymentId`, `deliveryMethod`, `deliveryAddress`, `deliveryStatus`, `deliveryDate`) VALUES
(8, NULL, 20, 2, 28, '2023-06-28 13:29:05', 1, '', '', 'ordered', '2023-07-26'),
(9, NULL, 30, 2, 41, '2023-06-28 13:29:05', 11, '', '', 'ordered', '2023-07-26'),
(10, NULL, 30, 2, 45, '2023-06-28 13:33:45', 17, '', '', 'ordered', '2023-07-31');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `detailId` int(10) NOT NULL,
  `orderId` int(11) NOT NULL,
  `itemId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`detailId`, `orderId`, `itemId`, `quantity`) VALUES
(1, 8, 1, 1),
(2, 8, 2, 1),
(3, 9, 4, 1),
(4, 9, 5, 1),
(5, 10, 4, 1),
(6, 10, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `paymentitems`
--

CREATE TABLE `paymentitems` (
  `itemId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `userPayMethodId` int(11) NOT NULL,
  `paymentMethod` enum('Kreditkarte','Paypal','Zahlschein','') NOT NULL,
  `payMethodDetail` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `paymentitems`
--

INSERT INTO `paymentitems` (`itemId`, `userId`, `userPayMethodId`, `paymentMethod`, `payMethodDetail`) VALUES
(1, 20, 1, 'Kreditkarte', '123453677'),
(3, 20, 2, 'Paypal', '5555555'),
(5, 20, 3, 'Zahlschein', '887878'),
(6, 30, 1, 'Kreditkarte', '87878655'),
(9, 38, 1, 'Paypal', '654611'),
(11, 36, 1, 'Zahlschein', '74444'),
(13, 49, 1, 'Zahlschein', '0'),
(14, 50, 1, 'Paypal', '656565656'),
(15, 51, 1, 'Paypal', 'fdger56tf'),
(16, 52, 1, 'Paypal', 'ABC123456'),
(17, 30, 2, 'Paypal', '666332hh'),
(18, 30, 3, 'Kreditkarte', '144569997');

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
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `salutation`, `firstName`, `lastName`, `address`, `postcode`, `location`, `email`, `username`, `password`, `active`, `admin`) VALUES
(20, 'Frau', 'Alexa', 'Maier', 'TestStrasse 29', 100001, 'Wienh', 'alex@test.at', 'testUser1', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 1),
(29, 'Divers', 'Lukas', 'Maier', 'Weg 1', 10005, 'Wien', 'lukas@test.at', 'testUser2', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(30, 'Frau', 'Klara', 'Weiss', 'Weg 1', 100099, 'Wien', 'klara@test.at', 'testUser3', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(31, 'Divers', 'Gil', 'Maurer', 'Straße 1888', 30001, 'Wien', 'gil@test.at', 'testUser4', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(32, 'Frau', 'Mia', 'Gruber', 'Test 1', 1000, 'Wien', 'mia@test.at', 'testUser6', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(33, 'Frau', 'Deni', 'Deni', 'Deni 17', 1010, 'Wien', 'deni@deni.at', 'deni', 'a2385d91b09bd4562f61f7a378f375ea400a593f6aca1a9a1bb6c066d95203b849df13c092fadc475df26b8beaf16c07a99e10898e246e94ee016ec885f59817', 1, 0),
(36, 'Herr', 'Tom', 'Tailor', 'DresdnerStr 33-4-1', 1220, 'Wien', 'tom@tailor.at', 'tom', 'tom', 1, 1),
(37, 'Frau', 'Lucy', 'Lu', 'SunsetDr 22105', 21568, 'Los Angeles', 'lucy@lu.us', 'lucy', 'lucy', 1, 1),
(38, 'Frau', 'Macy', 'Mae', 'EastridgeDr 55105', 44568, 'Reno', ',macy@mae.us', 'macy', 'macy', 1, 1),
(39, 'Frau', 'sdfg', 'sdf', 'sdf', 0, 'sdf', 'sdfsd@sdf.sf', 'asd', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(40, 'Herr', 'ttt', 'zzz', 'zukzi', 444, 'zujkzu', 'tt@rrr.hh', 'erzt', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(41, 'Herr', 'tzu', 'tzu', 'tzu', 0, 'tzu', 'dfsdf@ff.ar', 'wer', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(42, 'Frau', 'zzz', 'uuu', 'sdfsdf', 445566, 'GGGGG', 'zz@ww.com', 'zzz', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(43, 'Herr', 'ppp', 'ppp', 'ppp', 0, 'ppp', 'ppp@ppp.pp', 'ppp', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(45, 'Frau', 'PTest', 'QTest', 'LTest 35', 1145, 'Wien', 'testtt@tt.com', 'ptest', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(49, 'Frau', 'Sara', 'Seyedmirzaei', 'HSXY', 1190, 'Wien', 'sar@sar.at', 'sara', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(50, 'Frau', 'rttzujn', 'fgnfgn', 'fghfg', 545, 'fdgdfg', 'erter@zzzzkkk.kl', 'pupupupup', '62fdedc52cbd4e7fe96212b6c87610b70bc3e9c3106f3e559edb1d455d6b1505d40dd5ef9ecfc1ecb06f62ee73b5c9ff34d8d6a0f3d388bc3bc01d9480dbe9cc', 1, 0),
(51, 'Frau', 'ppppppppppppppp', 'oooooooooooooooooooo', 'uuuuuuuuuuuuuuuuuuuu', 9999, 'uoiouio', 'mail@de.de', 'usdusdufsduf', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 0, 0),
(52, 'Herr', 'zzz', 'zzz', 'zzz', 0, 'zzz', 'zzz@ttt.zz', 'aaa', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 1);

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
  ADD KEY `order2user` (`userId`),
  ADD KEY `order2payment` (`paymentId`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`detailId`),
  ADD KEY `product2orderdetail` (`itemId`),
  ADD KEY `orderid2orderdetail` (`orderId`);

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
  MODIFY `orderId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `detailId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `paymentitems`
--
ALTER TABLE `paymentitems`
  MODIFY `itemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

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
  ADD CONSTRAINT `oder2detail` FOREIGN KEY (`order_detail_id`) REFERENCES `order_details` (`detailId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order2payment` FOREIGN KEY (`paymentId`) REFERENCES `paymentitems` (`itemId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order2user` FOREIGN KEY (`userId`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `orderid2orderdetail` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `product2orderdetail` FOREIGN KEY (`itemId`) REFERENCES `book` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `paymentitems`
--
ALTER TABLE `paymentitems`
  ADD CONSTRAINT `user2paymentopt` FOREIGN KEY (`userId`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `voucher`
--
ALTER TABLE `voucher`
  ADD CONSTRAINT `voucher_ibfk_1` FOREIGN KEY (`personid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
