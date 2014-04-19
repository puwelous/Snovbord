-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	4.1.22-community-nt


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema powporn
--

CREATE DATABASE IF NOT EXISTS powporn;
USE powporn;

--
-- Definition of table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL default '0',
  `ip_address` varchar(45) NOT NULL default '0',
  `user_agent` varchar(120) NOT NULL default '',
  `last_activity` int(10) unsigned NOT NULL default '0',
  `user_data` text NOT NULL,
  PRIMARY KEY  (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ci_sessions`
--

/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
INSERT INTO `ci_sessions` (`session_id`,`ip_address`,`user_agent`,`last_activity`,`user_data`) VALUES 
 ('3577375d21befd7cd3a160bebc22c4d7','127.0.0.1','Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36',1396531770,'');
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;


--
-- Definition of table `pp_cart`
--

DROP TABLE IF EXISTS `pp_cart`;
CREATE TABLE `pp_cart` (
  `c_id` int(10) NOT NULL default '0',
  `c_sum` decimal(5,2) NOT NULL default '0.00',
  `c_status` varchar(16) NOT NULL default '',
  `o_id` int(10) default '0',
  `u_ordering_person_id` int(10) NOT NULL default '0',
  PRIMARY KEY  (`c_id`),
  KEY `FK_pp_cart_order` (`o_id`),
  KEY `FK_pp_cart_ordering_person` (`u_ordering_person_id`),
  CONSTRAINT `FK_pp_cart_order` FOREIGN KEY (`o_id`) REFERENCES `pp_order` (`o_id`),
  CONSTRAINT `FK_pp_cart_ordering_person` FOREIGN KEY (`u_ordering_person_id`) REFERENCES `pp_user` (`u_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pp_cart`
--

/*!40000 ALTER TABLE `pp_cart` DISABLE KEYS */;
INSERT INTO `pp_cart` (`c_id`,`c_sum`,`c_status`,`o_id`,`u_ordering_person_id`) VALUES 
 (0,'370.35','OPEN',NULL,1);
/*!40000 ALTER TABLE `pp_cart` ENABLE KEYS */;


--
-- Definition of table `pp_company`
--

DROP TABLE IF EXISTS `pp_company`;
CREATE TABLE `pp_company` (
  `cmpn_id` int(10) NOT NULL auto_increment,
  `cmpn_provider_firstname` varchar(32) NOT NULL default '',
  `cmpn_provider_lastname` varchar(32) NOT NULL default '',
  `cmpn_provider_street` varchar(64) NOT NULL default '',
  `cmpn_provider_street_number` varchar(8) NOT NULL default '',
  `cmpn_provider_city` varchar(64) NOT NULL default '',
  `cmpn_provider_country` varchar(64) NOT NULL default '',
  `cmpn_provider_email` varchar(64) NOT NULL default '',
  `cmpn_provider_phone_number` varchar(32) NOT NULL default '',
  `cmpn_rules` text NOT NULL,
  PRIMARY KEY  (`cmpn_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pp_company`
--

/*!40000 ALTER TABLE `pp_company` DISABLE KEYS */;
INSERT INTO `pp_company` (`cmpn_id`,`cmpn_provider_firstname`,`cmpn_provider_lastname`,`cmpn_provider_street`,`cmpn_provider_street_number`,`cmpn_provider_city`,`cmpn_provider_country`,`cmpn_provider_email`,`cmpn_provider_phone_number`,`cmpn_rules`) VALUES 
 (1,'roman','juhas','sladkovica hajtkovica','23','075 01 SECOVCE','slovakia','INFO@436.SK','+432 984 500 500','Loreme ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco labo- ris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui ofcia deserunt mollit anim id est laborum.');
/*!40000 ALTER TABLE `pp_company` ENABLE KEYS */;


--
-- Definition of table `pp_contact_video`
--

DROP TABLE IF EXISTS `pp_contact_video`;
CREATE TABLE `pp_contact_video` (
  `cv_id` int(10) NOT NULL auto_increment,
  `cv_url` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`cv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pp_contact_video`
--

/*!40000 ALTER TABLE `pp_contact_video` DISABLE KEYS */;
/*!40000 ALTER TABLE `pp_contact_video` ENABLE KEYS */;


--
-- Definition of table `pp_order`
--

DROP TABLE IF EXISTS `pp_order`;
CREATE TABLE `pp_order` (
  `o_id` int(10) NOT NULL auto_increment,
  `o_cart` int(10) NOT NULL default '0',
  `o_shipping_method` int(10) NOT NULL default '0',
  `o_payment_method` int(10) NOT NULL default '0',
  `o_is_shipping_address_registration_addres` tinyint(4) NOT NULL default '0',
  `o_status` varchar(32) NOT NULL default '',
  `o_final_sum` decimal(5,2) NOT NULL default '0.00',
  PRIMARY KEY  (`o_id`),
  KEY `FK_Reference_7` USING BTREE (`o_cart`),
  KEY `FK_Reference_8` USING BTREE (`o_shipping_method`),
  KEY `FK_Reference_9` USING BTREE (`o_payment_method`),
  CONSTRAINT `FK_Reference_7` FOREIGN KEY (`o_cart`) REFERENCES `pp_cart` (`c_id`),
  CONSTRAINT `FK_Reference_8` FOREIGN KEY (`o_shipping_method`) REFERENCES `pp_shipping_method` (`sm_id`),
  CONSTRAINT `FK_Reference_9` FOREIGN KEY (`o_payment_method`) REFERENCES `pp_payment_method` (`pm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pp_order`
--

/*!40000 ALTER TABLE `pp_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `pp_order` ENABLE KEYS */;


--
-- Definition of table `pp_ordered_product`
--

DROP TABLE IF EXISTS `pp_ordered_product`;
CREATE TABLE `pp_ordered_product` (
  `op_id` int(10) NOT NULL auto_increment,
  `pd_id` int(10) NOT NULL default '0',
  `op_amount` smallint(6) NOT NULL default '0',
  `psfp_name` varchar(16) NOT NULL default '',
  `c_id` int(10) NOT NULL default '0',
  `u_id` int(10) default NULL,
  PRIMARY KEY  (`op_id`),
  KEY `FK_Reference_3` (`pd_id`),
  KEY `FK_Reference_4` (`u_id`),
  KEY `FK_Reference_5` (`c_id`),
  CONSTRAINT `FK_Reference_3` FOREIGN KEY (`pd_id`) REFERENCES `pp_product_definition` (`pd_id`),
  CONSTRAINT `FK_Reference_4` FOREIGN KEY (`u_id`) REFERENCES `pp_user` (`u_id`),
  CONSTRAINT `FK_Reference_5` FOREIGN KEY (`c_id`) REFERENCES `pp_cart` (`c_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pp_ordered_product`
--

/*!40000 ALTER TABLE `pp_ordered_product` DISABLE KEYS */;
INSERT INTO `pp_ordered_product` (`op_id`,`pd_id`,`op_amount`,`psfp_name`,`c_id`,`u_id`) VALUES 
 (1,30,1,'small',0,1),
 (2,30,1,'medium',0,1),
 (3,30,1,'medium',0,1);
/*!40000 ALTER TABLE `pp_ordered_product` ENABLE KEYS */;


--
-- Definition of table `pp_payment_method`
--

DROP TABLE IF EXISTS `pp_payment_method`;
CREATE TABLE `pp_payment_method` (
  `pm_id` int(10) NOT NULL auto_increment,
  `pm_name` varchar(32) NOT NULL default '',
  `pm_cost` decimal(5,2) NOT NULL default '0.00',
  PRIMARY KEY  (`pm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pp_payment_method`
--

/*!40000 ALTER TABLE `pp_payment_method` DISABLE KEYS */;
INSERT INTO `pp_payment_method` (`pm_id`,`pm_name`,`pm_cost`) VALUES 
 (5,'IBANKING','0.00'),
 (6,'CREDIT CARD','1.00');
/*!40000 ALTER TABLE `pp_payment_method` ENABLE KEYS */;


--
-- Definition of table `pp_possible_size_for_product`
--

DROP TABLE IF EXISTS `pp_possible_size_for_product`;
CREATE TABLE `pp_possible_size_for_product` (
  `psfp_id` int(10) NOT NULL auto_increment,
  `pd_id` int(10) NOT NULL default '0',
  `psfp_name` varchar(16) NOT NULL default '',
  `psfp_amount` int(8) NOT NULL default '0',
  PRIMARY KEY  (`psfp_id`),
  KEY `FK_Reference_1` USING BTREE (`pd_id`),
  CONSTRAINT `FK_Reference_1` FOREIGN KEY (`pd_id`) REFERENCES `pp_product_definition` (`pd_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pp_possible_size_for_product`
--

/*!40000 ALTER TABLE `pp_possible_size_for_product` DISABLE KEYS */;
INSERT INTO `pp_possible_size_for_product` (`psfp_id`,`pd_id`,`psfp_name`,`psfp_amount`) VALUES 
 (25,30,'small',1),
 (26,30,'medium',1);
/*!40000 ALTER TABLE `pp_possible_size_for_product` ENABLE KEYS */;


--
-- Definition of table `pp_product_definition`
--

DROP TABLE IF EXISTS `pp_product_definition`;
CREATE TABLE `pp_product_definition` (
  `pd_id` int(10) NOT NULL auto_increment,
  `pd_product_name` varchar(32) NOT NULL default '',
  `pd_photo_url` varchar(128) NOT NULL default '',
  `pd_product_creator` int(10) NOT NULL default '0',
  `pd_type` text NOT NULL,
  `pd_price` decimal(5,2) NOT NULL default '0.00',
  `pd_sex` varchar(8) NOT NULL default '',
  PRIMARY KEY  (`pd_id`),
  KEY `FK_pp_product_definition_creator` (`pd_product_creator`),
  CONSTRAINT `FK_pp_product_definition_creator` FOREIGN KEY (`pd_product_creator`) REFERENCES `pp_user` (`u_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pp_product_definition`
--

/*!40000 ALTER TABLE `pp_product_definition` DISABLE KEYS */;
INSERT INTO `pp_product_definition` (`pd_id`,`pd_product_name`,`pd_photo_url`,`pd_product_creator`,`pd_type`,`pd_price`,`pd_sex`) VALUES 
 (30,'PP K2 Bambi','./assets/images/products/finals/puwel_PP_K2_Bambi_1.jpg',1,'Unbelievably water-proof hoodie made of high-quality material.','123.45','male');
/*!40000 ALTER TABLE `pp_product_definition` ENABLE KEYS */;


--
-- Definition of table `pp_shipping_method`
--

DROP TABLE IF EXISTS `pp_shipping_method`;
CREATE TABLE `pp_shipping_method` (
  `sm_id` int(10) NOT NULL auto_increment,
  `sm_name` varchar(64) NOT NULL default '',
  `sm_price` decimal(5,2) NOT NULL default '0.00',
  PRIMARY KEY  (`sm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pp_shipping_method`
--

/*!40000 ALTER TABLE `pp_shipping_method` DISABLE KEYS */;
INSERT INTO `pp_shipping_method` (`sm_id`,`sm_name`,`sm_price`) VALUES 
 (1,'Standard mail delivery','3.00'),
 (2,'UPC','5.00'),
 (3,'Personal pick up','0.00');
/*!40000 ALTER TABLE `pp_shipping_method` ENABLE KEYS */;


--
-- Definition of table `pp_user`
--

DROP TABLE IF EXISTS `pp_user`;
CREATE TABLE `pp_user` (
  `u_id` int(10) NOT NULL auto_increment,
  `u_firstname` varchar(32) NOT NULL default '',
  `u_lastname` varchar(32) NOT NULL default '',
  `u_email_address` varchar(64) NOT NULL default '',
  `u_password` varchar(32) NOT NULL default '',
  `u_gender` tinyint(4) NOT NULL default '0',
  `u_delivery_address` text,
  `u_address` text NOT NULL,
  `u_city` varchar(64) NOT NULL default '',
  `u_zip` varchar(16) NOT NULL default '',
  `u_country` varchar(64) NOT NULL default '',
  `u_is_admin` tinyint(4) NOT NULL default '0',
  `u_nick` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`u_id`),
  UNIQUE KEY `u_email_address_index` (`u_email_address`),
  UNIQUE KEY `u_nick_index` (`u_nick`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pp_user`
--

/*!40000 ALTER TABLE `pp_user` DISABLE KEYS */;
INSERT INTO `pp_user` (`u_id`,`u_firstname`,`u_lastname`,`u_email_address`,`u_password`,`u_gender`,`u_delivery_address`,`u_address`,`u_city`,`u_zip`,`u_country`,`u_is_admin`,`u_nick`) VALUES 
 (1,'Pavol','Daňo','pavol.dano@gmail.com','c88a03a140ce09099860821c3546baf4',0,'','gen. svobodu 130/17','Medzilaborce','06801','slovakia',1,'puwel'),
 (2,'Pali','Dano','pavol.dano@student.tuke.sk','c88a03a140ce09099860821c3546baf4',0,'jedlikova 9, kosice','jedlikova 9, kosice','Kosice','04001','Slovakia',0,'palko'),
 (3,'Lobo','Gito','lobogito@azet.sk','0412f8641c8a84bc6addf4fa55afbd5d',0,'gen.Svobodu 130/17','gen. svobodu 130/17','Medzilaborce','06801','Slovakia',0,'lobogito');
/*!40000 ALTER TABLE `pp_user` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
