-- Monopoly Shop Database
-- Database Systems Project
-- Contains product catalog, customers, orders, inventory management, and information pages

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

SET NAMES utf8mb4;


-- --------------------------------------------------------
-- Table: CATEGORIES
-- --------------------------------------------------------

CREATE TABLE `CATEGORIES` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;


INSERT INTO `CATEGORIES`
(`category_id`, `category_name`) VALUES
(1, 'Classic Monopoly'),
(2, 'Family Editions'),
(3, 'Pop Culture Editions'),
(4, 'City & Travel Editions'),
(5, 'Accessories');


-- --------------------------------------------------------
-- Table: CUSTOMERS
-- --------------------------------------------------------

CREATE TABLE `CUSTOMERS` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;


-- Demo account only
INSERT INTO `CUSTOMERS`
(`customer_id`, `customer_name`, `customer_email`, `username`, `password_hash`, `created_at`)
VALUES
(1,
'Demo Customer',
'demo@example.com',
'demo_user',
'DEMO_PASSWORD_HASH',
'2026-01-01 00:00:00');


-- --------------------------------------------------------
-- Table: EMPLOYEES
-- --------------------------------------------------------

CREATE TABLE `EMPLOYEES` (
  `employee_id` int(11) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `employee_role` varchar(50) NOT NULL,
  `employee_username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;


-- Demo employee account only
INSERT INTO `EMPLOYEES`
(`employee_id`, `employee_name`, `employee_role`, `employee_username`, `password_hash`, `created_at`)
VALUES
(1,
'Demo Administrator',
'admin',
'demo_admin',
'DEMO_PASSWORD_HASH',
'2026-01-01 00:00:00');

-- --------------------------------------------------------
-- Table: monopoly_info_pages
-- --------------------------------------------------------

CREATE TABLE `monopoly_info_pages` (
  `info_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;


INSERT INTO `monopoly_info_pages`
(`info_id`, `title`, `content`, `created_at`) VALUES

(1,
'What is Monopoly?',
'Monopoly is a real-estate trading board game originally created in 1935.',
'2026-01-01 00:00:00'),

(2,
'History of Monopoly',
'Monopoly was inspired by earlier property trading games and became one of the most popular board games worldwide.',
'2026-01-01 00:00:00'),

(3,
'Monopoly Editions',
'Monopoly has many editions including Classic, Family, Pop Culture, City, and Travel editions.',
'2026-01-01 00:00:00'),

(4,
'Strategy & Tips',
'Successful Monopoly strategies include smart property purchases, managing money carefully, and negotiating trades.',
'2026-01-01 00:00:00'),

(5,
'Rules of Monopoly',
'Players move around the board, purchase properties, collect rent, and try to avoid bankruptcy.',
'2026-01-01 00:00:00');

-- --------------------------------------------------------
-- Table: ORDERS
-- --------------------------------------------------------

CREATE TABLE `ORDERS` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_total` decimal(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;


-- Demo orders only

INSERT INTO `ORDERS`
(`order_id`, `customer_id`, `order_date`, `order_total`) VALUES

(1,
1,
'2026-01-01 12:00:00',
59.98),

(2,
1,
'2026-01-02 14:30:00',
29.99);

-- --------------------------------------------------------
-- Table: order_items
-- --------------------------------------------------------

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;


INSERT INTO `order_items`
(`order_item_id`, `order_id`, `product_id`, `quantity`, `purchase_price`) VALUES

(1,
1,
2,
2,
29.99),

(2,
2,
89,
1,
39.99);

-- --------------------------------------------------------
-- Table: products
-- --------------------------------------------------------

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `product_description` text NOT NULL,
  `product_qty` int(11) NOT NULL,
  `product_price` decimal(8,2) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;


INSERT INTO `products`
(`product_id`, `product_name`, `product_description`, `product_qty`, `product_price`, `category_id`) VALUES

(2,
'Classic Monopoly',
'Classic fast-dealing property trading game. Buy, sell, and bankrupt opponents.',
100,
29.99,
1),

(3,
'Monopoly Junior',
'Simplified Monopoly version designed for younger players.',
100,
19.99,
2),

(5,
'Monopoly Deal',
'Fast card game involving property sets, trading, and collecting rent.',
100,
9.99,
2),

(7,
'Monopoly Empire',
'Build your tower using famous brands and compete for the highest tower.',
100,
24.99,
2),

(11,
'Monopoly Cheaters Edition',
'A Monopoly edition where players can use special cheat cards.',
100,
26.99,
2),

(13,
'Monopoly Speed',
'A faster Monopoly experience designed for quick games.',
100,
21.99,
2),

(17,
'Monopoly Builder',
'Collect resources and build structures while competing with others.',
100,
27.99,
2),

(19,
'Monopoly Electronic Banking',
'Uses electronic banking instead of traditional paper money.',
100,
34.99,
2),

(23,
'Monopoly Voice Banking',
'Voice activated banking experience featuring Mr. Monopoly.',
100,
39.99,
2),

(29,
'Monopoly Longest Game Ever',
'A special edition designed for extended gameplay.',
100,
31.99,
1),

(31,
'Monopoly Here and Now',
'A modern version featuring updated locations.',
100,
29.99,
1),

(37,
'Monopoly Luxury Edition',
'Premium Monopoly edition with upgraded components.',
100,
99.99,
1),

(41,
'Monopoly Disney Edition',
'Disney themed Monopoly featuring famous characters.',
100,
39.99,
3),

(43,
'Monopoly Marvel Edition',
'Marvel themed Monopoly featuring superheroes.',
100,
39.99,
3),

(47,
'Monopoly Star Wars Edition',
'Star Wars themed Monopoly with planets and characters.',
100,
39.99,
3),

(53,
'Monopoly Pokemon Edition',
'Pokemon themed Monopoly featuring Pokemon locations.',
100,
39.99,
3),

(59,
'Monopoly Fortnite Edition',
'Fortnite themed Monopoly with locations from the game.',
100,
39.99,
3),

(61,
'Monopoly Stranger Things Edition',
'Stranger Things themed Monopoly board game.',
100,
39.99,
3),

(67,
'Monopoly Friends Edition',
'Friends TV show themed Monopoly edition.',
100,
39.99,
3),

(71,
'Monopoly New York City Edition',
'NYC themed Monopoly featuring city landmarks.',
100,
34.99,
4),

(73,
'Monopoly National Parks Edition',
'Celebrate national parks with this themed edition.',
100,
34.99,
4),

(79,
'Monopoly World Edition',
'International edition featuring cities around the world.',
100,
34.99,
4),

(83,
'Monopoly Simpsons Edition',
'The Simpsons themed Monopoly game.',
100,
39.99,
3),

(89,
'Monopoly Game of Thrones Edition',
'Game of Thrones themed Monopoly edition.',
100,
39.99,
3),

(97,
'Monopoly SpongeBob SquarePants Edition',
'SpongeBob themed Monopoly game.',
100,
39.99,
3),

(101,
'Replacement Monopoly Money Pack',
'Replacement Monopoly money accessories.',
50,
7.99,
5),

(103,
'Replacement Property Cards',
'Replacement property card set.',
50,
5.99,
5),

(107,
'Replacement Chance and Community Chest Cards',
'Replacement card decks for Monopoly.',
50,
6.99,
5),

(109,
'Replacement Dice Set',
'Replacement Monopoly dice set.',
50,
4.99,
5),

(113,
'Replacement Token Set',
'Replacement player token collection.',
50,
9.99,
5),

(357,
'Monopoly Evil Edition',
'A special Monopoly edition with competitive gameplay.',
100,
67.67,
2);

-- --------------------------------------------------------
-- Indexes for tables
-- --------------------------------------------------------

-- Categories Primary Key

ALTER TABLE `CATEGORIES`
  ADD PRIMARY KEY (`category_id`);


-- Customers Primary Key and Unique Fields

ALTER TABLE `CUSTOMERS`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `customer_email` (`customer_email`),
  ADD UNIQUE KEY `username` (`username`);


-- Employees Primary Key and Unique Username

ALTER TABLE `EMPLOYEES`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `employee_username` (`employee_username`);


-- Information Pages Primary Key

ALTER TABLE `monopoly_info_pages`
  ADD PRIMARY KEY (`info_id`);


-- Orders Primary Key

ALTER TABLE `ORDERS`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);


-- Order Items Primary Key

ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);


-- Products Primary Key

ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_category` (`category_id`);


-- --------------------------------------------------------
-- AUTO_INCREMENT settings
-- --------------------------------------------------------

ALTER TABLE `CATEGORIES`
  MODIFY `category_id`
  int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT=6;


ALTER TABLE `CUSTOMERS`
  MODIFY `customer_id`
  int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT=2;


ALTER TABLE `EMPLOYEES`
  MODIFY `employee_id`
  int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT=2;


ALTER TABLE `monopoly_info_pages`
  MODIFY `info_id`
  int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT=6;


ALTER TABLE `ORDERS`
  MODIFY `order_id`
  int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT=3;


ALTER TABLE `order_items`
  MODIFY `order_item_id`
  int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT=3;

-- --------------------------------------------------------
-- End of Database
-- --------------------------------------------------------

COMMIT;