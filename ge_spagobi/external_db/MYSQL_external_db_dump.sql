-- MySQL dump 10.13  Distrib 5.6.26, for osx10.10 (x86_64)
--
-- Host: localhost    Database: spago_ext_db
-- ------------------------------------------------------
-- Server version	5.6.26

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `new_users_daily`
--


CREATE DATABASE spagobi;
USE spagobi;

DROP TABLE IF EXISTS `new_users_daily`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_users_daily` (
  `time` date DEFAULT NULL,
  `Android` int(11) DEFAULT NULL,
  `iPhone_OS` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `new_users_daily`
--

LOCK TABLES `new_users_daily` WRITE;
/*!40000 ALTER TABLE `new_users_daily` DISABLE KEYS */;
INSERT INTO `new_users_daily` VALUES ('2015-10-06',245,221),('2015-10-08',404,235),('2015-09-23',209,182),('2015-11-04',334,243),('2015-09-04',237,184),('2015-07-28',203,163),('2015-07-09',2,186),('2015-09-26',199,209),('2015-10-04',220,232),('2015-09-07',264,389),('2015-08-19',256,179),('2015-11-06',241,224),('2015-07-31',299,387),('2015-10-18',236,207),('2015-09-29',373,345),('2015-09-10',226,188),('2015-08-22',256,1443),('2015-11-09',232,97),('2015-08-03',410,193),('2015-10-21',299,305),('2015-10-02',238,311),('2015-09-13',278,177),('2015-08-25',294,428),('2015-11-12',243,217),('2015-08-16',266,242),('2015-08-06',302,315),('2015-10-24',263,551),('2015-10-05',263,180),('2015-09-16',267,1),('2015-08-28',305,275),('2015-08-09',290,123),('2015-10-27',247,234),('2015-08-31',258,218),('2015-08-12',264,243),('2015-10-30',163,283),('2015-10-11',263,447),('2015-11-03',222,188),('2015-09-22',240,13),('2015-09-03',252,192),('2015-08-15',272,178),('2015-11-02',230,280),('2015-07-27',1,363),('2015-10-14',236,195),('2015-09-25',165,199),('2015-09-06',267,232),('2015-08-18',294,227),('2015-11-05',235,753),('2015-07-30',270,326),('2015-10-17',246,265),('2015-09-28',257,30),('2015-09-09',217,184),('2015-09-15',270,227),('2015-08-21',241,256),('2015-11-08',323,445),('2015-08-02',326,218),('2015-10-20',267,874),('2015-10-01',226,200),('2015-09-12',260,254),('2015-08-24',324,596),('2015-11-11',240,333),('2015-10-23',284,202),('2015-08-27',297,174),('2015-08-08',305,1),('2015-08-05',342,207),('2015-10-26',224,300),('2015-10-07',227,478),('2015-09-18',212,221),('2015-08-30',264,308),('2015-08-11',251,264),('2015-10-29',224,445),('2015-10-10',268,208),('2015-09-21',279,193),('2015-09-02',257,3),('2015-08-14',258,386),('2015-11-01',247,174),('2015-10-13',257,222),('2015-10-15',221,391),('2015-09-24',198,244),('2015-09-05',231,147),('2015-08-17',255,1),('2015-07-29',471,262),('2015-10-16',192,246),('2015-09-27',241,383),('2015-09-08',252,210),('2015-08-20',266,253),('2015-08-01',322,246),('2015-10-19',285,210),('2015-07-13',1,292),('2015-09-30',233,386),('2015-09-11',243,198),('2015-11-07',238,1057),('2015-08-23',279,158),('2015-11-10',271,185),('2015-08-04',300,298),('2015-10-22',253,315),('2015-10-03',221,81),('2015-09-14',290,707),('2015-08-26',311,231),('2015-11-13',112,202),('2015-08-07',339,2),('2015-10-25',244,235),('2015-09-17',258,288),('2015-08-29',288,540),('2015-08-10',302,256),('2015-10-28',268,251),('2015-09-19',243,251),('2015-10-09',274,255),('2015-09-20',237,612),('2015-09-01',239,205),('2015-08-13',311,185);
/*!40000 ALTER TABLE `new_users_daily` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `new_users_monthly`
--

DROP TABLE IF EXISTS `new_users_monthly`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_users_monthly` (
  `time` date DEFAULT NULL,
  `Android` int(11) DEFAULT NULL,
  `iPhone_OS` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `new_users_monthly`
--

LOCK TABLES `new_users_monthly` WRITE;
/*!40000 ALTER TABLE `new_users_monthly` DISABLE KEYS */;
INSERT INTO `new_users_monthly` VALUES ('2015-08-01',9553,14472),('2015-09-01',7561,7364),('2015-10-01',7764,6263);
/*!40000 ALTER TABLE `new_users_monthly` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_ble_status`
--

DROP TABLE IF EXISTS `users_ble_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_ble_status` (
  `not_capable` int(11) DEFAULT NULL,
  `not_activated` int(11) DEFAULT NULL,
  `activated` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_ble_status`
--

LOCK TABLES `users_ble_status` WRITE;
/*!40000 ALTER TABLE `users_ble_status` DISABLE KEYS */;
INSERT INTO `users_ble_status` VALUES (3215,400633,173630);
/*!40000 ALTER TABLE `users_ble_status` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-12-04 10:43:34
