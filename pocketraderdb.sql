/*
SQLyog Community v13.3.1 (64 bit)
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `card_sets` */

insert  into `card_sets`(`id`,`name`,`release_date`,`description`,`image_url`,`total_cards`) values 
(1,'Genetic Apex','2024-10-30','The first main expansion heavily focused on Generation 1 Pokémon (Kanto).','images/PokemonCards/A1_Genetic_Apex/A1.webp',286),
(2,'Mythical Island','2024-12-17','A mini-expansion focusing on Mythical Pokémon.','images/PokemonCards/A1a_Mythical_Island/A1a.webp',86),
(3,'Mega Rising','2025-10-30','The debut of the B-Series, introducing Mega Evolution.','images/PokemonCards/B1_Mega_Rising/B1.webp',331),
(4,'Crimson Blaze','2025-12-17','A high-powered mini-set headlined by Mega Charizard Y.','images/PokemonCards/B1a_Crimson_Blaze/B1a.webp',103);

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
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `cards` */

insert  into `cards`(`id`,`card_set_id`,`name`,`card_type`,`image_url`,`rarity`,`edition`,`estimated_market_price`) values 
(1,1,'Bulbasaur','Grass','images/PokemonCards/A1_Genetic_Apex/A1_001_EN_SM.webp','1-Diamond','Normal',0.10),
(2,1,'Ivysaur','Grass','images/PokemonCards/A1_Genetic_Apex/A1_002_EN_SM.webp','2-Diamond','Normal',0.25),
(3,1,'Venusaur EX','Grass','images/PokemonCards/A1_Genetic_Apex/A1_003_EN_SM.webp','4-Diamond','EX',15.00),
(4,1,'Charmander','Fire','images/PokemonCards/A1_Genetic_Apex/A1_010_EN_SM.webp','1-Diamond','Normal',0.10),
(5,1,'Charmeleon','Fire','images/PokemonCards/A1_Genetic_Apex/A1_011_EN_SM.webp','2-Diamond','Normal',0.25),
(6,1,'Charizard EX','Fire','images/PokemonCards/A1_Genetic_Apex/A1_012_EN_SM.webp','4-Diamond','EX',20.00),
(7,1,'Squirtle','Water','images/PokemonCards/A1_Genetic_Apex/A1_020_EN_SM.webp','1-Diamond','Normal',0.10),
(8,1,'Wartortle','Water','images/PokemonCards/A1_Genetic_Apex/A1_021_EN_SM.webp','2-Diamond','Normal',0.25),
(9,1,'Blastoise EX','Water','images/PokemonCards/A1_Genetic_Apex/A1_022_EN_SM.webp','4-Diamond','EX',18.00),
(10,1,'Pikachu EX','Lightning','images/PokemonCards/A1_Genetic_Apex/A1_035_EN_SM.webp','4-Diamond','EX',25.00),
(11,1,'Mewtwo EX','Psychic','images/PokemonCards/A1_Genetic_Apex/A1_050_EN_SM.webp','4-Diamond','EX',30.00),
(12,1,'Gardevoir','Psychic','images/PokemonCards/A1_Genetic_Apex/A1_080_EN_SM.webp','3-Diamond','Normal',5.50),
(13,1,'Greninja','Water','images/PokemonCards/A1_Genetic_Apex/A1_120_EN_SM.webp','3-Diamond','Normal',4.00),
(14,1,'Weezing','Darkness','images/PokemonCards/A1_Genetic_Apex/A1_150_EN_SM.webp','2-Diamond','Normal',1.50),
(15,1,'Alakazam','Psychic','images/PokemonCards/A1_Genetic_Apex/A1_236_EN_SM.webp','1-Star','Illustration Rare',12.00),
(16,1,'Machamp EX','Fighting','images/PokemonCards/A1_Genetic_Apex/A1_278_EN_SM.webp','2-Star','Full Art EX',45.00),
(17,1,'Mewtwo EX','Psychic','images/PokemonCards/A1_Genetic_Apex/A1_282_EN_SM.webp','3-Star','Immersive Rare',85.00),
(18,1,'Pikachu EX','Lightning','images/PokemonCards/A1_Genetic_Apex/A1_285_EN_SM.webp','Crown','Crown Rare',350.00),
(19,2,'Exeggcute','Grass','images/PokemonCards/A1a_Mythical_Island/A1a_001_EN_SM.webp','1-Diamond','Normal',0.15),
(20,2,'Snivy','Grass','images/PokemonCards/A1a_Mythical_Island/A1a_004_EN_SM.webp','1-Diamond','Normal',0.15),
(21,2,'Serperior','Grass','images/PokemonCards/A1a_Mythical_Island/A1a_006_EN_SM.webp','3-Diamond','Normal',3.50),
(22,2,'Celebi EX','Grass','images/PokemonCards/A1a_Mythical_Island/A1a_003_EN_SM.webp','4-Diamond','EX',12.00),
(23,2,'Magikarp','Water','images/PokemonCards/A1a_Mythical_Island/A1a_017_EN_SM.webp','1-Diamond','Normal',0.10),
(24,2,'Gyarados EX','Water','images/PokemonCards/A1a_Mythical_Island/A1a_018_EN_SM.webp','4-Diamond','EX',14.50),
(25,2,'Mew EX','Psychic','images/PokemonCards/A1a_Mythical_Island/A1a_032_EN_SM.webp','4-Diamond','EX',22.00),
(26,2,'Pidgeot EX','Colorless','images/PokemonCards/A1a_Mythical_Island/A1a_059_EN_SM.webp','4-Diamond','EX',11.00),
(27,2,'Druddigon','Dragon','images/PokemonCards/A1a_Mythical_Island/A1a_056_EN_SM.webp','2-Diamond','Normal',2.50),
(28,2,'Leaf','Trainer','images/PokemonCards/A1a_Mythical_Island/A1a_068_EN_SM.webp','2-Diamond','Supporter',1.50),
(29,2,'Exeggutor','Grass','images/PokemonCards/A1a_Mythical_Island/A1a_069_EN_SM.webp','1-Star','Illustration Rare',10.00),
(30,2,'Aerodactyl EX','Fighting','images/PokemonCards/A1a_Mythical_Island/A1a_078_EN_SM.webp','2-Star','Full Art EX',42.00),
(31,2,'Celebi EX','Grass','images/PokemonCards/A1a_Mythical_Island/A1a_085_EN_SM.webp','3-Star','Immersive Rare',75.00),
(32,2,'Mew EX','Psychic','images/PokemonCards/A1a_Mythical_Island/A1a_086_EN_SM.webp','Crown','Crown Rare',450.00),
(33,3,'Pinsir','Grass','images/PokemonCards/B1_Mega_Rising/B1_001_EN_SM.webp','1-Diamond','Normal',0.10),
(34,3,'Mega Pinsir EX','Grass','images/PokemonCards/B1_Mega_Rising/B1_002_EN_SM.webp','4-Diamond','Mega EX',13.00),
(35,3,'Wurmple','Grass','images/PokemonCards/B1_Mega_Rising/B1_003_EN_SM.webp','1-Diamond','Normal',0.10),
(36,3,'Torchic','Fire','images/PokemonCards/B1_Mega_Rising/B1_033_EN_SM.webp','1-Diamond','Normal',0.15),
(37,3,'Mega Blaziken EX','Fire','images/PokemonCards/B1_Mega_Rising/B1_036_EN_SM.webp','4-Diamond','Mega EX',19.50),
(38,3,'Mega Gyarados EX','Water','images/PokemonCards/B1_Mega_Rising/B1_052_EN_SM.webp','4-Diamond','Mega EX',17.00),
(39,3,'Mareep','Lightning','images/PokemonCards/B1_Mega_Rising/B1_082_EN_SM.webp','1-Diamond','Normal',0.15),
(40,3,'Altaria EX','Colorless','images/PokemonCards/B1_Mega_Rising/B1_150_EN_SM.webp','4-Diamond','EX',10.00),
(41,3,'Mega Altaria EX','Colorless','images/PokemonCards/B1_Mega_Rising/B1_151_EN_SM.webp','4-Diamond','Mega EX',16.00),
(42,3,'Lisia','Trainer','images/PokemonCards/B1_Mega_Rising/B1_250_EN_SM.webp','2-Diamond','Supporter',4.00),
(43,3,'Rare Candy','Trainer','images/PokemonCards/B1_Mega_Rising/B1_300_EN_SM.webp','2-Diamond','Item',5.50),
(44,3,'Beautifly','Grass','images/PokemonCards/B1_Mega_Rising/B1_227_EN_SM.webp','1-Star','Illustration Rare',11.00),
(45,3,'Greninja EX','Water','images/PokemonCards/B1_Mega_Rising/B1_275_EN_SM.webp','2-Star','Rainbow Rare',55.00),
(46,3,'Mega Altaria EX','Psychic','images/PokemonCards/B1_Mega_Rising/B1_289_EN_SM.webp','3-Star','Immersive Rare',80.00),
(47,3,'Moltres','Fire','images/PokemonCards/B1_Mega_Rising/B1_301_EN_SM.webp','1-ShinyStar','Shiny Rare',22.00),
(48,3,'Dialga EX','Metal','images/PokemonCards/B1_Mega_Rising/B1_324_EN_SM.webp','2-ShinyStar','Shiny Rare EX',65.00),
(49,3,'Klefki','Psychic','images/PokemonCards/B1_Mega_Rising/B1_330_EN_SM.webp','Crown','Hyper Rare',250.00),
(50,4,'Bulbasaur','Grass','images/PokemonCards/B1a_Crimson_Blaze/B1a_001_EN_SM.webp','1-Diamond','Normal',0.20),
(51,4,'Mega Venusaur EX','Grass','images/PokemonCards/B1a_Crimson_Blaze/B1a_004_EN_SM.webp','4-Diamond','Mega EX',22.00),
(52,4,'Charmander','Fire','images/PokemonCards/B1a_Crimson_Blaze/B1a_011_EN_SM.webp','1-Diamond','Normal',0.20),
(53,4,'Mega Charizard Y EX','Fire','images/PokemonCards/B1a_Crimson_Blaze/B1a_014_EN_SM.webp','4-Diamond','Mega EX',35.00),
(54,4,'Squirtle','Water','images/PokemonCards/B1a_Crimson_Blaze/B1a_017_EN_SM.webp','1-Diamond','Normal',0.20),
(55,4,'Mega Blastoise EX','Water','images/PokemonCards/B1a_Crimson_Blaze/B1a_020_EN_SM.webp','4-Diamond','Mega EX',24.00),
(56,4,'Serena','Trainer','images/PokemonCards/B1a_Crimson_Blaze/B1a_069_EN_SM.webp','2-Diamond','Supporter',3.00),
(57,4,'Quick-Grow Extract','Trainer','images/PokemonCards/B1a_Crimson_Blaze/B1a_067_EN_SM.webp','2-Diamond','Item',4.50),
(58,4,'Ditto','Colorless','images/PokemonCards/B1a_Crimson_Blaze/B1a_055_EN_SM.webp','2-Diamond','Normal',8.00),
(59,4,'Sunflora','Grass','images/PokemonCards/B1a_Crimson_Blaze/B1a_008_EN_SM.webp','2-Diamond','Normal',1.25),
(60,4,'Ariados','Grass','images/PokemonCards/B1a_Crimson_Blaze/B1a_070_EN_SM.webp','1-Star','Illustration Rare',10.00),
(61,4,'Mega Steelix EX','Metal','images/PokemonCards/B1a_Crimson_Blaze/B1a_080_EN_SM.webp','2-Star','Full Art EX',48.00),
(62,4,'Mega Charizard Y EX','Fire','images/PokemonCards/B1a_Crimson_Blaze/B1a_087_EN_SM.webp','3-Star','Immersive Rare',95.00),
(63,4,'Shellder','Water','images/PokemonCards/B1a_Crimson_Blaze/B1a_091_EN_SM.webp','1-ShinyStar','Shiny Rare',28.00),
(64,4,'Solgaleo EX','Metal','images/PokemonCards/B1a_Crimson_Blaze/B1a_101_EN_SM.webp','2-ShinyStar','Shiny Super Rare',70.00),
(65,4,'Aegislash','Metal','images/PokemonCards/B1a_Crimson_Blaze/B1a_102_EN_SM.webp','Crown','Crown Rare',300.00);

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `chats` */

insert  into `chats`(`id`,`user1_id`,`user2_id`,`created_at`) values 
(1,1,2,'2025-12-12 09:00:00'),
(2,2,3,'2025-12-12 09:30:00'),
(3,1,3,'2026-01-07 08:39:26'),
(4,1,4,'2026-01-07 08:48:17'),
(5,1,5,'2026-01-07 08:52:14');

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
  `user_collection_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `seller_id` (`seller_id`),
  KEY `idx_listings_filter` (`card_id`,`is_active`,`price`),
  CONSTRAINT `listings_ibfk_1` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`),
  CONSTRAINT `listings_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `listings` */

insert  into `listings`(`id`,`card_id`,`seller_id`,`price`,`condition_text`,`description`,`quantity`,`is_active`,`created_at`,`user_collection_id`) values 
(1,4,2,30.00,'Near Mint','Pikachu NM',0,0,'2026-01-09 02:04:35',NULL),
(2,5,2,45.00,'Lightly Played','Eevee LP',1,1,'2026-01-09 02:04:35',NULL),
(3,2,3,210.00,'Mint','Blastoise Mint',1,1,'2026-01-09 02:04:35',NULL),
(4,3,3,175.00,'Heavily Played','Venusaur HP',1,0,'2026-01-09 02:04:35',NULL),
(5,1,3,2.00,'Lightly Played','',0,0,'2026-01-09 02:04:35',NULL),
(6,1,3,3.00,'Mint','',0,0,'2026-01-09 02:04:35',NULL),
(7,1,2,1.00,'Lightly Played','mantap',0,0,'2026-01-09 02:04:35',NULL),
(8,1,3,5.00,'Lightly Played','',1,1,'2026-01-09 02:32:17',NULL),
(9,1,3,6.00,'Lightly Played','',0,0,'2026-01-09 02:33:52',NULL),
(10,1,3,0.03,'Lightly Played','',1,1,'2026-01-09 02:36:13',NULL),
(11,1,3,0.01,'Lightly Played','',0,0,'2026-01-09 02:37:02',NULL),
(12,1,3,0.02,'Lightly Played','',1,1,'2026-01-09 02:39:17',NULL),
(13,1,3,0.01,'Lightly Played','',0,0,'2026-01-09 02:42:43',27),
(14,6,1,0.03,'Mint','',0,0,'2026-01-09 02:45:50',NULL),
(15,6,1,0.04,'Mint','',0,0,'2026-01-09 02:46:04',NULL),
(16,6,3,5.00,'Mint','',0,0,'2026-01-09 02:46:50',32),
(17,17,3,93.00,'Mint','',0,0,'2026-01-09 02:49:38',NULL),
(18,1,3,5.00,'Lightly Played','',1,1,'2026-01-09 02:54:21',30),
(19,1,3,5.00,'Lightly Played','',0,0,'2026-01-09 02:54:28',29);

/*Table structure for table `messages` */

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `read` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `idx_messages_chat` (`chat_id`,`sent_at`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `messages` */

insert  into `messages`(`id`,`chat_id`,`sender_id`,`content`,`sent_at`,`read`) values 
(1,1,1,'Hi Alice, interested in Pikachu.','2025-12-12 09:05:00',0),
(2,1,2,'Sure, I have two near mint.','2025-12-12 09:06:00',1),
(5,3,1,'oi nigha','2026-01-07 08:39:26',1);

/*Table structure for table `notifications` */

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `action_url` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_index` (`user_id`),
  KEY `notifications_is_read_index` (`is_read`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `notifications` */

insert  into `notifications`(`id`,`user_id`,`type`,`title`,`message`,`action_url`,`is_read`,`read_at`,`created_at`,`updated_at`) values 
(1,1,'order','Order Placed Successfully','Your item #12345 has been purchased.','/items/12345',1,'2026-01-07 08:49:18','2026-01-07 15:36:31','2026-01-07 08:49:18');

/*Table structure for table `order_items` */

DROP TABLE IF EXISTS `order_items`;

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `listing_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `price_at_purchase` decimal(10,2) NOT NULL,
  `purchased_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `listing_id` (`listing_id`),
  KEY `buyer_id` (`buyer_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`),
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `order_items` */

insert  into `order_items`(`id`,`listing_id`,`buyer_id`,`quantity`,`price_at_purchase`,`purchased_at`) values 
(1,1,1,1,30.00,'2026-01-09 01:14:38'),
(2,2,1,1,45.00,'2026-01-09 01:14:38'),
(3,3,1,1,210.00,'2026-01-09 01:14:38'),
(4,1,1,1,30.00,'2026-01-09 01:14:38'),
(5,1,1,1,30.00,'2026-01-09 01:14:38'),
(7,7,3,1,1.00,'2026-01-09 02:07:20'),
(8,7,3,1,1.00,'2026-01-09 02:10:43'),
(9,7,3,1,1.00,'2026-01-09 02:11:58'),
(10,7,3,1,1.00,'2026-01-09 02:16:43'),
(11,7,3,1,1.00,'2026-01-09 02:20:04'),
(12,7,3,1,1.00,'2026-01-09 02:20:36'),
(13,15,3,1,0.04,'2026-01-09 02:46:31'),
(14,16,1,1,5.00,'2026-01-09 02:47:26'),
(15,17,1,1,93.00,'2026-01-09 02:50:11'),
(16,17,1,1,93.00,'2026-01-09 02:52:48'),
(17,9,1,1,6.00,'2026-01-09 02:55:17'),
(18,11,1,1,0.01,'2026-01-09 03:00:17'),
(19,13,1,1,0.01,'2026-01-09 03:01:18'),
(20,19,1,1,5.00,'2026-01-09 03:02:12');

/*Table structure for table `reviews` */

DROP TABLE IF EXISTS `reviews`;

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reviewer_id` int(11) NOT NULL,
  `reviewee_id` int(11) NOT NULL,
  `order_item_id` int(11) NOT NULL,
  `rating` tinyint(3) unsigned NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `reviewer_id` (`reviewer_id`),
  KEY `reviewee_id` (`reviewee_id`),
  KEY `order_item_id` (`order_item_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`),
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`reviewee_id`) REFERENCES `users` (`id`),
  CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `reviews` */

insert  into `reviews`(`id`,`reviewer_id`,`reviewee_id`,`order_item_id`,`rating`,`comment`,`created_at`) values 
(1,4,2,1,5,'Great seller, fast shipping!','2025-12-16 09:00:00'),
(2,5,2,2,4,'Item as described.','2025-12-17 10:00:00');

/*Table structure for table `user_collections` */

DROP TABLE IF EXISTS `user_collections`;

CREATE TABLE `user_collections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  `condition_text` enum('Mint','Near Mint','Lightly Played','Heavily Played','Damaged') DEFAULT NULL,
  `is_for_trade` tinyint(1) DEFAULT 0,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_listed` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `card_id` (`card_id`),
  CONSTRAINT `user_collections_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_collections_ibfk_2` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `user_collections` */

insert  into `user_collections`(`id`,`user_id`,`card_id`,`condition_text`,`is_for_trade`,`added_at`,`is_listed`) values 
(1,1,1,'Lightly Played',0,'2025-12-10 08:00:00',0),
(2,2,2,'Mint',1,'2025-12-10 08:30:00',0),
(26,3,1,'Lightly Played',0,'2026-01-08 19:07:20',0),
(27,1,1,'Lightly Played',0,'2026-01-08 20:01:18',0),
(28,3,1,'Lightly Played',1,'2026-01-08 19:11:58',0),
(29,1,1,'Lightly Played',0,'2026-01-08 20:02:12',0),
(30,3,1,'Lightly Played',1,'2026-01-08 19:20:04',1),
(31,3,1,'Lightly Played',1,'2026-01-08 19:20:36',1),
(32,1,6,'Mint',0,'2026-01-08 19:47:26',0),
(33,1,17,'Mint',0,'2026-01-08 19:50:11',0),
(34,1,17,'Mint',0,'2026-01-08 19:52:48',0),
(35,1,1,'Lightly Played',0,'2026-01-08 19:55:17',0),
(36,1,1,'Lightly Played',0,'2026-01-08 20:00:17',0);

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
  `identity_card_url` varchar(255) DEFAULT NULL,
  `identity_status` enum('unverified','pending','verified','rejected') DEFAULT 'unverified',
  `otp_code` varchar(10) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `account_status` enum('active','suspended','banned','verify') DEFAULT 'verify',
  `ban_reason` varchar(255) DEFAULT NULL,
  `banned_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `last_online` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_users_username` (`username`),
  UNIQUE KEY `idx_users_email` (`email`),
  UNIQUE KEY `idx_users_identity` (`identity_number`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`username`,`password_hash`,`email`,`phone_number`,`balance`,`identity_type`,`identity_number`,`identity_image_url`,`identity_card_url`,`identity_status`,`otp_code`,`otp_expires_at`,`account_status`,`ban_reason`,`banned_at`,`is_admin`,`last_online`,`created_at`) values 
(1,'Benny Example','benny','$2y$10$kwFPqfciiYDn7gFiqiSHz.wXMyECv4u67Zd2Ycs5n.zysmv5Cgt1C','benny@example.com',NULL,9895.98,NULL,NULL,NULL,NULL,'verified',NULL,NULL,'active',NULL,NULL,1,'2026-01-09 02:04:34','2026-01-09 02:04:34'),
(2,'Alice Seller','alice','$2y$10$kwFPqfciiYDn7gFiqiSHz.wXMyECv4u67Zd2Ycs5n.zysmv5Cgt1C','alice@example.com',NULL,50.00,NULL,NULL,NULL,NULL,'verified',NULL,NULL,'active',NULL,NULL,0,'2026-01-09 02:04:34','2026-01-09 02:04:34'),
(3,'Bob Seller','bob','$2y$10$kwFPqfciiYDn7gFiqiSHz.wXMyECv4u67Zd2Ycs5n.zysmv5Cgt1C','bob@example.com',NULL,13.97,NULL,NULL,NULL,NULL,'rejected',NULL,NULL,'active',NULL,NULL,0,'2026-01-09 02:04:34','2026-01-09 02:04:34'),
(4,'Carla Buyer','carla','$2y$10$kwFPqfciiYDn7gFiqiSHz.wXMyECv4u67Zd2Ycs5n.zysmv5Cgt1C','carla@example.com',NULL,200.00,NULL,NULL,NULL,NULL,'unverified',NULL,NULL,'active',NULL,NULL,0,'2026-01-09 02:04:34','2026-01-09 02:04:34'),
(5,'Derek Buyer','derek','$2y$10$kwFPqfciiYDn7gFiqiSHz.wXMyECv4u67Zd2Ycs5n.zysmv5Cgt1C','derek@example.com',NULL,150.00,NULL,NULL,NULL,NULL,'unverified',NULL,NULL,'active',NULL,NULL,0,'2026-01-09 02:04:34','2026-01-09 02:04:34');

/*Table structure for table `wallet_transactions` */

DROP TABLE IF EXISTS `wallet_transactions`;

CREATE TABLE `wallet_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `reference_order_item_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_type` enum('TOPUP','PURCHASE','SALES_REVENUE','WITHDRAWAL','REFUND') NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_wallet_user` (`user_id`,`created_at`),
  CONSTRAINT `wallet_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `wallet_transactions` */

insert  into `wallet_transactions`(`id`,`user_id`,`reference_order_item_id`,`amount`,`transaction_type`,`description`,`created_at`) values 
(1,4,NULL,100.00,'TOPUP','Initial topup','2025-12-12 08:00:00'),
(2,4,1,-39.50,'PURCHASE','Item #1 payment','2025-12-13 10:05:00'),
(3,2,1,30.00,'SALES_REVENUE','Item #1 revenue','2025-12-13 10:06:00'),
(4,5,2,-57.75,'PURCHASE','Item #2 payment','2025-12-14 11:10:00'),
(5,2,2,45.00,'SALES_REVENUE','Item #2 revenue','2025-12-14 11:11:00'),
(6,4,3,220.50,'REFUND','Item #3 refund','2025-12-15 12:15:00'),
(7,3,7,-1.00,'PURCHASE','Purchase of 1 card(s) from listing #7','2026-01-08 19:07:20'),
(8,3,8,-1.00,'PURCHASE','Purchase of 1 card(s) from listing #7','2026-01-09 02:10:43'),
(9,3,9,-1.00,'PURCHASE','Purchase of 1 card(s) from listing #7','2026-01-09 02:11:58'),
(10,3,10,-1.00,'PURCHASE','Purchase of 1 card(s) from listing #7','2026-01-09 02:16:43'),
(11,3,11,-1.00,'PURCHASE','Purchase of 1 card(s) from listing #7','2026-01-09 02:20:04'),
(12,3,12,-1.00,'PURCHASE','Purchase of 1 card(s) from listing #7','2026-01-09 02:20:36'),
(13,3,13,-0.04,'PURCHASE','Purchase of 1 card(s) from listing #15','2026-01-09 02:46:31'),
(14,1,14,-5.00,'PURCHASE','Purchase of 1 card(s) from listing #16','2026-01-09 02:47:26'),
(15,1,15,-93.00,'PURCHASE','Purchase of 1 card(s) from listing #17','2026-01-09 02:50:11'),
(16,1,16,-93.00,'PURCHASE','Purchase of 1 card(s) from listing #17','2026-01-09 02:52:48'),
(17,1,17,-6.00,'PURCHASE','Purchase of 1 card(s) from listing #9','2026-01-09 02:55:17'),
(18,1,18,-0.01,'PURCHASE','Purchase of 1 card(s) from listing #11','2026-01-09 03:00:17'),
(19,1,19,-0.01,'PURCHASE','Purchase of 1 card(s) from listing #13','2026-01-09 03:01:18'),
(20,3,19,0.01,'SALES_REVENUE','Sale of 1 card(s) from listing #13','2026-01-09 03:01:18'),
(21,1,20,-5.00,'PURCHASE','Purchase of 1 card(s) from listing #19','2026-01-09 03:02:12'),
(22,3,20,5.00,'SALES_REVENUE','Sale of 1 card(s) from listing #19','2026-01-09 03:02:12');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `wishlists` */

insert  into `wishlists`(`id`,`user_id`,`card_id`,`added_at`) values 
(2,5,1,'2025-12-12 07:30:00');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
