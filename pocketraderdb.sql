drop database if exists pocketraderdb;

/*
SQLyog Community v13.3.0 (64 bit)
MySQL - 10.4.32-MariaDB : Database - pocketraderdb
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`pocketraderdb` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `pocketraderdb`;

/*Table structure for table `card_sets` */

DROP TABLE IF EXISTS `card_sets`;

CREATE TABLE `card_sets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `release_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `total_cards` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `card_sets` */

/*Table structure for table `cards` */

DROP TABLE IF EXISTS `cards`;

CREATE TABLE `cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `card_set_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `card_type` varchar(50) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `rarity` varchar(50) DEFAULT NULL,
  `edition` varchar(50) DEFAULT NULL,
  `estimated_market_price` decimal(10,2) DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `idx_cards_name` (`name`),
  KEY `idx_cards_set` (`card_set_id`),
  CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`card_set_id`) REFERENCES `card_sets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `cards` */

/*Table structure for table `chats` */

DROP TABLE IF EXISTS `chats`;

CREATE TABLE `chats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user1_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user2_id` (`user2_id`),
  KEY `idx_chats_users` (`user1_id`,`user2_id`),
  CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `users` (`id`),
  CONSTRAINT `chats_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `chats` */

/*Table structure for table `listings` */

DROP TABLE IF EXISTS `listings`;

CREATE TABLE `listings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `card_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `condition_text` enum('Mint','Near Mint','Lightly Played','Heavily Played','Damaged') NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `seller_id` (`seller_id`),
  KEY `idx_listings_filter` (`card_id`,`is_active`,`price`),
  CONSTRAINT `listings_ibfk_1` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`),
  CONSTRAINT `listings_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `listings` */

/*Table structure for table `messages` */

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `idx_messages_chat` (`chat_id`,`sent_at`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `messages` */

/*Table structure for table `order_items` */

DROP TABLE IF EXISTS `order_items`;

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `price_at_purchase` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `listing_id` (`listing_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `order_items` */

/*Table structure for table `orders` */

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buyer_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_status` enum('Processing','Shipped','Delivered','Cancelled','Returned') DEFAULT 'Processing',
  `shipping_address` text NOT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `payment_status` enum('Unpaid','Paid','Refunded') DEFAULT 'Paid',
  `paid_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `subtotal` decimal(10,2) NOT NULL,
  `shipping_cost` decimal(10,2) DEFAULT 0.00,
  `platform_fee` decimal(10,2) DEFAULT 0.00,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_orders_buyer` (`buyer_id`),
  KEY `idx_orders_status` (`order_status`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `orders` */

/*Table structure for table `reviews` */

DROP TABLE IF EXISTS `reviews`;

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reviewer_id` int(11) NOT NULL,
  `reviewee_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `rating` tinyint(3) unsigned NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `reviewer_id` (`reviewer_id`),
  KEY `reviewee_id` (`reviewee_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`),
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`reviewee_id`) REFERENCES `users` (`id`),
  CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `reviews` */

/*Table structure for table `user_collections` */

DROP TABLE IF EXISTS `user_collections`;

CREATE TABLE `user_collections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  `condition_text` enum('Mint','Near Mint','Lightly Played','Heavily Played','Damaged') DEFAULT NULL,
  `is_for_trade` tinyint(1) DEFAULT 0,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `card_id` (`card_id`),
  CONSTRAINT `user_collections_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_collections_ibfk_2` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `user_collections` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `identity_type` enum('KTP','SIM','Passport') DEFAULT NULL,
  `identity_number` varchar(50) DEFAULT NULL,
  `identity_image_url` varchar(255) DEFAULT NULL,
  `identity_status` enum('unverified','pending','verified','rejected') DEFAULT 'unverified',
  `otp_code` varchar(10) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `account_status` enum('active','suspended','banned', 'verify') DEFAULT 'verify',
  `ban_reason` varchar(255) DEFAULT NULL,
  `banned_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `last_online` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_users_username` (`username`),
  UNIQUE KEY `idx_users_email` (`email`),
  UNIQUE KEY `idx_users_identity` (`identity_number`),
  KEY `idx_users_status` (`account_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `users` */

/*Table structure for table `wallet_transactions` */

DROP TABLE IF EXISTS `wallet_transactions`;

CREATE TABLE `wallet_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `reference_order_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_type` enum('TOPUP','PURCHASE','SALES_REVENUE','WITHDRAWAL','REFUND') NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_wallet_user` (`user_id`,`created_at`),
  KEY `fk_wallet_order` (`reference_order_id`),
  CONSTRAINT `fk_wallet_order` FOREIGN KEY (`reference_order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `wallet_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `wallet_transactions` */

/*Table structure for table `wishlists` */

DROP TABLE IF EXISTS `wishlists`;

CREATE TABLE `wishlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_wishlist` (`user_id`,`card_id`),
  KEY `card_id` (`card_id`),
  CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlists_ibfk_2` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `wishlists` */

-- ------------------------------------------------------
-- Dummy data inserts (FK-safe order)
-- ------------------------------------------------------
START TRANSACTION;

-- card_sets
INSERT INTO card_sets (id, name, release_date, description, image_url, total_cards) VALUES
  (1, 'Base Set', '2020-01-01', 'Original base set', 'https://img.example.com/sets/base.jpg', 102),
  (2, 'Jungle', '2020-06-01', 'Jungle expansion', 'https://img.example.com/sets/jungle.jpg', 64);

-- users
INSERT INTO users (
  id, name, username, password_hash, email, phone_number, balance,
  identity_type, identity_number, identity_image_url, identity_status,
  otp_code, otp_expires_at, account_status, ban_reason, banned_at, is_admin,
  last_online, created_at
) VALUES
  (1, 'Benny Example', 'benny', '$2y$10$examplehash1', 'benny@example.com', '+62111111111', 100.00,
   'KTP', 'KTP-001', 'https://img.example.com/id/ktp-001.jpg', 'verified',
   NULL, NULL, 'active', NULL, NULL, 1,
   '2025-12-01 10:00:00', '2025-11-01 09:00:00'),
  (2, 'Alice Seller', 'alice', '$2y$10$examplehash2', 'alice@example.com', '+62122222222', 50.00,
   'SIM', 'SIM-002', 'https://img.example.com/id/sim-002.jpg', 'verified',
   NULL, NULL, 'active', NULL, NULL, 0,
   '2025-12-02 11:00:00', '2025-11-02 09:00:00'),
  (3, 'Bob Seller', 'bob', '$2y$10$examplehash3', 'bob@example.com', '+62133333333', 75.00,
   'Passport', 'PASS-003', 'https://img.example.com/id/pass-003.jpg', 'pending',
   NULL, NULL, 'active', NULL, NULL, 0,
   '2025-12-03 12:00:00', '2025-11-03 09:00:00'),
  (4, 'Carla Buyer', 'carla', '$2y$10$examplehash4', 'carla@example.com', '+62144444444', 200.00,
   NULL, NULL, NULL, 'unverified',
   NULL, NULL, 'active', NULL, NULL, 0,
   '2025-12-04 13:00:00', '2025-11-04 09:00:00'),
  (5, 'Derek Buyer', 'derek', '$2y$10$examplehash5', 'derek@example.com', '+62155555555', 150.00,
   NULL, NULL, NULL, 'unverified',
   NULL, NULL, 'active', NULL, NULL, 0,
   '2025-12-05 14:00:00', '2025-11-05 09:00:00');

-- cards
INSERT INTO cards (id, card_set_id, name, card_type, image_url, rarity, edition, estimated_market_price) VALUES
  (1, 1, 'Charizard', 'Monster', 'https://img.example.com/cards/charizard.jpg', 'Rare', 'First', 300.00),
  (2, 1, 'Blastoise', 'Monster', 'https://img.example.com/cards/blastoise.jpg', 'Rare', 'Unlimited', 200.00),
  (3, 1, 'Venusaur', 'Monster', 'https://img.example.com/cards/venusaur.jpg', 'Rare', 'Unlimited', 180.00),
  (4, 2, 'Pikachu', 'Monster', 'https://img.example.com/cards/pikachu.jpg', 'Common', 'Unlimited', 25.00),
  (5, 2, 'Eevee', 'Monster', 'https://img.example.com/cards/eevee.jpg', 'Uncommon', 'Unlimited', 40.00);

-- listings
INSERT INTO listings (id, card_id, seller_id, price, condition_text, description, quantity, is_active, created_at) VALUES
  (1, 4, 2, 30.00, 'Near Mint', 'Pikachu NM', 2, 1, '2025-12-10 09:00:00'),
  (2, 5, 2, 45.00, 'Lightly Played', 'Eevee LP', 1, 1, '2025-12-10 10:00:00'),
  (3, 2, 3, 210.00, 'Mint', 'Blastoise Mint', 1, 1, '2025-12-11 11:00:00'),
  (4, 3, 3, 175.00, 'Heavily Played', 'Venusaur HP', 1, 0, '2025-12-11 12:00:00');

-- chats
INSERT INTO chats (id, user1_id, user2_id, created_at) VALUES
  (1, 1, 2, '2025-12-12 09:00:00'),
  (2, 2, 3, '2025-12-12 09:30:00');

-- messages
INSERT INTO messages (id, chat_id, sender_id, content, sent_at) VALUES
  (1, 1, 1, 'Hi Alice, interested in Pikachu.', '2025-12-12 09:05:00'),
  (2, 1, 2, 'Sure, I have two near mint.', '2025-12-12 09:06:00'),
  (3, 2, 2, 'Bob, can you check my listing?', '2025-12-12 09:35:00'),
  (4, 2, 3, 'Looks good. Price is fair.', '2025-12-12 09:36:00');

-- orders
INSERT INTO orders (id, buyer_id, order_date, order_status, shipping_address, tracking_number, payment_status, paid_at, subtotal, shipping_cost, platform_fee, tax_amount, total_amount) VALUES
  (1, 4, '2025-12-13 10:00:00', 'Processing', 'Jl. Contoh No.1, Jakarta', 'TRK-1001', 'Paid', '2025-12-13 10:05:00', 30.00, 5.00, 1.50, 3.00, 39.50),
  (2, 5, '2025-12-14 11:00:00', 'Shipped', 'Jl. Contoh No.2, Bandung', 'TRK-1002', 'Paid', '2025-12-14 11:10:00', 45.00, 6.00, 2.25, 4.50, 57.75),
  (3, 4, '2025-12-15 12:00:00', 'Cancelled', 'Jl. Contoh No.1, Jakarta', NULL, 'Refunded', '2025-12-15 12:10:00', 210.00, 0.00, 10.50, 0.00, 220.50);

-- order_items
INSERT INTO order_items (id, order_id, listing_id, quantity, price_at_purchase) VALUES
  (1, 1, 1, 1, 30.00),
  (2, 2, 2, 1, 45.00),
  (3, 3, 3, 1, 210.00);

-- reviews
INSERT INTO reviews (id, reviewer_id, reviewee_id, order_id, rating, comment, created_at) VALUES
  (1, 4, 2, 1, 5, 'Great seller, fast shipping!', '2025-12-16 09:00:00'),
  (2, 5, 2, 2, 4, 'Item as described.', '2025-12-17 10:00:00');

-- user_collections
INSERT INTO user_collections (id, user_id, card_id, condition_text, is_for_trade, added_at) VALUES
  (1, 1, 1, 'Lightly Played', 0, '2025-12-10 08:00:00'),
  (2, 2, 2, 'Mint', 1, '2025-12-10 08:30:00'),
  (3, 3, 5, 'Near Mint', 0, '2025-12-10 09:00:00');

-- wallet_transactions
INSERT INTO wallet_transactions (id, user_id, reference_order_id, amount, transaction_type, description, created_at) VALUES
  (1, 4, NULL, 100.00, 'TOPUP', 'Initial topup', '2025-12-12 08:00:00'),
  (2, 4, 1, -39.50, 'PURCHASE', 'Order #1 payment', '2025-12-13 10:05:00'),
  (3, 2, 1, 30.00, 'SALES_REVENUE', 'Order #1 revenue', '2025-12-13 10:06:00'),
  (4, 5, 2, -57.75, 'PURCHASE', 'Order #2 payment', '2025-12-14 11:10:00'),
  (5, 2, 2, 45.00, 'SALES_REVENUE', 'Order #2 revenue', '2025-12-14 11:11:00'),
  (6, 4, 3, 220.50, 'REFUND', 'Order #3 refund', '2025-12-15 12:15:00');

-- wishlists (unique user_id + card_id)
INSERT INTO wishlists (id, user_id, card_id, added_at) VALUES
  (1, 3, 5, '2025-12-12 07:00:00'),
  (2, 5, 1, '2025-12-12 07:30:00');

COMMIT;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
