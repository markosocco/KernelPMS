-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: kernel
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.31-MariaDB

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
-- Table structure for table `assessmentdepartment`
--

DROP TABLE IF EXISTS `assessmentdepartment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assessmentdepartment` (
  `DEPARTMENTASSESSMENTID` int(11) NOT NULL AUTO_INCREMENT,
  `ACCURACY` double DEFAULT NULL,
  `COMPLETENESS` double DEFAULT NULL,
  `TIMELINESS` double DEFAULT NULL,
  `departments_DEPARTMENTID` int(11) DEFAULT NULL,
  `DATE` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`DEPARTMENTASSESSMENTID`),
  KEY `fk_departmentAssessment_departments1_idx` (`departments_DEPARTMENTID`),
  CONSTRAINT `fk_departmentAssessment_departments1` FOREIGN KEY (`departments_DEPARTMENTID`) REFERENCES `departments` (`DEPARTMENTID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=167 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assessmentdepartment`
--

LOCK TABLES `assessmentdepartment` WRITE;
/*!40000 ALTER TABLE `assessmentdepartment` DISABLE KEYS */;
/*!40000 ALTER TABLE `assessmentdepartment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assessmentemployee`
--

DROP TABLE IF EXISTS `assessmentemployee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assessmentemployee` (
  `EMPLOYEEASSESSMENTID` int(11) NOT NULL AUTO_INCREMENT,
  `ACCURACY` double DEFAULT NULL,
  `COMPLETENESS` double DEFAULT NULL,
  `TIMELINESS` double DEFAULT NULL,
  `users_USERID` int(11) DEFAULT NULL,
  `DATE` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEEASSESSMENTID`),
  KEY `fk_employeeAssessment_users1_idx` (`users_USERID`),
  CONSTRAINT `fk_employeeAssessment_users1` FOREIGN KEY (`users_USERID`) REFERENCES `users` (`USERID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=535 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assessmentemployee`
--

LOCK TABLES `assessmentemployee` WRITE;
/*!40000 ALTER TABLE `assessmentemployee` DISABLE KEYS */;
/*!40000 ALTER TABLE `assessmentemployee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assessmentproject`
--

DROP TABLE IF EXISTS `assessmentproject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assessmentproject` (
  `ASSESSMENTPROJECTID` int(11) NOT NULL AUTO_INCREMENT,
  `ACCURACY` double DEFAULT NULL,
  `COMPLETENESS` double DEFAULT NULL,
  `TIMELINESS` double DEFAULT NULL,
  `projects_PROJECTID` int(11) NOT NULL,
  `DATE` varchar(20) NOT NULL,
  `TYPE` int(1) DEFAULT NULL COMMENT '1 - project\\n2 - main',
  `tasks_MAINID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ASSESSMENTPROJECTID`),
  KEY `fk_ projectWeeklyProgress_projects1_idx` (`projects_PROJECTID`),
  KEY `fk_assessmentProject_task1_idx` (`tasks_MAINID`),
  CONSTRAINT `fk_ assessmentProject_projects1` FOREIGN KEY (`projects_PROJECTID`) REFERENCES `projects` (`PROJECTID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_assessmentProject_task1` FOREIGN KEY (`tasks_MAINID`) REFERENCES `tasks` (`TASKID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1714 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assessmentproject`
--

LOCK TABLES `assessmentproject` WRITE;
/*!40000 ALTER TABLE `assessmentproject` DISABLE KEYS */;
INSERT INTO `assessmentproject` VALUES (1499,NULL,0,100,70,'2018-10-20',1,NULL),(1500,NULL,0,100,70,'2018-10-21',1,NULL),(1501,NULL,0,100,70,'2018-10-22',1,NULL),(1502,NULL,0,100,70,'2018-10-23',1,NULL),(1503,NULL,7.14,100,70,'2018-10-24',1,NULL),(1504,NULL,7.14,100,70,'2018-10-25',1,NULL),(1505,NULL,14.28,100,70,'2018-10-26',1,NULL),(1506,NULL,14.28,100,70,'2018-10-27',1,NULL),(1507,NULL,14.28,100,70,'2018-10-28',1,NULL),(1508,NULL,14.28,100,70,'2018-10-29',1,NULL),(1509,NULL,14.28,100,70,'2018-10-30',1,NULL),(1510,NULL,14.28,100,70,'2018-10-31',1,NULL),(1511,NULL,14.28,100,70,'2018-11-01',1,NULL),(1512,NULL,14.28,100,70,'2018-11-02',1,NULL),(1513,NULL,21.42,100,70,'2018-11-03',1,NULL),(1514,NULL,21.42,100,70,'2018-11-04',1,NULL),(1515,NULL,21.42,100,70,'2018-11-05',1,NULL),(1516,NULL,28.56,100,70,'2018-11-06',1,NULL),(1517,NULL,28.56,100,70,'2018-11-07',1,NULL),(1518,NULL,28.56,100,70,'2018-11-08',1,NULL),(1519,NULL,28.56,100,70,'2018-11-09',1,NULL),(1520,NULL,28.56,100,70,'2018-11-10',1,NULL),(1521,NULL,28.56,100,70,'2018-11-11',1,NULL),(1522,NULL,28.56,100,70,'2018-11-12',1,NULL),(1523,NULL,28.56,100,70,'2018-11-13',1,NULL),(1524,NULL,28.56,100,70,'2018-11-14',1,NULL),(1525,NULL,28.56,100,70,'2018-11-15',1,NULL),(1526,NULL,35.7,100,70,'2018-11-16',1,NULL),(1527,NULL,35.7,100,70,'2018-11-17',1,NULL),(1528,NULL,35.7,100,70,'2018-11-18',1,NULL),(1529,NULL,42.84,100,70,'2018-11-19',1,NULL),(1530,NULL,42.84,100,70,'2018-11-20',1,NULL),(1531,NULL,49.98,100,70,'2018-11-21',1,NULL),(1532,NULL,49.98,100,70,'2018-11-22',1,NULL),(1533,NULL,57.12,100,70,'2018-11-23',1,NULL),(1534,NULL,57.12,100,70,'2018-11-24',1,NULL),(1535,NULL,57.12,100,70,'2018-11-25',1,NULL),(1536,NULL,57.12,100,70,'2018-11-26',1,NULL),(1537,NULL,57.12,100,70,'2018-11-27',1,NULL),(1538,NULL,64.26,100,70,'2018-11-28',1,NULL),(1539,NULL,64.26,100,70,'2018-11-29',1,NULL),(1540,NULL,64.26,100,70,'2018-11-30',1,NULL),(1541,NULL,64.26,100,70,'2018-12-01',1,NULL),(1542,NULL,0,NULL,70,'2018-10-20',2,605),(1543,NULL,0,NULL,70,'2018-10-20',2,613),(1544,NULL,0,NULL,70,'2018-10-20',2,617),(1545,NULL,0,NULL,70,'2018-10-20',2,625),(1546,NULL,0,NULL,70,'2018-10-21',2,605),(1547,NULL,0,NULL,70,'2018-10-21',2,613),(1548,NULL,0,NULL,70,'2018-10-21',2,617),(1549,NULL,0,NULL,70,'2018-10-21',2,625),(1550,NULL,0,NULL,70,'2018-10-22',2,605),(1551,NULL,0,NULL,70,'2018-10-22',2,613),(1552,NULL,0,NULL,70,'2018-10-22',2,617),(1553,NULL,0,NULL,70,'2018-10-22',2,625),(1554,NULL,0,NULL,70,'2018-10-23',2,605),(1555,NULL,0,NULL,70,'2018-10-23',2,613),(1556,NULL,0,NULL,70,'2018-10-23',2,617),(1557,NULL,0,NULL,70,'2018-10-23',2,625),(1558,NULL,20,NULL,70,'2018-10-24',2,605),(1559,NULL,0,NULL,70,'2018-10-24',2,613),(1560,NULL,0,NULL,70,'2018-10-24',2,617),(1561,NULL,0,NULL,70,'2018-10-24',2,625),(1562,NULL,20,NULL,70,'2018-10-25',2,605),(1563,NULL,0,NULL,70,'2018-10-25',2,613),(1564,NULL,0,NULL,70,'2018-10-25',2,617),(1565,NULL,0,NULL,70,'2018-10-25',2,625),(1566,NULL,40,NULL,70,'2018-10-26',2,605),(1567,NULL,0,NULL,70,'2018-10-26',2,613),(1568,NULL,0,NULL,70,'2018-10-26',2,617),(1569,NULL,0,NULL,70,'2018-10-26',2,625),(1570,NULL,40,NULL,70,'2018-10-27',2,605),(1571,NULL,0,NULL,70,'2018-10-27',2,613),(1572,NULL,0,NULL,70,'2018-10-27',2,617),(1573,NULL,0,NULL,70,'2018-10-27',2,625),(1574,NULL,40,NULL,70,'2018-10-28',2,605),(1575,NULL,0,NULL,70,'2018-10-28',2,613),(1576,NULL,0,NULL,70,'2018-10-28',2,617),(1577,NULL,0,NULL,70,'2018-10-28',2,625),(1578,NULL,40,NULL,70,'2018-10-29',2,605),(1579,NULL,0,NULL,70,'2018-10-29',2,613),(1580,NULL,0,NULL,70,'2018-10-29',2,617),(1581,NULL,0,NULL,70,'2018-10-29',2,625),(1582,NULL,40,NULL,70,'2018-10-30',2,605),(1583,NULL,0,NULL,70,'2018-10-30',2,613),(1584,NULL,0,NULL,70,'2018-10-30',2,617),(1585,NULL,0,NULL,70,'2018-10-30',2,625),(1586,NULL,40,NULL,70,'2018-10-31',2,605),(1587,NULL,0,NULL,70,'2018-10-31',2,613),(1588,NULL,0,NULL,70,'2018-10-31',2,617),(1589,NULL,0,NULL,70,'2018-10-31',2,625),(1590,NULL,40,NULL,70,'2018-11-01',2,605),(1591,NULL,0,NULL,70,'2018-11-01',2,613),(1592,NULL,0,NULL,70,'2018-11-01',2,617),(1593,NULL,0,NULL,70,'2018-11-01',2,625),(1594,NULL,40,NULL,70,'2018-11-02',2,605),(1595,NULL,0,NULL,70,'2018-11-02',2,613),(1596,NULL,0,NULL,70,'2018-11-02',2,617),(1597,NULL,0,NULL,70,'2018-11-02',2,625),(1598,NULL,60,NULL,70,'2018-11-03',2,605),(1599,NULL,0,NULL,70,'2018-11-03',2,613),(1600,NULL,0,NULL,70,'2018-11-03',2,617),(1601,NULL,0,NULL,70,'2018-11-03',2,625),(1602,NULL,60,NULL,70,'2018-11-04',2,605),(1603,NULL,0,NULL,70,'2018-11-04',2,613),(1604,NULL,0,NULL,70,'2018-11-04',2,617),(1605,NULL,0,NULL,70,'2018-11-04',2,625),(1606,NULL,60,NULL,70,'2018-11-05',2,605),(1607,NULL,0,NULL,70,'2018-11-05',2,613),(1608,NULL,0,NULL,70,'2018-11-05',2,617),(1609,NULL,0,NULL,70,'2018-11-05',2,625),(1610,NULL,80,NULL,70,'2018-11-06',2,605),(1611,NULL,0,NULL,70,'2018-11-06',2,613),(1612,NULL,0,NULL,70,'2018-11-06',2,617),(1613,NULL,0,NULL,70,'2018-11-06',2,625),(1614,NULL,80,NULL,70,'2018-11-07',2,605),(1615,NULL,0,NULL,70,'2018-11-07',2,613),(1616,NULL,0,NULL,70,'2018-11-07',2,617),(1617,NULL,0,NULL,70,'2018-11-07',2,625),(1618,NULL,80,NULL,70,'2018-11-08',2,605),(1619,NULL,0,NULL,70,'2018-11-08',2,613),(1620,NULL,0,NULL,70,'2018-11-08',2,617),(1621,NULL,0,NULL,70,'2018-11-08',2,625),(1622,NULL,80,NULL,70,'2018-11-09',2,605),(1623,NULL,0,NULL,70,'2018-11-09',2,613),(1624,NULL,0,NULL,70,'2018-11-09',2,617),(1625,NULL,0,NULL,70,'2018-11-09',2,625),(1626,NULL,100,NULL,70,'2018-11-10',2,605),(1627,NULL,0,NULL,70,'2018-11-10',2,613),(1628,NULL,0,NULL,70,'2018-11-10',2,617),(1629,NULL,0,NULL,70,'2018-11-10',2,625),(1630,NULL,100,NULL,70,'2018-11-11',2,605),(1631,NULL,0,NULL,70,'2018-11-11',2,613),(1632,NULL,0,NULL,70,'2018-11-11',2,617),(1633,NULL,0,NULL,70,'2018-11-11',2,625),(1634,NULL,100,NULL,70,'2018-11-12',2,605),(1635,NULL,0,NULL,70,'2018-11-12',2,613),(1636,NULL,0,NULL,70,'2018-11-12',2,617),(1637,NULL,0,NULL,70,'2018-11-12',2,625),(1638,NULL,100,NULL,70,'2018-11-13',2,605),(1639,NULL,0,NULL,70,'2018-11-13',2,613),(1640,NULL,0,NULL,70,'2018-11-13',2,617),(1641,NULL,0,NULL,70,'2018-11-13',2,625),(1642,NULL,100,NULL,70,'2018-11-14',2,605),(1643,NULL,0,NULL,70,'2018-11-14',2,613),(1644,NULL,0,NULL,70,'2018-11-14',2,617),(1645,NULL,0,NULL,70,'2018-11-14',2,625),(1646,NULL,100,NULL,70,'2018-11-15',2,605),(1647,NULL,0,NULL,70,'2018-11-15',2,613),(1648,NULL,0,NULL,70,'2018-11-15',2,617),(1649,NULL,0,NULL,70,'2018-11-15',2,625),(1650,NULL,100,NULL,70,'2018-11-16',2,605),(1651,NULL,100,NULL,70,'2018-11-16',2,613),(1652,NULL,0,NULL,70,'2018-11-16',2,617),(1653,NULL,0,NULL,70,'2018-11-16',2,625),(1654,NULL,100,NULL,70,'2018-11-17',2,605),(1655,NULL,100,NULL,70,'2018-11-17',2,613),(1656,NULL,0,NULL,70,'2018-11-17',2,617),(1657,NULL,0,NULL,70,'2018-11-17',2,625),(1658,NULL,100,NULL,70,'2018-11-18',2,605),(1659,NULL,100,NULL,70,'2018-11-18',2,613),(1660,NULL,0,NULL,70,'2018-11-18',2,617),(1661,NULL,0,NULL,70,'2018-11-18',2,625),(1662,NULL,100,NULL,70,'2018-11-19',2,605),(1663,NULL,100,NULL,70,'2018-11-19',2,613),(1664,NULL,0,NULL,70,'2018-11-19',2,617),(1665,NULL,0,NULL,70,'2018-11-19',2,625),(1666,NULL,100,NULL,70,'2018-11-20',2,605),(1667,NULL,100,NULL,70,'2018-11-20',2,613),(1668,NULL,0,NULL,70,'2018-11-20',2,617),(1669,NULL,0,NULL,70,'2018-11-20',2,625),(1670,NULL,100,NULL,70,'2018-11-21',2,605),(1671,NULL,100,NULL,70,'2018-11-21',2,613),(1672,NULL,100,NULL,70,'2018-11-21',2,617),(1673,NULL,0,NULL,70,'2018-11-21',2,625),(1674,NULL,100,NULL,70,'2018-11-22',2,605),(1675,NULL,100,NULL,70,'2018-11-22',2,613),(1676,NULL,100,NULL,70,'2018-11-22',2,617),(1677,NULL,0,NULL,70,'2018-11-22',2,625),(1678,NULL,100,NULL,70,'2018-11-23',2,605),(1679,NULL,100,NULL,70,'2018-11-23',2,613),(1680,NULL,100,NULL,70,'2018-11-23',2,617),(1681,NULL,0,NULL,70,'2018-11-23',2,625),(1682,NULL,100,NULL,70,'2018-11-24',2,605),(1683,NULL,100,NULL,70,'2018-11-24',2,613),(1684,NULL,100,NULL,70,'2018-11-24',2,617),(1685,NULL,0,NULL,70,'2018-11-24',2,625),(1686,NULL,100,NULL,70,'2018-11-25',2,605),(1687,NULL,100,NULL,70,'2018-11-25',2,613),(1688,NULL,100,NULL,70,'2018-11-25',2,617),(1689,NULL,0,NULL,70,'2018-11-25',2,625),(1690,NULL,100,NULL,70,'2018-11-26',2,605),(1691,NULL,100,NULL,70,'2018-11-26',2,613),(1692,NULL,100,NULL,70,'2018-11-26',2,617),(1693,NULL,0,NULL,70,'2018-11-26',2,625),(1694,NULL,100,NULL,70,'2018-11-27',2,605),(1695,NULL,100,NULL,70,'2018-11-27',2,613),(1696,NULL,100,NULL,70,'2018-11-27',2,617),(1697,NULL,0,NULL,70,'2018-11-27',2,625),(1698,NULL,100,NULL,70,'2018-11-28',2,605),(1699,NULL,100,NULL,70,'2018-11-28',2,613),(1700,NULL,100,NULL,70,'2018-11-28',2,617),(1701,NULL,0,NULL,70,'2018-11-28',2,625),(1702,NULL,100,NULL,70,'2018-11-29',2,605),(1703,NULL,100,NULL,70,'2018-11-29',2,613),(1704,NULL,100,NULL,70,'2018-11-29',2,617),(1705,NULL,0,NULL,70,'2018-11-29',2,625),(1706,NULL,100,NULL,70,'2018-11-30',2,605),(1707,NULL,100,NULL,70,'2018-11-30',2,613),(1708,NULL,100,NULL,70,'2018-11-30',2,617),(1709,NULL,0,NULL,70,'2018-11-30',2,625),(1710,NULL,100,NULL,70,'2018-12-01',2,605),(1711,NULL,100,NULL,70,'2018-12-01',2,613),(1712,NULL,100,NULL,70,'2018-12-01',2,617),(1713,NULL,0,NULL,70,'2018-12-01',2,625);
/*!40000 ALTER TABLE `assessmentproject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `changerequests`
--

DROP TABLE IF EXISTS `changerequests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `changerequests` (
  `REQUESTID` int(11) NOT NULL AUTO_INCREMENT,
  `REQUESTTYPE` varchar(5) NOT NULL COMMENT '1 - Change task performer\n2 - Change task dates',
  `NEWSTARTDATE` varchar(20) DEFAULT NULL,
  `NEWENDDATE` varchar(20) DEFAULT NULL,
  `REASON` text NOT NULL,
  `users_REQUESTEDBY` int(11) NOT NULL,
  `REQUESTEDDATE` varchar(20) NOT NULL,
  `REQUESTSTATUS` varchar(45) NOT NULL,
  `users_APPROVEDBY` int(11) DEFAULT NULL,
  `APPROVEDDATE` varchar(20) DEFAULT NULL,
  `REMARKS` text,
  `tasks_REQUESTEDTASK` int(11) NOT NULL,
  PRIMARY KEY (`REQUESTID`),
  KEY `fk_changerequests_users1_idx` (`users_REQUESTEDBY`),
  KEY `fk_changerequests_users2_idx` (`users_APPROVEDBY`),
  KEY `fk_changerequests_tasks1_idx` (`tasks_REQUESTEDTASK`),
  CONSTRAINT `fk_changerequests_tasks1` FOREIGN KEY (`tasks_REQUESTEDTASK`) REFERENCES `tasks` (`TASKID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_changerequests_users1` FOREIGN KEY (`users_REQUESTEDBY`) REFERENCES `users` (`USERID`),
  CONSTRAINT `fk_changerequests_users2` FOREIGN KEY (`users_APPROVEDBY`) REFERENCES `users` (`USERID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `changerequests`
--

LOCK TABLES `changerequests` WRITE;
/*!40000 ALTER TABLE `changerequests` DISABLE KEYS */;
/*!40000 ALTER TABLE `changerequests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `DEPARTMENTID` int(11) NOT NULL AUTO_INCREMENT,
  `DEPARTMENTNAME` varchar(100) NOT NULL,
  `users_DEPARTMENTHEAD` int(11) NOT NULL,
  `DEPT` varchar(10) NOT NULL,
  `COLOR` varchar(10) NOT NULL,
  `isAct` int(11) NOT NULL COMMENT '1 - Active\n0 - Inactive',
  PRIMARY KEY (`DEPARTMENTID`),
  KEY `fk_users1_idx` (`users_DEPARTMENTHEAD`),
  CONSTRAINT `fk_users1` FOREIGN KEY (`users_DEPARTMENTHEAD`) REFERENCES `users` (`USERID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Executive',2,'EXEC','',1),(2,'Marketing',4,'MKT','',1),(3,'Finance',5,'FIN','',1),(4,'Procurement',6,'PROC','',1),(5,'Human Resource',7,'HR','',1),(6,'Management Information System',8,'MIS','',1),(7,'Store Operations',9,'OPS','',1);
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dependencies`
--

DROP TABLE IF EXISTS `dependencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dependencies` (
  `DEPENDENCYID` int(11) NOT NULL AUTO_INCREMENT,
  `PRETASKID` varchar(45) NOT NULL,
  `tasks_POSTTASKID` int(11) DEFAULT NULL,
  PRIMARY KEY (`DEPENDENCYID`),
  KEY `fk_dependencies_tasks1_idx` (`tasks_POSTTASKID`),
  CONSTRAINT `fk_dependencies_tasks1` FOREIGN KEY (`tasks_POSTTASKID`) REFERENCES `tasks` (`TASKID`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dependencies`
--

LOCK TABLES `dependencies` WRITE;
/*!40000 ALTER TABLE `dependencies` DISABLE KEYS */;
INSERT INTO `dependencies` VALUES (40,'608',624);
/*!40000 ALTER TABLE `dependencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentacknowledgement`
--

DROP TABLE IF EXISTS `documentacknowledgement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documentacknowledgement` (
  `DOCUMENTACKNOWLEDGEMENTID` int(11) NOT NULL AUTO_INCREMENT,
  `documents_DOCUMENTID` int(11) NOT NULL,
  `users_ACKNOWLEDGEDBY` int(11) NOT NULL,
  `ACKNOWLEDGEDDATE` varchar(45) NOT NULL,
  PRIMARY KEY (`DOCUMENTACKNOWLEDGEMENTID`),
  KEY `fk_documentAcknowledgement_users1_idx` (`users_ACKNOWLEDGEDBY`),
  KEY `fk_documentAcknowledgement_documents1_idx` (`documents_DOCUMENTID`),
  CONSTRAINT `fk_documentAcknowledgement_documents1` FOREIGN KEY (`documents_DOCUMENTID`) REFERENCES `documents` (`DOCUMENTID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_documentAcknowledgement_users1` FOREIGN KEY (`users_ACKNOWLEDGEDBY`) REFERENCES `users` (`USERID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentacknowledgement`
--

LOCK TABLES `documentacknowledgement` WRITE;
/*!40000 ALTER TABLE `documentacknowledgement` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentacknowledgement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documents` (
  `DOCUMENTID` int(11) NOT NULL AUTO_INCREMENT,
  `DOCUMENTSTATUS` varchar(45) NOT NULL,
  `DOCUMENTNAME` varchar(45) NOT NULL,
  `DOCUMENTLINK` varchar(2048) NOT NULL,
  `REMARKS` varchar(45) DEFAULT NULL,
  `users_UPLOADEDBY` int(11) NOT NULL,
  `UPLOADEDDATE` varchar(20) NOT NULL,
  `projects_PROJECTID` int(11) NOT NULL,
  PRIMARY KEY (`DOCUMENTID`),
  KEY `fk_documents_users1_idx` (`users_UPLOADEDBY`),
  KEY `fk_documents_projects1_idx` (`projects_PROJECTID`),
  CONSTRAINT `fk_documents_projects1` FOREIGN KEY (`projects_PROJECTID`) REFERENCES `projects` (`PROJECTID`),
  CONSTRAINT `fk_documents_users1` FOREIGN KEY (`users_UPLOADEDBY`) REFERENCES `users` (`USERID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `LOGID` int(11) NOT NULL AUTO_INCREMENT,
  `LOGDETAILS` varchar(1000) NOT NULL,
  `TIMESTAMP` datetime NOT NULL,
  `projects_PROJECTID` int(11) NOT NULL,
  PRIMARY KEY (`LOGID`),
  KEY `fk_logs_projects1_idx` (`projects_PROJECTID`),
  CONSTRAINT `fk_logs_projects1` FOREIGN KEY (`projects_PROJECTID`) REFERENCES `projects` (`PROJECTID`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` VALUES (1,'Mickey Mouse created this project.','2019-02-18 17:25:12',70),(2,'Mickey Mouse has created Main Activity - Market Research.','2019-02-18 17:25:13',70),(3,'Mickey Mouse has created Sub Activity - Prepare Materials.','2019-02-18 17:25:13',70),(4,'Mickey Mouse has tagged Winnie The Pooh as responsible for  Create Questionnaire in Minor Popcorn','2019-02-18 17:25:13',70),(5,'Mickey Mouse has tagged Pig Let as accountable for  Create Questionnaire in Minor Popcorn','2019-02-18 17:25:13',70),(6,'Mickey Mouse has tagged Mickey Mouse as consulted for  Create Questionnaire in Minor Popcorn','2019-02-18 17:25:13',70),(7,'Mickey Mouse has tagged Pig Let as informed for  Create Questionnaire in Minor Popcorn','2019-02-18 17:25:13',70),(8,'Mickey Mouse has tagged Buzz Lightyear as responsible for  Decide Target Audience in Minor Popcorn','2019-02-18 17:25:13',70),(9,'Mickey Mouse has tagged Mickey Mouse as accountable for  Decide Target Audience in Minor Popcorn','2019-02-18 17:25:13',70),(10,'Mickey Mouse has tagged Mickey Mouse as consulted for  Decide Target Audience in Minor Popcorn','2019-02-18 17:25:13',70),(11,'Mickey Mouse has tagged Mickey Mouse as informed for  Decide Target Audience in Minor Popcorn','2019-02-18 17:25:13',70),(12,'Mickey Mouse has created Sub Activity - Conduct Market Research.','2019-02-18 17:25:13',70),(13,'Mickey Mouse has tagged Plu To as responsible for  Distribute Surveys in Minor Popcorn','2019-02-18 17:25:13',70),(14,'Mickey Mouse has tagged Pig Let as accountable for  Distribute Surveys in Minor Popcorn','2019-02-18 17:25:13',70),(15,'Mickey Mouse has tagged Mickey Mouse as consulted for  Distribute Surveys in Minor Popcorn','2019-02-18 17:25:13',70),(16,'Mickey Mouse has tagged Mickey Mouse as informed for  Distribute Surveys in Minor Popcorn','2019-02-18 17:25:13',70),(17,'Mickey Mouse has tagged Simba Gabi as responsible for  Tally Answers in Minor Popcorn','2019-02-18 17:25:13',70),(18,'Mickey Mouse has tagged Pig Let as accountable for  Tally Answers in Minor Popcorn','2019-02-18 17:25:13',70),(19,'Mickey Mouse has tagged Pig Let as consulted for  Tally Answers in Minor Popcorn','2019-02-18 17:25:13',70),(20,'Mickey Mouse has tagged Pig Let as informed for  Tally Answers in Minor Popcorn','2019-02-18 17:25:13',70),(21,'Mickey Mouse has tagged Pum Ba as responsible for  Review and Analyze Results in Minor Popcorn','2019-02-18 17:25:13',70),(22,'Mickey Mouse has tagged Pig Let as accountable for  Review and Analyze Results in Minor Popcorn','2019-02-18 17:25:13',70),(23,'Mickey Mouse has tagged Pig Let as consulted for  Review and Analyze Results in Minor Popcorn','2019-02-18 17:25:13',70),(24,'Mickey Mouse has tagged Pig Let as informed for  Review and Analyze Results in Minor Popcorn','2019-02-18 17:25:13',70),(25,'Mickey Mouse has created Main Activity - Marketing Promotion.','2019-02-18 17:25:13',70),(26,'Mickey Mouse has created Sub Activity - Conceptualization.','2019-02-18 17:25:13',70),(27,'Mickey Mouse has tagged Simba Gabi as responsible for  Create Promotion in Minor Popcorn','2019-02-18 17:25:13',70),(28,'Mickey Mouse has tagged Buzz Lightyear as accountable for  Create Promotion in Minor Popcorn','2019-02-18 17:25:13',70),(29,'Mickey Mouse has tagged Buzz Lightyear as consulted for  Create Promotion in Minor Popcorn','2019-02-18 17:25:13',70),(30,'Mickey Mouse has tagged Buzz Lightyear as informed for  Create Promotion in Minor Popcorn','2019-02-18 17:25:13',70),(31,'Mickey Mouse has tagged Pum Ba as responsible for  Create Mechanics in Minor Popcorn','2019-02-18 17:25:13',70),(32,'Mickey Mouse has tagged Pig Let as accountable for  Create Mechanics in Minor Popcorn','2019-02-18 17:25:13',70),(33,'Mickey Mouse has tagged Pig Let as consulted for  Create Mechanics in Minor Popcorn','2019-02-18 17:25:13',70),(34,'Mickey Mouse has tagged Pig Let as informed for  Create Mechanics in Minor Popcorn','2019-02-18 17:25:13',70),(35,'Mickey Mouse has created Main Activity - Design.','2019-02-18 17:25:13',70),(36,'Mickey Mouse has created Sub Activity - Conceptualize Design.','2019-02-18 17:25:13',70),(37,'Mickey Mouse has tagged Buzz Lightyear as responsible for  Draft Posters in Minor Popcorn','2019-02-18 17:25:13',70),(38,'Mickey Mouse has tagged Pig Let as accountable for  Draft Posters in Minor Popcorn','2019-02-18 17:25:13',70),(39,'Mickey Mouse has tagged Mickey Mouse as consulted for  Draft Posters in Minor Popcorn','2019-02-18 17:25:13',70),(40,'Mickey Mouse has tagged Mickey Mouse as informed for  Draft Posters in Minor Popcorn','2019-02-18 17:25:13',70),(41,'Mickey Mouse has tagged Plu To as responsible for  Sketch Posters in Minor Popcorn','2019-02-18 17:25:13',70),(42,'Mickey Mouse has tagged Buzz Lightyear as accountable for  Sketch Posters in Minor Popcorn','2019-02-18 17:25:13',70),(43,'Mickey Mouse has tagged Pig Let as consulted for  Sketch Posters in Minor Popcorn','2019-02-18 17:25:13',70),(44,'Mickey Mouse has tagged Mickey Mouse as informed for  Sketch Posters in Minor Popcorn','2019-02-18 17:25:13',70),(45,'Mickey Mouse has created Sub Activity - Submit for Approval.','2019-02-18 17:25:13',70),(46,'Mickey Mouse has tagged Pig Let as responsible for  Approve Designs in Minor Popcorn','2019-02-18 17:25:13',70),(47,'Mickey Mouse has tagged Mickey Mouse as accountable for  Approve Designs in Minor Popcorn','2019-02-18 17:25:13',70),(48,'Mickey Mouse has tagged Mickey Mouse as consulted for  Approve Designs in Minor Popcorn','2019-02-18 17:25:13',70),(49,'Mickey Mouse has tagged Mickey Mouse as informed for  Approve Designs in Minor Popcorn','2019-02-18 17:25:13',70),(50,'Mickey Mouse has created Sub Activity - Production.','2019-02-18 17:25:13',70),(51,'Mickey Mouse has tagged Buzz Lightyear as responsible for  Print Posters in Minor Popcorn','2019-02-18 17:25:13',70),(52,'Mickey Mouse has tagged Pig Let as accountable for  Print Posters in Minor Popcorn','2019-02-18 17:25:13',70),(53,'Mickey Mouse has tagged Mickey Mouse as consulted for  Print Posters in Minor Popcorn','2019-02-18 17:25:13',70),(54,'Mickey Mouse has tagged Mickey Mouse as informed for  Print Posters in Minor Popcorn','2019-02-18 17:25:13',70),(55,'Mickey Mouse has created Main Activity - Launch Promotion.','2019-02-18 17:25:13',70),(56,'Mickey Mouse has created Sub Activity - Finalize Promotion.','2019-02-18 17:25:13',70),(57,'Mickey Mouse has tagged Pig Let as responsible for  Finalize Materials in Minor Popcorn','2019-02-18 17:25:13',70),(58,'Mickey Mouse has tagged Mickey Mouse as accountable for  Finalize Materials in Minor Popcorn','2019-02-18 17:25:13',70),(59,'Mickey Mouse has tagged Mickey Mouse as consulted for  Finalize Materials in Minor Popcorn','2019-02-18 17:25:13',70),(60,'Mickey Mouse has tagged Mickey Mouse as informed for  Finalize Materials in Minor Popcorn','2019-02-18 17:25:13',70),(61,'Mickey Mouse has tagged Buzz Lightyear as responsible for  Promo Mechanics in Minor Popcorn','2019-02-18 17:25:13',70),(62,'Mickey Mouse has tagged Mickey Mouse as accountable for  Promo Mechanics in Minor Popcorn','2019-02-18 17:25:13',70),(63,'Mickey Mouse has tagged Mickey Mouse as consulted for  Promo Mechanics in Minor Popcorn','2019-02-18 17:25:13',70),(64,'Mickey Mouse has tagged Mickey Mouse as informed for  Promo Mechanics in Minor Popcorn','2019-02-18 17:25:13',70),(65,'Mickey Mouse has created Sub Activity - Distribute Promos.','2019-02-18 17:25:13',70),(66,'Mickey Mouse has tagged Pum Ba as responsible for  Deliver Promos in Minor Popcorn','2019-02-18 17:25:13',70),(67,'Mickey Mouse has tagged Pig Let as accountable for  Deliver Promos in Minor Popcorn','2019-02-18 17:25:13',70),(68,'Mickey Mouse has tagged Mickey Mouse as consulted for  Deliver Promos in Minor Popcorn','2019-02-18 17:25:13',70),(69,'Mickey Mouse has tagged Mickey Mouse as informed for  Deliver Promos in Minor Popcorn','2019-02-18 17:25:14',70);
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `NOTIFICATIONID` int(11) NOT NULL AUTO_INCREMENT,
  `users_USERID` int(11) NOT NULL,
  `DETAILS` text NOT NULL,
  `TIMESTAMP` varchar(20) NOT NULL,
  `status` varchar(45) NOT NULL COMMENT 'READ\nUNREAD',
  `tasks_TASKID` int(11) DEFAULT NULL,
  `projects_PROJECTID` int(11) DEFAULT NULL,
  `TYPE` int(5) DEFAULT NULL COMMENT 'Notification Types (where it redirects to)\n1 - project gantt\n2 - delegate\n3 - to do\n4 - monitor\n5 - project documents\n6 - rfc\n7 - project summary',
  PRIMARY KEY (`NOTIFICATIONID`),
  KEY `fk_notifications_users1_idx` (`users_USERID`),
  KEY `fk_notifications_tasks1_idx` (`tasks_TASKID`),
  KEY `fk_notifications_projects1_idx` (`projects_PROJECTID`),
  CONSTRAINT `fk_notifications_projects1` FOREIGN KEY (`projects_PROJECTID`) REFERENCES `projects` (`PROJECTID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_notifications_tasks1` FOREIGN KEY (`tasks_TASKID`) REFERENCES `tasks` (`TASKID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_notifications_users1` FOREIGN KEY (`users_USERID`) REFERENCES `users` (`USERID`)
) ENGINE=InnoDB AUTO_INCREMENT=1620 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1564,25,'Mickey Mouse has tagged you as responsbile for Create Questionnaire in Minor Popcorn.','2019-02-18 17:25:13','Unread',607,70,2),(1565,11,'Mickey Mouse has tagged you as accountable for Create Questionnaire in Minor Popcorn.','2019-02-18 17:25:13','Unread',607,70,2),(1566,4,'Mickey Mouse has tagged you as consulted for Create Questionnaire in Minor Popcorn.','2019-02-18 17:25:13','Unread',607,70,2),(1567,11,'Mickey Mouse has tagged you as informed for Create Questionnaire in Minor Popcorn.','2019-02-18 17:25:13','Unread',607,70,2),(1568,12,'Mickey Mouse has tagged you as responsbile for Decide Target Audience in Minor Popcorn.','2019-02-18 17:25:13','Unread',608,70,2),(1569,4,'Mickey Mouse has tagged you as accountable for Decide Target Audience in Minor Popcorn.','2019-02-18 17:25:13','Unread',608,70,2),(1570,4,'Mickey Mouse has tagged you as consulted for Decide Target Audience in Minor Popcorn.','2019-02-18 17:25:13','Unread',608,70,2),(1571,4,'Mickey Mouse has tagged you as informed for Decide Target Audience in Minor Popcorn.','2019-02-18 17:25:13','Unread',608,70,2),(1572,26,'Mickey Mouse has tagged you as responsbile for Distribute Surveys in Minor Popcorn.','2019-02-18 17:25:13','Unread',610,70,2),(1573,11,'Mickey Mouse has tagged you as accountable for Distribute Surveys in Minor Popcorn.','2019-02-18 17:25:13','Unread',610,70,2),(1574,4,'Mickey Mouse has tagged you as consulted for Distribute Surveys in Minor Popcorn.','2019-02-18 17:25:13','Unread',610,70,2),(1575,4,'Mickey Mouse has tagged you as informed for Distribute Surveys in Minor Popcorn.','2019-02-18 17:25:13','Unread',610,70,2),(1576,28,'Mickey Mouse has tagged you as responsbile for Tally Answers in Minor Popcorn.','2019-02-18 17:25:13','Unread',611,70,2),(1577,11,'Mickey Mouse has tagged you as accountable for Tally Answers in Minor Popcorn.','2019-02-18 17:25:13','Unread',611,70,2),(1578,11,'Mickey Mouse has tagged you as consulted for Tally Answers in Minor Popcorn.','2019-02-18 17:25:13','Unread',611,70,2),(1579,11,'Mickey Mouse has tagged you as informed for Tally Answers in Minor Popcorn.','2019-02-18 17:25:13','Unread',611,70,2),(1580,27,'Mickey Mouse has tagged you as responsbile for Review and Analyze Results in Minor Popcorn.','2019-02-18 17:25:13','Unread',612,70,2),(1581,11,'Mickey Mouse has tagged you as accountable for Review and Analyze Results in Minor Popcorn.','2019-02-18 17:25:13','Unread',612,70,2),(1582,11,'Mickey Mouse has tagged you as consulted for Review and Analyze Results in Minor Popcorn.','2019-02-18 17:25:13','Unread',612,70,2),(1583,11,'Mickey Mouse has tagged you as informed for Review and Analyze Results in Minor Popcorn.','2019-02-18 17:25:13','Unread',612,70,2),(1584,28,'Mickey Mouse has tagged you as responsbile for Create Promotion in Minor Popcorn.','2019-02-18 17:25:13','Unread',615,70,2),(1585,12,'Mickey Mouse has tagged you as accountable for Create Promotion in Minor Popcorn.','2019-02-18 17:25:13','Unread',615,70,2),(1586,12,'Mickey Mouse has tagged you as consulted for Create Promotion in Minor Popcorn.','2019-02-18 17:25:13','Unread',615,70,2),(1587,12,'Mickey Mouse has tagged you as informed for Create Promotion in Minor Popcorn.','2019-02-18 17:25:13','Unread',615,70,2),(1588,27,'Mickey Mouse has tagged you as responsbile for Create Mechanics in Minor Popcorn.','2019-02-18 17:25:13','Unread',616,70,2),(1589,11,'Mickey Mouse has tagged you as accountable for Create Mechanics in Minor Popcorn.','2019-02-18 17:25:13','Unread',616,70,2),(1590,11,'Mickey Mouse has tagged you as consulted for Create Mechanics in Minor Popcorn.','2019-02-18 17:25:13','Unread',616,70,2),(1591,11,'Mickey Mouse has tagged you as informed for Create Mechanics in Minor Popcorn.','2019-02-18 17:25:13','Unread',616,70,2),(1592,12,'Mickey Mouse has tagged you as responsbile for Draft Posters in Minor Popcorn.','2019-02-18 17:25:13','Unread',619,70,2),(1593,11,'Mickey Mouse has tagged you as accountable for Draft Posters in Minor Popcorn.','2019-02-18 17:25:13','Unread',619,70,2),(1594,4,'Mickey Mouse has tagged you as consulted for Draft Posters in Minor Popcorn.','2019-02-18 17:25:13','Unread',619,70,2),(1595,4,'Mickey Mouse has tagged you as informed for Draft Posters in Minor Popcorn.','2019-02-18 17:25:13','Unread',619,70,2),(1596,26,'Mickey Mouse has tagged you as responsbile for Sketch Posters in Minor Popcorn.','2019-02-18 17:25:13','Unread',620,70,2),(1597,12,'Mickey Mouse has tagged you as accountable for Sketch Posters in Minor Popcorn.','2019-02-18 17:25:13','Unread',620,70,2),(1598,11,'Mickey Mouse has tagged you as consulted for Sketch Posters in Minor Popcorn.','2019-02-18 17:25:13','Unread',620,70,2),(1599,4,'Mickey Mouse has tagged you as informed for Sketch Posters in Minor Popcorn.','2019-02-18 17:25:13','Unread',620,70,2),(1600,11,'Mickey Mouse has tagged you as responsbile for Approve Designs in Minor Popcorn.','2019-02-18 17:25:13','Unread',622,70,2),(1601,4,'Mickey Mouse has tagged you as accountable for Approve Designs in Minor Popcorn.','2019-02-18 17:25:13','Unread',622,70,2),(1602,4,'Mickey Mouse has tagged you as consulted for Approve Designs in Minor Popcorn.','2019-02-18 17:25:13','Unread',622,70,2),(1603,4,'Mickey Mouse has tagged you as informed for Approve Designs in Minor Popcorn.','2019-02-18 17:25:13','Unread',622,70,2),(1604,12,'Mickey Mouse has tagged you as responsbile for Print Posters in Minor Popcorn.','2019-02-18 17:25:13','Unread',624,70,2),(1605,11,'Mickey Mouse has tagged you as accountable for Print Posters in Minor Popcorn.','2019-02-18 17:25:13','Unread',624,70,2),(1606,4,'Mickey Mouse has tagged you as consulted for Print Posters in Minor Popcorn.','2019-02-18 17:25:13','Unread',624,70,2),(1607,4,'Mickey Mouse has tagged you as informed for Print Posters in Minor Popcorn.','2019-02-18 17:25:13','Unread',624,70,2),(1608,11,'Mickey Mouse has tagged you as responsbile for Finalize Materials in Minor Popcorn.','2019-02-18 17:25:13','Unread',627,70,2),(1609,4,'Mickey Mouse has tagged you as accountable for Finalize Materials in Minor Popcorn.','2019-02-18 17:25:13','Unread',627,70,2),(1610,4,'Mickey Mouse has tagged you as consulted for Finalize Materials in Minor Popcorn.','2019-02-18 17:25:13','Unread',627,70,2),(1611,4,'Mickey Mouse has tagged you as informed for Finalize Materials in Minor Popcorn.','2019-02-18 17:25:13','Unread',627,70,2),(1612,12,'Mickey Mouse has tagged you as responsbile for Promo Mechanics in Minor Popcorn.','2019-02-18 17:25:13','Unread',628,70,2),(1613,4,'Mickey Mouse has tagged you as accountable for Promo Mechanics in Minor Popcorn.','2019-02-18 17:25:13','Unread',628,70,2),(1614,4,'Mickey Mouse has tagged you as consulted for Promo Mechanics in Minor Popcorn.','2019-02-18 17:25:13','Unread',628,70,2),(1615,4,'Mickey Mouse has tagged you as informed for Promo Mechanics in Minor Popcorn.','2019-02-18 17:25:13','Unread',628,70,2),(1616,27,'Mickey Mouse has tagged you as responsbile for Deliver Promos in Minor Popcorn.','2019-02-18 17:25:13','Unread',630,70,2),(1617,11,'Mickey Mouse has tagged you as accountable for Deliver Promos in Minor Popcorn.','2019-02-18 17:25:13','Unread',630,70,2),(1618,4,'Mickey Mouse has tagged you as consulted for Deliver Promos in Minor Popcorn.','2019-02-18 17:25:13','Unread',630,70,2),(1619,4,'Mickey Mouse has tagged you as informed for Deliver Promos in Minor Popcorn.','2019-02-18 17:25:14','Unread',630,70,2);
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `PROJECTID` int(11) NOT NULL AUTO_INCREMENT,
  `PROJECTTITLE` varchar(100) NOT NULL,
  `PROJECTSTARTDATE` varchar(20) NOT NULL,
  `PROJECTENDDATE` varchar(20) NOT NULL,
  `PROJECTDESCRIPTION` text NOT NULL,
  `PROJECTSTATUS` varchar(45) NOT NULL,
  `users_USERID` int(11) NOT NULL COMMENT 'PROJECT OWNER',
  `templates_PROJECTID` int(11) DEFAULT NULL,
  `PROJECTACTUALSTARTDATE` varchar(20) DEFAULT NULL,
  `PROJECTACTUALENDDATE` varchar(20) DEFAULT NULL,
  `PROJECTADJUSTEDSTARTDATE` varchar(20) DEFAULT NULL,
  `PROJECTADJUSTEDENDDATE` varchar(20) DEFAULT NULL,
  `DATECREATED` varchar(20) DEFAULT NULL,
  `PROJECTTYPE` int(11) DEFAULT NULL,
  PRIMARY KEY (`PROJECTID`),
  KEY `fk_projects_users1_idx` (`users_USERID`),
  KEY `fk_projects_templates1_idx` (`templates_PROJECTID`),
  CONSTRAINT `fk_projects_templates1` FOREIGN KEY (`templates_PROJECTID`) REFERENCES `templates` (`TEMPLATEID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_projects_users1` FOREIGN KEY (`users_USERID`) REFERENCES `users` (`USERID`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (70,'Minor Popcorn','2018-10-20','2018-12-05','Marketing Promotion for 4Q 2018','Ongoing',4,NULL,'2018-10-20',NULL,NULL,NULL,'2019-02-18',3);
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `raci`
--

DROP TABLE IF EXISTS `raci`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `raci` (
  `RACIID` int(11) NOT NULL AUTO_INCREMENT,
  `ROLE` varchar(45) NOT NULL COMMENT 'ROLE LEGEND:\n1 - Responsible\n2 - Accountable\n3 - Consulted\n4 - Informed\n5 - Previous Responsible with Delegation Ability\n0 - Current Responsible with Delegation Ability',
  `users_USERID` int(11) NOT NULL,
  `tasks_TASKID` int(11) NOT NULL,
  `STATUS` varchar(45) NOT NULL COMMENT 'Current or Updated',
  PRIMARY KEY (`RACIID`),
  KEY `fk_raci_users1_idx` (`users_USERID`),
  KEY `fk_raci_tasks1_idx` (`tasks_TASKID`),
  CONSTRAINT `fk_raci_tasks1` FOREIGN KEY (`tasks_TASKID`) REFERENCES `tasks` (`TASKID`),
  CONSTRAINT `fk_raci_users1` FOREIGN KEY (`users_USERID`) REFERENCES `users` (`USERID`)
) ENGINE=InnoDB AUTO_INCREMENT=2133 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `raci`
--

LOCK TABLES `raci` WRITE;
/*!40000 ALTER TABLE `raci` DISABLE KEYS */;
INSERT INTO `raci` VALUES (2049,'1',25,607,'Current'),(2050,'5',4,605,'Current'),(2051,'5',4,606,'Current'),(2052,'2',11,607,'Current'),(2053,'3',4,607,'Current'),(2054,'4',11,607,'Current'),(2055,'1',12,608,'Current'),(2056,'5',4,605,'Current'),(2057,'5',4,606,'Current'),(2058,'2',4,608,'Current'),(2059,'3',4,608,'Current'),(2060,'4',4,608,'Current'),(2061,'1',26,610,'Current'),(2062,'5',4,605,'Current'),(2063,'5',4,609,'Current'),(2064,'2',11,610,'Current'),(2065,'3',4,610,'Current'),(2066,'4',4,610,'Current'),(2067,'1',28,611,'Current'),(2068,'5',4,605,'Current'),(2069,'5',4,609,'Current'),(2070,'2',11,611,'Current'),(2071,'3',11,611,'Current'),(2072,'4',11,611,'Current'),(2073,'1',27,612,'Current'),(2074,'5',4,605,'Current'),(2075,'5',4,609,'Current'),(2076,'2',11,612,'Current'),(2077,'3',11,612,'Current'),(2078,'4',11,612,'Current'),(2079,'1',28,615,'Current'),(2080,'5',4,613,'Current'),(2081,'5',4,614,'Current'),(2082,'2',12,615,'Current'),(2083,'3',12,615,'Current'),(2084,'4',12,615,'Current'),(2085,'1',27,616,'Current'),(2086,'5',4,613,'Current'),(2087,'5',4,614,'Current'),(2088,'2',11,616,'Current'),(2089,'3',11,616,'Current'),(2090,'4',11,616,'Current'),(2091,'1',12,619,'Current'),(2092,'5',4,617,'Current'),(2093,'5',4,618,'Current'),(2094,'2',11,619,'Current'),(2095,'3',4,619,'Current'),(2096,'4',4,619,'Current'),(2097,'1',26,620,'Current'),(2098,'5',4,617,'Current'),(2099,'5',4,618,'Current'),(2100,'2',12,620,'Current'),(2101,'3',11,620,'Current'),(2102,'4',4,620,'Current'),(2103,'1',11,622,'Current'),(2104,'5',4,617,'Current'),(2105,'5',4,621,'Current'),(2106,'2',4,622,'Current'),(2107,'3',4,622,'Current'),(2108,'4',4,622,'Current'),(2109,'1',12,624,'Current'),(2110,'5',4,617,'Current'),(2111,'5',4,623,'Current'),(2112,'2',11,624,'Current'),(2113,'3',4,624,'Current'),(2114,'4',4,624,'Current'),(2115,'1',11,627,'Current'),(2116,'5',4,625,'Current'),(2117,'5',4,626,'Current'),(2118,'2',4,627,'Current'),(2119,'3',4,627,'Current'),(2120,'4',4,627,'Current'),(2121,'1',12,628,'Current'),(2122,'5',4,625,'Current'),(2123,'5',4,626,'Current'),(2124,'2',4,628,'Current'),(2125,'3',4,628,'Current'),(2126,'4',4,628,'Current'),(2127,'1',27,630,'Current'),(2128,'5',4,625,'Current'),(2129,'5',4,629,'Current'),(2130,'2',11,630,'Current'),(2131,'3',4,630,'Current'),(2132,'4',4,630,'Current');
/*!40000 ALTER TABLE `raci` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasks` (
  `TASKID` int(11) NOT NULL AUTO_INCREMENT,
  `TASKTITLE` varchar(100) NOT NULL,
  `TASKSTARTDATE` varchar(20) DEFAULT NULL,
  `TASKENDDATE` varchar(20) DEFAULT NULL,
  `TASKSTATUS` varchar(45) NOT NULL,
  `TASKREMARKS` varchar(1000) DEFAULT NULL,
  `CATEGORY` varchar(45) NOT NULL,
  `projects_PROJECTID` int(11) NOT NULL,
  `TASKACTUALSTARTDATE` varchar(20) DEFAULT NULL,
  `TASKACTUALENDDATE` varchar(20) DEFAULT NULL,
  `tasks_TASKPARENT` int(11) DEFAULT NULL,
  `TASKADJUSTEDSTARTDATE` varchar(20) DEFAULT NULL,
  `TASKADJUSTEDENDDATE` varchar(20) DEFAULT NULL,
  `TEMPLATETASKID` int(11) DEFAULT NULL,
  PRIMARY KEY (`TASKID`),
  KEY `fk_tasks_projects1_idx` (`projects_PROJECTID`),
  KEY `fk_tasks_tasks1_idx` (`tasks_TASKPARENT`),
  CONSTRAINT `fk_tasks_projects1` FOREIGN KEY (`projects_PROJECTID`) REFERENCES `projects` (`PROJECTID`),
  CONSTRAINT `fk_tasks_tasks1` FOREIGN KEY (`tasks_TASKPARENT`) REFERENCES `tasks` (`TASKID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=631 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES (605,'Market Research','2018-10-20','2018-11-10','Complete',NULL,'1',70,'2018-10-20','2018-11-10',NULL,NULL,NULL,NULL),(606,'Prepare Materials','2018-10-20','2018-10-26','Complete',NULL,'2',70,'2018-10-20','2018-10-26',605,NULL,NULL,NULL),(607,'Create Questionnaire','2018-10-20','2018-10-24','Complete',NULL,'3',70,'2018-10-20','2018-10-24',606,NULL,NULL,NULL),(608,'Decide Target Audience','2018-10-24','2018-10-26','Complete',NULL,'3',70,'2018-10-24','2018-10-26',606,NULL,NULL,NULL),(609,'Conduct Market Research','2018-10-27','2018-11-10','Complete',NULL,'2',70,'2018-10-27','2018-11-10',605,NULL,NULL,NULL),(610,'Distribute Surveys','2018-10-27','2018-11-03','Complete',NULL,'3',70,'2018-10-27','2018-11-03',609,NULL,NULL,NULL),(611,'Tally Answers','2018-11-03','2018-11-06','Complete',NULL,'3',70,'2018-11-03','2018-11-06',609,NULL,NULL,NULL),(612,'Review and Analyze Results','2018-11-06','2018-11-10','Complete',NULL,'3',70,'2018-11-06','2018-11-10',609,NULL,NULL,NULL),(613,'Marketing Promotion','2018-11-11','2018-11-16','Complete',NULL,'1',70,'2018-11-11','2018-11-16',NULL,NULL,NULL,NULL),(614,'Conceptualization','2018-11-11','2018-11-16','Complete',NULL,'2',70,'2018-11-11','2018-11-16',613,NULL,NULL,NULL),(615,'Create Promotion','2018-11-11','2018-11-16','Complete',NULL,'3',70,'2018-11-11','2018-11-16',614,NULL,NULL,NULL),(616,'Create Mechanics','2018-11-11','2018-11-16','Complete',NULL,'3',70,'2018-11-11','2018-11-16',614,NULL,NULL,NULL),(617,'Design','2018-11-17','2018-11-28','Complete',NULL,'1',70,'2018-11-17','2018-11-28',NULL,NULL,NULL,NULL),(618,'Conceptualize Design','2018-11-17','2018-11-21','Complete',NULL,'2',70,'2018-11-17','2018-11-21',617,NULL,NULL,NULL),(619,'Draft Posters','2018-11-17','2018-11-19','Complete',NULL,'3',70,'2018-11-17','2018-11-19',618,NULL,NULL,NULL),(620,'Sketch Posters','2018-11-19','2018-11-21','Complete',NULL,'3',70,'2018-11-19','2018-11-21',618,NULL,NULL,NULL),(621,'Submit for Approval','2018-11-21','2018-11-23','Complete',NULL,'2',70,'2018-11-21','2018-11-23',617,NULL,NULL,NULL),(622,'Approve Designs','2018-11-21','2018-11-23','Complete',NULL,'3',70,'2018-11-21','2018-11-23',621,NULL,NULL,NULL),(623,'Production','2018-11-23','2018-11-28','Complete',NULL,'2',70,'2018-11-23','2018-11-28',617,NULL,NULL,NULL),(624,'Print Posters','2018-11-23','2018-11-28','Complete',NULL,'3',70,'2018-11-23','2018-11-28',623,NULL,NULL,NULL),(625,'Launch Promotion','2018-11-29','2018-12-05','Ongoing',NULL,'1',70,'2018-11-29',NULL,NULL,NULL,NULL,NULL),(626,'Finalize Promotion','2018-11-29','2018-12-01','Ongoing',NULL,'2',70,'2018-11-29',NULL,625,NULL,NULL,NULL),(627,'Finalize Materials','2018-11-29','2018-12-01','Ongoing',NULL,'3',70,'2018-11-29',NULL,626,NULL,NULL,NULL),(628,'Promo Mechanics','2018-11-29','2018-12-01','Ongoing',NULL,'3',70,'2018-11-29',NULL,626,NULL,NULL,NULL),(629,'Distribute Promos','2018-12-01','2018-12-05','Ongoing',NULL,'2',70,'2018-12-01',NULL,625,NULL,NULL,NULL),(630,'Deliver Promos','2018-12-01','2018-12-05','Ongoing',NULL,'3',70,'2018-12-01',NULL,629,NULL,NULL,NULL);
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taskupdates`
--

DROP TABLE IF EXISTS `taskupdates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `taskupdates` (
  `COMMENTID` int(11) NOT NULL AUTO_INCREMENT,
  `tasks_TASKID` int(11) NOT NULL,
  `COMMENT` text NOT NULL,
  `users_COMMENTEDBY` int(11) NOT NULL,
  `COMMENTDATE` varchar(45) NOT NULL,
  PRIMARY KEY (`COMMENTID`),
  KEY `users_COMMENTEDBY_idx` (`users_COMMENTEDBY`),
  KEY `tasks_TASKID_idx` (`tasks_TASKID`),
  CONSTRAINT `tasks_TASKID` FOREIGN KEY (`tasks_TASKID`) REFERENCES `tasks` (`TASKID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `users_COMMENTEDBY` FOREIGN KEY (`users_COMMENTEDBY`) REFERENCES `users` (`USERID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taskupdates`
--

LOCK TABLES `taskupdates` WRITE;
/*!40000 ALTER TABLE `taskupdates` DISABLE KEYS */;
/*!40000 ALTER TABLE `taskupdates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `templates`
--

DROP TABLE IF EXISTS `templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `templates` (
  `TEMPLATEID` int(11) NOT NULL AUTO_INCREMENT,
  `projects_PROJECTID` int(11) NOT NULL,
  `PROJECTTITLE` varchar(100) NOT NULL,
  `PROJECTSTARTDATE` varchar(20) NOT NULL,
  `PROJECTENDDATE` varchar(20) NOT NULL,
  `PROJECTDESCRIPTION` text NOT NULL,
  `users_USERID` int(11) NOT NULL COMMENT 'PROJECT OWNER',
  PRIMARY KEY (`TEMPLATEID`),
  KEY `fk_projects_users1_idx` (`users_USERID`),
  KEY `fk_templates_projects_idx` (`projects_PROJECTID`),
  CONSTRAINT `fk_projects_users10` FOREIGN KEY (`users_USERID`) REFERENCES `users` (`USERID`),
  CONSTRAINT `fk_templates_projects` FOREIGN KEY (`projects_PROJECTID`) REFERENCES `projects` (`PROJECTID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `templates`
--

LOCK TABLES `templates` WRITE;
/*!40000 ALTER TABLE `templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `USERID` int(11) NOT NULL AUTO_INCREMENT,
  `FIRSTNAME` varchar(100) NOT NULL,
  `LASTNAME` varchar(100) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `PASSWORD` varchar(100) NOT NULL,
  `POSITION` varchar(100) NOT NULL,
  `departments_DEPARTMENTID` int(11) DEFAULT NULL,
  `usertype_USERTYPEID` int(11) NOT NULL,
  `users_SUPERVISORS` int(11) DEFAULT NULL,
  `IDPIC` varchar(2048) DEFAULT NULL,
  `isACT` int(11) DEFAULT '1',
  `JOBDESCRIPTION` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`USERID`),
  KEY `fk_users_departments_idx` (`departments_DEPARTMENTID`),
  KEY `fk_users_usertype1_idx` (`usertype_USERTYPEID`),
  KEY `fk_users_users_idx` (`users_SUPERVISORS`),
  CONSTRAINT `fk_users_departments` FOREIGN KEY (`departments_DEPARTMENTID`) REFERENCES `departments` (`DEPARTMENTID`),
  CONSTRAINT `fk_users_users` FOREIGN KEY (`users_SUPERVISORS`) REFERENCES `users` (`USERID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_usertype1` FOREIGN KEY (`usertype_USERTYPEID`) REFERENCES `usertype` (`USERTYPEID`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin','admin@tei.com','$2y$10$8v73yfAUgvJAI.oGOdjt/OL8w/oq09igM11JNAHeWcqWV02RlxHyW','Admin',6,1,NULL,'http://localhost/Kernel/assets/media/idpic.png',1,NULL),(2,'Walt','Disney','president@tei.com','$2y$10$8v73yfAUgvJAI.oGOdjt/OL8w/oq09igM11JNAHeWcqWV02RlxHyW','President',1,2,NULL,'http://localhost/Kernel/assets/media/idpic.png',1,'Oversee entire company'),(4,'Mickey','Mouse','mkthead@tei.com','$2y$10$8v73yfAUgvJAI.oGOdjt/OL8w/oq09igM11JNAHeWcqWV02RlxHyW','Marketing Manager',2,3,2,'http://localhost/Kernel/assets/media/mickey.jpg',1,'Oversee the Department'),(5,'Knee','Moe','finhead@tei.com','$2y$10$8v73yfAUgvJAI.oGOdjt/OL8w/oq09igM11JNAHeWcqWV02RlxHyW','Finance Manager',3,3,2,'http://localhost/Kernel/assets/media/idpic.png',1,'Oversee the Department'),(6,'Donald','Duck','prochead@tei.com','$2y$10$8v73yfAUgvJAI.oGOdjt/OL8w/oq09igM11JNAHeWcqWV02RlxHyW','Procurement Manager',4,3,2,'http://localhost/Kernel/assets/media/donald.png',1,'Oversee the Department'),(7,'Lee','Low','hrhead@tei.com','$2y$10$8v73yfAUgvJAI.oGOdjt/OL8w/oq09igM11JNAHeWcqWV02RlxHyW','Human Resource Manager',5,3,2,'http://localhost/Kernel/assets/media/idpic.png',1,'Oversee the Department'),(8,'Moo','Lan','mishead@tei.com','$2y$10$8v73yfAUgvJAI.oGOdjt/OL8w/oq09igM11JNAHeWcqWV02RlxHyW','Management Information System Manager',6,3,2,'http://localhost/Kernel/assets/media/idpic.png',1,'Oversee the Department'),(9,'Oh','Laugh','storeopshead@tei.com','$2y$10$8v73yfAUgvJAI.oGOdjt/OL8w/oq09igM11JNAHeWcqWV02RlxHyW','Store Operations Manager',7,3,2,'http://localhost/Kernel/assets/media/idpic.png',1,'Oversee the Department'),(11,'Pig','Let','mktsup1@tei.com','$2y$10$8v73yfAUgvJAI.oGOdjt/OL8w/oq09igM11JNAHeWcqWV02RlxHyW','Marketing Supervisor',2,4,4,'http://localhost/Kernel/assets/media/piglet.png',1,'Market Research Head'),(12,'Buzz','Lightyear','mktsup2@tei.com','$2y$10$8v73yfAUgvJAI.oGOdjt/OL8w/oq09igM11JNAHeWcqWV02RlxHyW','Marketing Supervisor',2,4,4,'http://localhost/Kernel/assets/media/buzz.jpg',1,'Design Head'),(25,'Winnie','The Pooh','mktstaff1@tei.com','$2y$10$8v73yfAUgvJAI.oGOdjt/OL8w/oq09igM11JNAHeWcqWV02RlxHyW','Marketing Staff',2,5,11,'http://localhost/Kernel/assets/media/winnie.jpg',1,'Market Research'),(26,'Plu','To','mktstaff2@tei.com','$2y$10$8v73yfAUgvJAI.oGOdjt/OL8w/oq09igM11JNAHeWcqWV02RlxHyW','Marketing Staff',2,5,11,'http://localhost/Kernel/assets/media/pluto.png',1,'Design'),(27,'Pum','Ba','mktstaff3@tei.com','$2y$10$8v73yfAUgvJAI.oGOdjt/OL8w/oq09igM11JNAHeWcqWV02RlxHyW','Marketing Staff',2,5,12,'http://localhost/Kernel/assets/media/pumba.jpg',1,'Research & Development'),(28,'Simba','Gabi','mktstaff4@tei.com','$2y$10$8v73yfAUgvJAI.oGOdjt/OL8w/oq09igM11JNAHeWcqWV02RlxHyW','Marketing Staff',2,5,12,'http://localhost/Kernel/assets/media/simba.jpg',1,'Payroll');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usertype`
--

DROP TABLE IF EXISTS `usertype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usertype` (
  `USERTYPEID` int(11) NOT NULL AUTO_INCREMENT,
  `USERTYPE` varchar(45) NOT NULL,
  `isAct` int(11) NOT NULL COMMENT '1 - Active\n0 - Inactive',
  PRIMARY KEY (`USERTYPEID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usertype`
--

LOCK TABLES `usertype` WRITE;
/*!40000 ALTER TABLE `usertype` DISABLE KEYS */;
INSERT INTO `usertype` VALUES (1,'Admin',1),(2,'Executive',1),(3,'Department Head',1),(4,'Department Supervisor',1),(5,'Department Staff',1),(6,'Janitorzz',0);
/*!40000 ALTER TABLE `usertype` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-02-18 17:26:18
