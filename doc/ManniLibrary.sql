-- MySQL dump 10.19  Distrib 10.3.32-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ManniLibrary
-- ------------------------------------------------------
-- Server version	10.3.32-MariaDB-1:10.3.32+maria~focal

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Book`
--

DROP TABLE IF EXISTS `Book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Book` (
  `BookNumber` int(11) NOT NULL AUTO_INCREMENT COMMENT '書籍編號',
  `Title` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名稱',
  `Author` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '作者',
  `ImgPath` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '圖片路徑',
  `Publisher` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '出版商',
  PRIMARY KEY (`BookNumber`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Book`
--

LOCK TABLES `Book` WRITE;
/*!40000 ALTER TABLE `Book` DISABLE KEYS */;
INSERT INTO `Book` VALUES (1,'類別資料分析導論','Alan Agresti','[path]/LINE_ALBUM_220610_1.jpg','華泰文化事業股份有限公司'),(2,'抽樣調查','Richard L. Scheaffer|William Mendenhall III|R.Lyma Ott|Kenneth G. Gerow','[path]/LINE_ALBUM_220610_2.jpg','新加坡商聖智學習亞洲私人有限公司台灣分公司'),(3,'試驗設計學','沈明來','[path]/LINE_ALBUM_220610_3.jpg','九州圖書文物有限公司'),(4,'R語言：邁向Big Data之路 ','洪錦魁|蔡桂宏','[path]/LINE_ALBUM_220610_4.jpg','深石數位科技股份有限公司'),(5,'會計學','李宗黎|林蕙真','[path]/LINE_ALBUM_220610_5.jpg','華泰文化事業股份有限公司'),(6,'MySQL新手入門超級手冊','張益裕','[path]/LINE_ALBUM_220610_6.jpg','碁峰資訊股份有限公司'),(7,'應用線性迴歸模型','Michael H. Kutner|Christopher J. Nachtsheim|John Neter','[path]/LINE_ALBUM_220610_0.jpg','華泰文化事業股份有限公司'),(8,'線性代數','黃文隆|黃龍','[path]/LINE_ALBUM_220610_7.jpg','滄海圖書資訊股份有限公司'),(9,'統計套裝軟體：精通SPSS','周子敬','[path]/LINE_ALBUM_220610_8.jpg','全華圖書股份有限公司'),(10,'品質工程：線外方法的應用','Chao-Ton Su','[path]/LINE_ALBUM_220610_9.jpg','前程文化事業股份有限公司'),(11,'EAST meets WEST in English 5','LeAnn Eyerman|Julie Sivigny','[path]/LINE_ALBUM_220620_19.jpg','銘薪股份有限公司'),(12,'EAST meets WEST in English 6','LeAnn Eyerman|Julie Sivigny','[path]/LINE_ALBUM_220620_18.jpg','銘薪股份有限公司'),(13,'PowerPoint 2016 實力養成暨評量解題密集','陳美玲|電腦技能基金會','[path]/LINE_ALBUM_220620_12.jpg','全華圖書股份有限公司'),(14,'Excel 2016 實力養成暨評量解題密集','楊明玉|電腦技能基金會','[path]/LINE_ALBUM_220620_13.jpg','全華圖書股份有限公司'),(15,'金融科技力 ','台灣金融研訓院編輯委員會','[path]/LINE_ALBUM_220620_17.jpg','財團法人台灣金融研訓院'),(16,'資料分析輕鬆學: Data Power Today使用手冊','Data Analyst編輯委員會','[path]/LINE_ALBUM_220620_15.jpg','上奇資訊'),(17,'資料分析輕鬆學: Statical Knowledge Today使用手冊','SSE Data Analyst編輯委員會','[path]/LINE_ALBUM_220620_14.jpg','上奇資訊'),(18,'大家的日本語 初級Ⅰ','3A Network','[path]/LINE_ALBUM_220620_11.jpg','大新書局'),(19,'New TOEIC多益新制黃金團隊5回全真試題＋詳解','洪鎮杰|李住恩|NEXUS多益研究所','[path]/LINE_ALBUM_220620_10.jpg','EZ叢書館'),(20,'超極制霸-Data Analysis Using Excel 輕鬆速成祕典','Data Analyst 編輯委員會','[path]/LINE_ALBUM_220620_16.jpg','上奇資訊');
/*!40000 ALTER TABLE `Book` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CirculatedCopy`
--

DROP TABLE IF EXISTS `CirculatedCopy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CirculatedCopy` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '編號',
  `ReaderNumber` int(11) DEFAULT NULL COMMENT '讀者編號',
  `CopyNumber` int(11) DEFAULT NULL COMMENT '書籍副本編號',
  `BorrowDate` datetime NOT NULL COMMENT '借閱時間',
  `DueDate` datetime DEFAULT NULL COMMENT '借閱期限',
  `ReturnDate` datetime DEFAULT NULL COMMENT '歸還時間',
  `FineAmount` int(11) DEFAULT NULL COMMENT '罰款金額',
  PRIMARY KEY (`ID`),
  KEY `ReaderNumber_idx` (`ReaderNumber`),
  KEY `CopyNumber_idx` (`CopyNumber`),
  CONSTRAINT `CopyNumber` FOREIGN KEY (`CopyNumber`) REFERENCES `Copy` (`CopyNumber`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `ReaderNumber` FOREIGN KEY (`ReaderNumber`) REFERENCES `Reader` (`ReaderNumber`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CirculatedCopy`
--

LOCK TABLES `CirculatedCopy` WRITE;
/*!40000 ALTER TABLE `CirculatedCopy` DISABLE KEYS */;
INSERT INTO `CirculatedCopy` VALUES (1,2,7,'2022-01-01 00:00:00','2022-01-15 00:00:00','2022-01-10 00:00:00',NULL),(2,1,8,'2022-01-15 00:00:00','2022-01-29 00:00:00','2022-01-25 00:00:00',NULL),(3,3,9,'2022-01-16 00:00:00','2022-01-30 00:00:00','2022-01-22 00:00:00',NULL),(4,5,1,'2022-01-31 00:00:00','2022-02-14 00:00:00','2022-02-28 00:00:00',NULL),(5,6,3,'2022-02-28 00:00:00','2022-03-14 00:00:00','2022-03-16 00:00:00',NULL),(6,7,5,'2022-03-01 00:00:00','2022-03-16 00:00:00','2022-03-12 00:00:00',NULL),(7,3,7,'2022-03-13 00:00:00','2022-03-28 00:00:00','2022-03-20 00:00:00',NULL),(8,9,2,'2022-03-23 00:00:00','2022-04-07 00:00:00','2022-04-10 00:00:00',NULL),(9,8,1,'2022-04-06 00:00:00','2022-04-21 00:00:00','2022-04-21 00:00:00',NULL),(10,10,9,'2022-04-07 00:00:00','2022-04-22 00:00:00','2022-04-21 00:00:00',NULL),(11,9,3,'2022-04-30 00:00:00','2022-05-15 00:00:00','2022-05-14 00:00:00',NULL),(12,9,5,'2022-04-30 00:00:00','2022-05-15 00:00:00','2022-05-14 00:00:00',NULL),(13,6,10,'2022-05-05 00:00:00','2022-05-20 00:00:00','2022-05-11 00:00:00',NULL),(14,4,8,'2022-05-05 00:00:00','2022-05-20 00:00:00','2022-05-11 00:00:00',NULL),(15,1,6,'2022-05-10 00:00:00','2022-05-25 00:00:00','2022-05-20 00:00:00',NULL),(17,35,5,'2022-06-19 17:25:07','2022-07-03 17:25:07','2022-06-20 05:04:37',NULL),(24,35,6,'2022-06-19 17:30:17','2022-07-03 17:30:17','2022-06-20 05:04:37',NULL),(25,35,7,'2022-06-19 17:30:24','2022-07-03 17:30:24','2022-06-20 05:04:37',NULL),(29,35,2,'2022-06-20 00:17:50','2022-07-04 00:17:50','2022-06-20 05:03:34',NULL),(30,35,1,'2022-06-20 00:18:04','2022-07-04 00:18:04','2022-06-20 05:03:34',NULL),(31,35,9,'2022-06-20 00:18:04','2022-07-04 00:18:04','2022-06-20 05:04:37',NULL),(32,35,10,'2022-06-20 02:12:10','2022-07-04 02:12:10','2022-06-20 05:04:37',NULL),(33,35,14,'2022-06-20 02:14:35','2022-07-04 02:14:35','2022-06-20 05:04:37',NULL),(34,35,11,'2022-06-20 02:16:33','2022-07-04 02:16:33','2022-06-20 05:04:37',NULL),(35,35,13,'2022-06-20 03:15:20','2022-08-01 03:15:20','2022-06-20 05:04:37',NULL),(36,35,12,'2022-06-20 03:16:14','2022-08-01 03:16:14','2022-06-20 05:04:37',NULL),(37,35,1,'2022-06-20 05:06:50','2022-07-18 05:06:50','2022-06-20 05:07:08',NULL),(38,35,13,'2022-06-20 05:23:44','2022-07-04 05:23:44','2022-06-20 05:23:50',NULL),(39,35,1,'2022-06-20 05:27:52','2022-07-04 05:27:52','2022-06-20 05:27:59',NULL),(40,35,10,'2022-06-20 07:02:50','2022-07-04 07:02:50','2022-06-20 07:57:17',NULL),(41,35,11,'2022-06-20 07:57:00','2022-07-04 07:57:00','2022-06-20 07:57:17',NULL),(42,35,9,'2022-06-20 07:58:36','2022-07-04 07:58:36','2022-06-20 23:20:40',NULL),(43,35,8,'2022-06-20 23:20:08','2022-07-04 23:20:08','2022-06-20 23:20:40',NULL),(44,35,2,'2022-06-20 23:20:08','2022-07-04 23:20:08','2022-06-20 23:20:40',NULL),(45,35,31,'2022-06-20 23:20:08','2022-07-04 23:20:08','2022-06-20 23:20:40',NULL),(46,35,8,'2022-06-20 23:52:58','2022-07-04 23:52:58',NULL,NULL),(47,36,1,'2022-06-21 00:01:59','2022-07-05 00:01:59',NULL,NULL),(48,15,4,'2022-06-21 00:01:59','2022-07-05 00:01:59',NULL,NULL),(49,15,5,'2022-06-21 00:01:59','2022-07-05 00:01:59',NULL,NULL),(50,35,9,'2022-06-21 14:50:04','2022-07-05 14:50:04','2022-06-21 14:52:43',NULL);
/*!40000 ALTER TABLE `CirculatedCopy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Copy`
--

DROP TABLE IF EXISTS `Copy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Copy` (
  `CopyNumber` int(11) NOT NULL AUTO_INCREMENT COMMENT '書籍副本編號',
  `BookNumber` int(11) NOT NULL COMMENT '書籍編號',
  `SequenceNumber` bigint(13) DEFAULT NULL COMMENT 'ISBN',
  `Type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '書籍副本狀態',
  `Price` int(11) DEFAULT NULL COMMENT '價格',
  PRIMARY KEY (`CopyNumber`),
  KEY `BookNumber_idx` (`BookNumber`),
  CONSTRAINT `BookNumber` FOREIGN KEY (`BookNumber`) REFERENCES `Book` (`BookNumber`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Copy`
--

LOCK TABLES `Copy` WRITE;
/*!40000 ALTER TABLE `Copy` DISABLE KEYS */;
INSERT INTO `Copy` VALUES (1,1,9789576095061,1,500),(2,2,9789865840594,0,650),(3,3,9789866929328,0,850),(4,4,9789869488471,1,580),(5,5,9789869907705,1,760),(6,5,9789869907705,0,760),(7,5,9789869907705,0,760),(8,6,9789864768547,1,520),(9,7,9789861571317,0,640),(10,8,9789865647308,0,620),(11,8,9789865647308,0,620),(12,8,9789865647308,0,620),(13,9,9789572157107,0,550),(14,10,9789865774066,0,680),(25,11,9579681503,0,NULL),(26,12,957968152,0,NULL),(27,13,9789864634934,0,NULL),(28,14,9789864634927,2,NULL),(29,15,9789863991632,0,NULL),(30,16,9789865001049,0,NULL),(31,17,9789869515610,0,NULL),(32,18,9789578279063,0,NULL),(33,19,9789862487068,0,NULL),(34,20,9789865001421,0,NULL);
/*!40000 ALTER TABLE `Copy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Moderator`
--

DROP TABLE IF EXISTS `Moderator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Moderator` (
  `Email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '電子信箱',
  `Password` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密碼',
  PRIMARY KEY (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Moderator`
--

LOCK TABLES `Moderator` WRITE;
/*!40000 ALTER TABLE `Moderator` DISABLE KEYS */;
INSERT INTO `Moderator` VALUES ('08170424@me.mcu.edu.tw','08170424'),('08170540@me.mcu.edu.tw','08170540'),('08170620@me.mcu.edu.tw','08170620'),('08170875@me.mcu.edu.tw','08170875'),('admin','admin');
/*!40000 ALTER TABLE `Moderator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Post`
--

DROP TABLE IF EXISTS `Post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Post` (
  `NO` int(11) NOT NULL AUTO_INCREMENT COMMENT '編號',
  `Type` int(11) DEFAULT NULL COMMENT '類別',
  `Content` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '內容',
  `PublishDate` date NOT NULL DEFAULT current_timestamp() COMMENT '發佈日期',
  `DueDate` date DEFAULT NULL COMMENT '截止日期',
  PRIMARY KEY (`NO`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Post`
--

LOCK TABLES `Post` WRITE;
/*!40000 ALTER TABLE `Post` DISABLE KEYS */;
INSERT INTO `Post` VALUES (1,1,'本館固定每月最後一個禮拜二休館','2020-02-04',NULL),(2,0,'線上借書系統，將於每日凌晨3:00~5:00維護，請讀者多加注意。','2022-06-11','2023-12-31'),(3,1,'因應疫情，本館自習室暫時關閉。','2022-06-11',NULL),(4,1,'因應疫情，本館每周星期二與五休館時間調整為早上9:00~下午5:00，懇請讀者多加注意。','2022-06-11',NULL),(5,1,'因應疫情，本館的每周星期四晚上的電影欣賞會暫時取消。','2022-06-07',NULL),(6,1,'因應疫情，本館原將於111年6月20日舉辦台語說故事活動取消。','2022-06-01',NULL),(7,0,'本館預計於8月1號休館一個月，將對館內重新裝潢整修。','2022-06-10','2022-08-31'),(8,0,'短時間公告','2022-06-01','2022-06-10'),(9,9,'我怎麼今天才想到可以寫更新日誌？','2022-06-20',NULL),(11,9,'新增了管理員更新佇列書籍功能、借書功能與還書功能。 讀者頁面新增了簡易的借書、還書表格。 於書籍搜尋頁面新增了借閱按鈕。','2022-06-20',NULL),(12,9,'新增了10本書籍。','2022-06-20',NULL);
/*!40000 ALTER TABLE `Post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Reader`
--

DROP TABLE IF EXISTS `Reader`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Reader` (
  `ReaderNumber` int(11) NOT NULL AUTO_INCREMENT COMMENT '讀者編號',
  `Email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '電子信箱',
  `Password` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密碼',
  `Name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '姓名',
  `Gender` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '性別',
  `BirthDate` date NOT NULL COMMENT '出生日期',
  `PhoneNum` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '手機號碼',
  PRIMARY KEY (`ReaderNumber`),
  UNIQUE KEY `Email_UNIQUE` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Reader`
--

LOCK TABLES `Reader` WRITE;
/*!40000 ALTER TABLE `Reader` DISABLE KEYS */;
INSERT INTO `Reader` VALUES (1,'asd@gmail.com','789456','小馬','男','2022-06-01','090000000'),(2,'fgh@gmail.com','456123','小李','女','2022-06-02','090000001'),(3,'edc@gmail.com','789123','阿明','不願透露','2015-06-17','090000002'),(4,'abd@gmail.com','963852','小美','不願透露','2014-06-11','090000003'),(5,'adj@gmail.com','741852','阿嬌','女','2004-06-09','090000004'),(6,'ilo@gmail.com','789512','阿貴','男','2012-04-09','090000005'),(7,'frtyu@gmail.com','123574','阿宇','女','2012-08-09','090000006'),(8,'opl@gmail.com','842695','小瓜','不願透露','2019-09-13','090000007'),(9,'tgb@gmail.com','741236','阿賴','男','2016-03-15','090000008'),(10,'yhnujm@gmail.com','985264','小虎','女','2027-09-30','090000009'),(15,'08170875@me.mcu.edu.tw','1230','陳O瑋','不願透露','2022-06-17','090000010'),(32,'7788@me.mcu.edu.tw','1234','阿瑤為你痛哭','不願透露','2021-10-19','013456778'),(35,'08170540@me.mcu.edu.tw','123','李佳芮','女','2022-06-17','0912341234'),(36,'08170620@me.mcu.edu.tw','08170620','德善','女','1998-12-31','0912341234');
/*!40000 ALTER TABLE `Reader` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Reserved`
--

DROP TABLE IF EXISTS `Reserved`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Reserved` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '編號',
  `R_ReaderNumber` int(11) DEFAULT NULL COMMENT '讀者編號',
  `R_BookNumber` int(11) DEFAULT NULL COMMENT '書籍編號',
  `Date` datetime DEFAULT NULL COMMENT '預借日期',
  `Status` tinyint(1) DEFAULT NULL COMMENT '預借狀態',
  PRIMARY KEY (`ID`),
  KEY `BookNumber_idx` (`R_BookNumber`),
  KEY `ReaderNumber_idx` (`R_ReaderNumber`),
  CONSTRAINT `R_BookNumber` FOREIGN KEY (`R_BookNumber`) REFERENCES `Book` (`BookNumber`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `R_ReaderNumber` FOREIGN KEY (`R_ReaderNumber`) REFERENCES `Reader` (`ReaderNumber`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Reserved`
--

LOCK TABLES `Reserved` WRITE;
/*!40000 ALTER TABLE `Reserved` DISABLE KEYS */;
INSERT INTO `Reserved` VALUES (1,2,1,'2022-01-16 00:00:00',2),(2,4,3,'2022-01-30 00:00:00',2),(3,6,1,'2022-01-31 00:00:00',2),(4,8,4,'2022-02-15 00:00:00',2),(5,1,9,'2022-03-15 00:00:00',2),(6,2,9,'2022-03-16 00:00:00',2),(7,1,6,'2022-03-28 00:00:00',2),(8,5,8,'2022-04-07 00:00:00',2),(9,6,7,'2022-04-21 00:00:00',2),(10,10,8,'2022-04-22 00:00:00',2),(11,9,7,'2022-05-15 00:00:00',2),(12,9,3,'2022-05-15 00:00:00',2),(13,6,3,'2022-05-20 00:00:00',2),(14,7,2,'2022-05-20 00:00:00',2),(15,3,10,'2022-05-25 00:00:00',2),(22,35,1,'2022-06-19 18:28:41',2),(23,35,2,'2022-06-19 19:06:01',2),(24,35,7,'2022-06-19 23:32:59',2),(25,35,8,'2022-06-20 00:23:19',2),(26,35,10,'2022-06-20 02:14:15',2),(27,35,8,'2022-06-20 02:16:18',2),(28,35,9,'2022-06-20 05:23:05',2),(29,35,1,'2022-06-20 05:27:28',2),(30,35,8,'2022-06-20 07:02:30',2),(31,35,8,'2022-06-20 07:56:51',2),(32,35,7,'2022-06-20 07:58:26',2),(33,35,6,'2022-06-20 08:01:55',2),(34,35,2,'2022-06-20 08:02:28',2),(35,36,1,'2022-06-20 10:17:42',2),(36,35,17,'2022-06-20 21:56:06',2),(37,35,6,'2022-06-20 23:51:06',2),(38,15,4,'2022-06-20 23:53:45',2),(39,15,5,'2022-06-20 23:54:18',2),(40,35,7,'2022-06-21 14:48:11',2),(41,15,14,'2022-06-22 19:15:10',1);
/*!40000 ALTER TABLE `Reserved` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-06-22 11:36:27
