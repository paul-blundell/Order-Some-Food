SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ordersomefood`
--

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Table structure for table `api_clients`
--

CREATE TABLE IF NOT EXISTS `api_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_key` varchar(250) NOT NULL,
  `client_secret` varchar(250) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1  ;

--
-- Dumping data for table `api_clients`
--

INSERT INTO `api_clients` (`id`, `client_key`, `client_secret`, `active`) VALUES
(2, 'android', 'longandsecuresecret', 1),
(3, 'website', 'anotherlongandsecurekey', 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Chinese'),
(2, 'Indian'),
(3, 'Pizza'),
(4, 'Thai'),
(5, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `menuId` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `price` double NOT NULL,
  `categoryId` int(11) NOT NULL,
  PRIMARY KEY (`menuId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menuId`, `parent`, `name`, `description`, `price`, `categoryId`) VALUES
(1, 0, 'Prawn Cocktail', '', 3.2, 1),
(2, 0, 'Chicken Wings (3)', '', 2.6, 1),
(4, 0, 'Roast Duck Chinese Style', '', 5.6, 2),
(5, 0, 'Honey Roast Chicken', '', 4.9, 2),
(6, 0, 'Beef with Bamboo Shoots', '', 4.1, 2),
(7, 0, 'Pepperoni', 'Pepperoni and extra cheese', 0, 3),
(12, 7, 'Personal', '', 4.99, 3),
(9, 0, 'Meat Feast', '', 12.99, 3),
(10, 0, 'Fries', '', 0, 4),
(11, 10, 'Small', '', 1.2, 4),
(13, 7, 'Medium', '', 7.99, 3),
(25, 9, 'Medium', '', 5.99, 3),
(14, 10, 'Large', '', 2.2, 4),
(24, 9, 'Personal', '', 3.99, 3),
(26, 9, 'Large', '', 8.99, 3),
(27, 9, 'Family', '', 11.99, 3),
(36, 7, 'Large', '', 9.87, 3),
(40, 39, 'Small', '', 2.99, 3),
(41, 39, 'Medium', '', 4.5, 3),
(47, 0, 'test', 'test', 1.2, 4),
(48, 0, 'test2', 'testing', 5.6, 4),
(49, 0, 'test3', 'more testing', 3.56, 4),
(50, 0, 'test4', 'further test', 2.89, 4);

-- --------------------------------------------------------

--
-- Table structure for table `menu_categories`
--

CREATE TABLE IF NOT EXISTS `menu_categories` (
  `categoryId` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) NOT NULL,
  `takeawayId` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`categoryId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

--
-- Dumping data for table `menu_categories`
--

INSERT INTO `menu_categories` (`categoryId`, `category_name`, `takeawayId`, `position`) VALUES
(1, 'Starters', 2, 0),
(2, 'Meat Dishes', 2, 0),
(3, 'Pizza', 1, 0),
(4, 'Sides', 1, 2),
(5, 'Burgers', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `menu_to_orders`
--

CREATE TABLE IF NOT EXISTS `menu_to_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderId` int(11) NOT NULL,
  `menuId` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `priceAtOrder` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

--
-- Dumping data for table `menu_to_orders`
--

INSERT INTO `menu_to_orders` (`id`, `orderId`, `menuId`, `qty`, `priceAtOrder`) VALUES
(1, 1, 12, 2, 4.99),
(2, 1, 13, 1, 7.99),
(3, 1, 11, 1, 1.2),
(7, 3, 1, 2, 3.2),
(8, 3, 5, 1, 4.9),
(9, 3, 4, 1, 5.6),
(10, 4, 27, 3, 11.99),
(11, 4, 44, 1, 3.99);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `orderId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `takeawayId` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `delivery_name` varchar(50) NOT NULL,
  `address1` varchar(50) NOT NULL,
  `address2` varchar(50) NOT NULL,
  `town` varchar(50) NOT NULL,
  `postcode` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `deliveryType` tinyint(1) NOT NULL COMMENT '1 = Collection, 2 = Cash on Delivery',
  `additional` text NOT NULL,
  `status` int(11) NOT NULL COMMENT '0 = placed, 1 = complete',
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`orderId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderId`, `userId`, `takeawayId`, `date`, `delivery_name`, `address1`, `address2`, `town`, `postcode`, `phone`, `deliveryType`, `additional`, `status`, `paid`) VALUES
(1, 0, 1, '2012-04-25 21:10:02', 'Paul', '', '', '', '', '07555555555', 1, '', 1, 0),
(3, 0, 2, '2012-04-25 21:13:36', 'Paul', '', '', '', '', '07555555555', 1, '', 1, 0),
(4, 0, 1, '2012-04-25 21:14:57', 'Joe Bloggs', 'High Street', '', 'Aberystwyth', 'SY231DX', '0555 555 5555', 2, '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `paypal`
--

CREATE TABLE IF NOT EXISTS `paypal` (
  `takeawayId` int(11) NOT NULL,
  `paypalActive` tinyint(1) NOT NULL,
  `paypalEmail` varchar(255) NOT NULL,
  `paypalSignature` text NOT NULL,
  `paypalPassword` text NOT NULL,
  PRIMARY KEY (`takeawayId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE IF NOT EXISTS `ratings` (
  `ratingId` int(11) NOT NULL AUTO_INCREMENT,
  `takeawayId` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `ip` text NOT NULL,
  PRIMARY KEY (`ratingId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`ratingId`, `takeawayId`, `rating`, `ip`) VALUES
(1, 1, 3, ''),
(2, 1, 5, ''),
(4, 2, 4, '0.0.0.0'),
(5, 1, 5, '87.112.111.121'),
(6, 1, 1, '144.124.4.27'),
(7, 2, 1, '144.124.1.118'),
(8, 1, 4, '144.124.16.28'),
(9, 1, 5, '144.124.1.127'),
(20, 1, 5, '87.115.119.212');


-- --------------------------------------------------------

--
-- Table structure for table `takeaways`
--

CREATE TABLE IF NOT EXISTS `takeaways` (
  `takeawayId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `shortname` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `postcode` varchar(20) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `description` text NOT NULL,
  `deliveryCharge` double NOT NULL,
  `deliveryTime` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `latitude` float(10,6) NOT NULL,
  `longitude` float(10,6) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0 - Closed, 1 = Open',
  `userId` int(11) NOT NULL,
  `background` text,
  `fontsize` int(5) NOT NULL DEFAULT '12',
  `buttons` varchar(10) NOT NULL DEFAULT '#C6231F',
  `categoryColour` varchar(10) NOT NULL DEFAULT '#F0E68C',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = Pending, 1 = Approved, 2 = Approved with no ads',
  PRIMARY KEY (`takeawayId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

--
-- Dumping data for table `takeaways`
--

INSERT INTO `takeaways` (`takeawayId`, `name`, `shortname`, `address`, `postcode`, `phone`, `description`, `deliveryCharge`, `deliveryTime`, `category`, `latitude`, `longitude`, `status`, `userId`, `background`, `fontsize`, `buttons`, `categoryColour`, `type`) VALUES
(1, 'California Pizza', 'california', 'Some Address\nAberystwyth', 'SY23 1HB', NULL, 'Delicious pizza, burgers and chicken.', 2, 45, 3, 52.415016, -4.086895, 1, 10, 'http://owlstoathens.driftdesignstudios.com/wp-content/gallery/the-bears-den-mural/abstract-blue-background-wallpaper-i3.jpg', 12, '#598ACC', '#7D98E5', 2),
(2, 'Chinese Palace', 'chinesepalace', 'Some other address', 'SY23', NULL, 'Chinese restaurant with unbeatable prices!', 0, 45, 1, 52.414459, -4.086368, 1, 10, '', 14, '', '#F0E68C', 1),
(3, 'Squares Pizza', 'squares', 'Unit 1 Some Place\r\nThis Road\r\nAberystwyth\r\nSY23 1LN ', '', '555555', 'Perfect for pizza lovers', 0, 0, 3, 52.415100, -4.080300, 0, 9, '', 0, '0', '#F0E68C', 1),
(4, 'Kebab Rush', 'kebabrush', 'An address', '', NULL, '', 0, 0, 5, 52.587299, -2.124900, 0, 9, NULL, 0, '0', '#F0E68C', 0),
(5, 'No.1 Pizza', 'no1pizza', 'Address', '', NULL, '', 0, 0, 3, 52.585098, -2.126000, 0, 15, NULL, 0, '0', '#F0E68C', 1),
(6, 'Perfect Pizza', 'perfectpizza', 'Some Address', '', NULL, '', 0, 0, 3, 52.559898, -2.154500, 0, 15, NULL, 0, '0', '#F0E68C', 1);


--
-- Table structure for table `takeaway_openingtimes`
--

CREATE TABLE IF NOT EXISTS `takeaway_openingtimes` (
  `takeawayId` int(11) NOT NULL,
  `dayOfWeek` enum('mon','tue','wed','thu','fri','sat','sun') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `openingTime` time NOT NULL,
  `closingTime` time NOT NULL,
  `open` tinyint(1) NOT NULL DEFAULT '1',
  UNIQUE KEY `takeawayId` (`takeawayId`,`dayOfWeek`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `takeaway_openingtimes`
--

INSERT INTO `takeaway_openingtimes` (`takeawayId`, `dayOfWeek`, `openingTime`, `closingTime`, `open`) VALUES
(1, 'mon', '19:00:00', '05:00:00', 1),
(1, 'tue', '16:00:00', '06:00:00', 1),
(1, 'wed', '14:00:00', '05:00:00', 1),
(1, 'thu', '00:00:00', '00:00:00', 0),
(1, 'fri', '00:00:00', '00:00:00', 0),
(1, 'sat', '00:00:00', '00:00:00', 0),
(1, 'sun', '00:00:00', '00:00:00', 0);


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `groupId` int(11) NOT NULL,
  PRIMARY KEY (`uId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uId`, `name`, `email`, `password`, `groupId`) VALUES
(1, 'testname', 'test@test.test', 'a59ff947760aa8f9fabc367241a42212bbe6e700', 3),
(2, '', 'test2@test.test', '18ee738904e7f966711ab1406947285bac006cd5', 1),
(9, '', 'test5@test.test', '314bb0e3428b0b2731605190ba2b0af1aee8adf0', 2),
(8, '', 'test3@test.test', 'de19fa799096812c170d1a25357ff93d71e42770', 1),
(10, '', 'test4@test.test', 'e261ee2c41bbb786b0950ae4e1ed052d0b0a5b90', 2),
(15, '', 'test6@test.test', '625489a6fc2182f9fc9cdd9ecf8af89db5979545', 2);

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE IF NOT EXISTS `user_groups` (
  `groupId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`groupId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`groupId`, `name`) VALUES
(1, 'User'),
(2, 'Takeaway'),
(3, 'Administrator');


