CREATE DATABASE  IF NOT EXISTS `Kernel` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `Kernel`;
-- MySQL dump 10.13  Distrib 5.7.17, for macos10.12 (x86_64)
--
-- Host: 127.0.0.1    Database: Kernel
-- ------------------------------------------------------
-- Server version	5.7.18

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
-- Table structure for table `assessmentDepartment`
--

DROP TABLE IF EXISTS `assessmentDepartment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assessmentDepartment` (
  `DEPARTMENTASSESSMENTID` int(11) NOT NULL AUTO_INCREMENT,
  `ACCURACY` double DEFAULT NULL,
  `COMPLETENESS` double DEFAULT NULL,
  `TIMELINESS` double DEFAULT NULL,
  `departments_DEPARTMENTID` int(11) DEFAULT NULL,
  `DATE` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`DEPARTMENTASSESSMENTID`),
  KEY `fk_departmentAssessment_departments1_idx` (`departments_DEPARTMENTID`),
  CONSTRAINT `fk_departmentAssessment_departments1` FOREIGN KEY (`departments_DEPARTMENTID`) REFERENCES `departments` (`DEPARTMENTID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assessmentDepartment`
--

LOCK TABLES `assessmentDepartment` WRITE;
/*!40000 ALTER TABLE `assessmentDepartment` DISABLE KEYS */;
INSERT INTO `assessmentDepartment` VALUES (1,NULL,12.34,64.21,2,'2018-07-23'),(2,NULL,56.78,10.92,3,'2018-07-23'),(3,NULL,89.01,38.74,4,'2018-07-23'),(4,NULL,23.45,56.64,5,'2018-07-23'),(5,NULL,67.89,21.09,6,'2018-07-23'),(6,NULL,13.57,43.87,7,'2018-07-23'),(7,NULL,91.08,65.56,8,'2018-07-23'),(8,NULL,73.81,82.93,2,'2018-07-30'),(9,NULL,50,50,3,'2018-07-30'),(10,NULL,50,100,4,'2018-07-30'),(11,NULL,0,0,5,'2018-07-30'),(12,NULL,33.33,66.67,6,'2018-07-30'),(13,NULL,0,100,7,'2018-07-30'),(21,NULL,86.05,83.72,2,'2018-07-31'),(22,NULL,50,50,3,'2018-07-31'),(23,NULL,33.33,100,4,'2018-07-31'),(24,NULL,0,0,5,'2018-07-31'),(25,NULL,66.67,66.67,6,'2018-07-31'),(26,NULL,0,100,7,'2018-07-31');
/*!40000 ALTER TABLE `assessmentDepartment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assessmentEmployee`
--

DROP TABLE IF EXISTS `assessmentEmployee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assessmentEmployee` (
  `EMPLOYEEASSESSMENTID` int(11) NOT NULL AUTO_INCREMENT,
  `ACCURACY` double DEFAULT NULL,
  `COMPLETENESS` double DEFAULT NULL,
  `TIMELINESS` double DEFAULT NULL,
  `users_USERID` int(11) DEFAULT NULL,
  `projects_PROJECTID` int(11) DEFAULT NULL,
  `DATE` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEEASSESSMENTID`),
  KEY `fk_employeeAssessment_users1_idx` (`users_USERID`),
  KEY `fk_employeeAssessment_projects1_idx` (`projects_PROJECTID`),
  CONSTRAINT `fk_employeeAssessment_projects1` FOREIGN KEY (`projects_PROJECTID`) REFERENCES `projects` (`PROJECTID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_employeeAssessment_users1` FOREIGN KEY (`users_USERID`) REFERENCES `users` (`USERID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assessmentEmployee`
--

LOCK TABLES `assessmentEmployee` WRITE;
/*!40000 ALTER TABLE `assessmentEmployee` DISABLE KEYS */;
/*!40000 ALTER TABLE `assessmentEmployee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assessmentProject`
--

DROP TABLE IF EXISTS `assessmentProject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assessmentProject` (
  `PROJECTWEEKLYPROGRESSID` int(11) NOT NULL AUTO_INCREMENT,
  `ACCURACY` double DEFAULT NULL,
  `COMPLETENESS` double DEFAULT NULL,
  `TIMELINESS` double DEFAULT NULL,
  `projects_PROJECTID` int(11) NOT NULL,
  `DATE` varchar(20) NOT NULL,
  PRIMARY KEY (`PROJECTWEEKLYPROGRESSID`),
  KEY `fk_ projectWeeklyProgress_projects1_idx` (`projects_PROJECTID`),
  CONSTRAINT `fk_ projectWeeklyProgress_projects1` FOREIGN KEY (`projects_PROJECTID`) REFERENCES `projects` (`PROJECTID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assessmentProject`
--

LOCK TABLES `assessmentProject` WRITE;
/*!40000 ALTER TABLE `assessmentProject` DISABLE KEYS */;
INSERT INTO `assessmentProject` VALUES (1,NULL,0,0,1,'2018-06-22'),(2,NULL,11.81,12.09,1,'2018-06-29'),(3,NULL,19.33,23.87,1,'2018-07-06'),(4,NULL,21.63,34.65,1,'2018-07-13'),(5,NULL,27.48,45.54,1,'2018-07-20'),(9,NULL,33.06,29.32,1,'2018-07-27'),(10,NULL,0,0,9,'2018-07-01'),(11,NULL,11.08,100,9,'2018-07-08'),(12,NULL,26.29,94.21,9,'2018-07-15'),(13,NULL,34.57,86.73,9,'2018-07-22'),(14,NULL,57.41,86.73,9,'2018-07-29'),(38,NULL,35.18,85.71,1,'2018-07-31'),(39,NULL,73.62,86.73,9,'2018-08-05'),(40,NULL,37.2,85.71,1,'2018-08-07'),(41,NULL,37.2,85.71,1,'2018-08-14'),(42,NULL,73.62,86.73,9,'2018-08-12');
/*!40000 ALTER TABLE `assessmentProject` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `changerequests`
--

LOCK TABLES `changerequests` WRITE;
/*!40000 ALTER TABLE `changerequests` DISABLE KEYS */;
INSERT INTO `changerequests` VALUES (1,'1',NULL,NULL,'On leave',49,'2018-07-06','Approved',4,'2018-07-25','',17),(2,'2','','2018-07-14','Documents were put on hold',21,'2018-06-14','Approved',4,'2018-06-15','',7),(3,'1',NULL,NULL,'la lang',4,'2018-07-24','Pending',4,'','',18),(4,'1',NULL,NULL,'pagod',5,'2018-07-24','Approved',4,'2018-07-25','',33),(5,'1',NULL,NULL,'hirap',27,'2018-06-13','Denied',4,'2018-06-14','fa',7),(6,'1',NULL,NULL,'does it go in?',4,'2018-07-25','Pending',1,NULL,NULL,18),(7,'1','','','Leave of Absence',25,'2018-07-05','Denied',11,'2018-07-05','Your request for leave is denied',113),(8,'1',NULL,NULL,'Wronmg staff',4,'2018-07-27','Pending',1,NULL,NULL,18),(9,'1','','','Not my function',26,'2018-07-01','Denied',11,'2018-07-01','This is a special case. Please handle this',104),(10,'2','2018-07-03','2018-07-07','Supplier wont be able to deliver',28,'2018-07-02','Denied',12,'2018-07-02','Contacted supplier. They will prioritize us but just this time',107);
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
  `users_DEPARTMENTHEAD` int(11) DEFAULT NULL,
  PRIMARY KEY (`DEPARTMENTID`),
  KEY `fk_users1_idx` (`users_DEPARTMENTHEAD`),
  CONSTRAINT `fk_users1` FOREIGN KEY (`users_DEPARTMENTHEAD`) REFERENCES `users` (`USERID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Executive',3),(2,'Marketing',4),(3,'Finance',5),(4,'Procurement',6),(5,'HR',7),(6,'MIS',8),(7,'Store Operations',9),(8,'Facilities Administration',10);
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
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dependencies`
--

LOCK TABLES `dependencies` WRITE;
/*!40000 ALTER TABLE `dependencies` DISABLE KEYS */;
INSERT INTO `dependencies` VALUES (1,'2',3),(2,'3',4),(3,'2',5),(4,'2',6),(5,'2',8),(6,'2',9),(7,'3',10),(8,'3',7),(9,'9',11),(10,'11',13),(11,'12',13),(12,'13',14),(13,'9',14),(14,'41',42),(15,'42',43),(16,'42',44),(17,'42',45),(18,'45',46),(19,'93',94),(20,'94',95),(21,'94',96),(22,'97',98),(23,'97',99),(24,'97',100),(25,'99',100),(26,'1',2),(27,'112',113),(28,'120',121),(29,'121',122),(30,'129',133),(31,'137',138);
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentacknowledgement`
--

LOCK TABLES `documentacknowledgement` WRITE;
/*!40000 ALTER TABLE `documentacknowledgement` DISABLE KEYS */;
INSERT INTO `documentacknowledgement` VALUES (2,19,6,''),(4,21,4,''),(5,22,4,''),(6,22,11,'2018-07-30'),(19,26,4,''),(20,26,11,'2018-07-30'),(21,26,2,''),(22,26,50,''),(23,27,23,''),(24,27,50,''),(25,27,2,'');
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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` VALUES (3,'Uploaded','Sched2.png','http://localhost/Kernel/assets/uploads/Sched2.png',NULL,4,'2018-07-12',3),(4,'Uploaded','Mama_Papa.png','http://localhost/Kernel/assets/uploads/Mama_Papa.png',NULL,5,'2018-07-12',5),(5,'Uploaded','Sched21.png','http://localhost/Kernel/assets/uploads/Sched21.png',NULL,4,'2018-07-14',5),(6,'Uploaded','Sched22.png','http://localhost/Kernel/assets/uploads/Sched22.png',NULL,4,'2018-07-14',5),(7,'Uploaded','Himala.doc','http://localhost/Kernel/assets/uploads/Himala.doc',NULL,4,'2018-07-14',8),(8,'Uploaded','pineapple-supply-co-64690-unsplash.jpg','http://localhost/Kernel/assets/uploads/pineapple-supply-co-64690-unsplash.jpg',NULL,4,'2018-07-14',7),(9,'Uploaded','pineapple-supply-co-64690-unsplash1.jpg','http://localhost/Kernel/assets/uploads/pineapple-supply-co-64690-unsplash1.jpg',NULL,4,'2018-07-14',1),(19,'For Acknowledgement','COVER_LETTER_Gonzaga,_Adrienne_Claire_A.1.pdf','http://localhost/Kernel/assets/uploads/COVER_LETTER_Gonzaga,_Adrienne_Claire_A.1.pdf',NULL,4,'2018-07-14',7),(21,'For Acknowledgement','spca2.pdf','http://localhost/Kernel/assets/uploads/spca2.pdf','no procurement',4,'2018-07-14',7),(22,'For Acknowledgement','Business_Case_-_Comment_1.png','http://localhost/Kernel/assets/uploads/Business_Case_-_Comment_1.png','',5,'2018-07-17',1),(26,'For Acknowledgement','BSIS-Flowchart.docx','http://localhost/Kernel/assets/uploads/BSIS-Flowchart.docx','',5,'2018-07-24',1),(27,'For Acknowledgement','9854.png','http://localhost/Kernel/assets/uploads/9854.png','',4,'2018-07-30',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=213 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` VALUES (1,'Marketing Head created a new project.','2018-04-30 11:08:23',1),(2,'President President completed a task (TASK TITLE)','2018-06-21 16:00:57',1),(3,'Marketing Supervisor delegated a task (TASK TITLE) to Marketing1 Staff.','2018-06-24 09:06:48',1),(4,'MIS Head created a new project.','2018-07-01 13:34:09',2),(5,'Marketing Head created a new project.','2018-05-17 10:22:36',3),(6,'FAD1 Staff requested a change in (DROPDOWN)','2018-06-15 11:58:51',3),(7,'Finance4 Staff uploaded a document.','2018-09-25 15:21:20',3),(174,'Marketing Head has archived this project.','2018-07-28 21:28:40',5);
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
  `TYPE` int(5) DEFAULT NULL,
  PRIMARY KEY (`NOTIFICATIONID`),
  KEY `fk_notifications_users1_idx` (`users_USERID`),
  KEY `fk_notifications_tasks1_idx` (`tasks_TASKID`),
  KEY `fk_notifications_projects1_idx` (`projects_PROJECTID`),
  CONSTRAINT `fk_notifications_projects1` FOREIGN KEY (`projects_PROJECTID`) REFERENCES `projects` (`PROJECTID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_notifications_tasks1` FOREIGN KEY (`tasks_TASKID`) REFERENCES `tasks` (`TASKID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_notifications_users1` FOREIGN KEY (`users_USERID`) REFERENCES `users` (`USERID`)
) ENGINE=InnoDB AUTO_INCREMENT=214 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (207,4,'Task - Submit certificate of registration in Store Opening is already delayed. Please accomplish immediately.','2018-08-15 05:39:56','Unread',18,1,3),(208,4,'Task - Submit certificate of registration in Store Opening is delayed.','2018-08-15 05:39:56','Unread',18,1,1),(209,4,'Task3.3.3-Receive Digital Menu TVs in Store Opening - DLSU Andrew is already delayed. Please accomplish immediately.','2018-08-15 05:39:56','Unread',139,9,3),(210,4,'Task3.3.3-Receive Digital Menu TVs in Store Opening - DLSU Andrew is delayed.','2018-08-15 05:39:56','Unread',139,9,1),(211,3,'Task3.3.3-Receive Digital Menu TVs in Store Opening - DLSU Andrew is delayed.','2018-08-15 05:39:56','Unread',139,9,4),(212,9,'Task3.3.3-Receive Digital Menu TVs in Store Opening - DLSU Andrew is delayed.','2018-08-15 05:39:56','Unread',139,9,4),(213,2,'Task3.3.3-Receive Digital Menu TVs in Store Opening - DLSU Andrew is delayed.','2018-08-15 05:39:56','Unread',139,9,4);
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
  `PROJECTADJUSTEDENDDATE` varchar(20) DEFAULT NULL,
  `PROJECTADJUSTEDSTATDATE` varchar(20) DEFAULT NULL,
  `DATECREATED` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`PROJECTID`),
  KEY `fk_projects_users1_idx` (`users_USERID`),
  KEY `fk_projects_templates1_idx` (`templates_PROJECTID`),
  CONSTRAINT `fk_projects_templates1` FOREIGN KEY (`templates_PROJECTID`) REFERENCES `templates` (`PROJECTID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_projects_users1` FOREIGN KEY (`users_USERID`) REFERENCES `users` (`USERID`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (1,'Store Opening','2018-06-15','2018-10-17','Store Opening for Vertis North','Ongoing',4,NULL,'2018-06-15',NULL,NULL,NULL,NULL),(2,'Software Update','2018-07-01','2018-08-01','Software Update for Finance Department','Ongoing',8,NULL,'2018-07-01',NULL,NULL,NULL,NULL),(3,'New Product Launching','2018-06-19','2018-07-17','Cotton Candy Flavored Popcorn','Ongoing',4,NULL,'2018-06-19',NULL,'11-05-2018',NULL,NULL),(4,'Software Dev - Draft','2018-07-22','2018-07-28','Development','Drafted',8,NULL,'2018-07-22',NULL,NULL,NULL,NULL),(5,'Centrum Complete','2018-06-26','2018-07-26','Advertisement','Complete',4,NULL,'2018-06-26','2018-07-28',NULL,NULL,NULL),(6,'MKT GA','2018-06-27','2018-07-30','General Assembly','Parked',6,NULL,NULL,NULL,NULL,NULL,NULL),(7,'Mulan','2018-04-16','2018-05-04','Hope this works','Archived',4,NULL,NULL,NULL,NULL,NULL,NULL),(8,'Hercules','2018-08-20','2018-09-28','Zero to Hero','Planning',4,NULL,NULL,NULL,NULL,NULL,NULL),(9,'Store Opening - DLSU Andrew','2018-07-01','2018-08-17','10th floor. 1st Taters branch in Taft','Ongoing',4,NULL,'2018-07-01',NULL,NULL,NULL,NULL),(11,'Coca Cola Partnership','2018-07-27','2018-08-04','SNOOPY','Drafted',4,NULL,NULL,NULL,NULL,NULL,NULL),(15,'Template Test','2018-07-22','2018-07-22','Project templates','Archived',4,NULL,NULL,'2018-07-22',NULL,NULL,NULL);
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
  `ROLE` varchar(45) NOT NULL,
  `users_USERID` int(11) NOT NULL,
  `tasks_TASKID` int(11) NOT NULL,
  `STATUS` varchar(45) NOT NULL COMMENT 'Current or Updated',
  PRIMARY KEY (`RACIID`),
  KEY `fk_raci_users1_idx` (`users_USERID`),
  KEY `fk_raci_tasks1_idx` (`tasks_TASKID`),
  CONSTRAINT `fk_raci_tasks1` FOREIGN KEY (`tasks_TASKID`) REFERENCES `tasks` (`TASKID`),
  CONSTRAINT `fk_raci_users1` FOREIGN KEY (`users_USERID`) REFERENCES `users` (`USERID`)
) ENGINE=InnoDB AUTO_INCREMENT=448 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `raci`
--

LOCK TABLES `raci` WRITE;
/*!40000 ALTER TABLE `raci` DISABLE KEYS */;
INSERT INTO `raci` VALUES (1,'1',2,1,'Current'),(2,'1',2,2,'Current'),(3,'1',5,3,'Current'),(4,'1',2,4,'Current'),(5,'1',4,5,'Current'),(6,'1',4,6,'Current'),(7,'1',27,7,'Current'),(8,'1',4,8,'Current'),(9,'1',35,9,'Current'),(10,'1',35,10,'Current'),(11,'1',5,11,'Current'),(12,'1',11,12,'Current'),(13,'1',11,13,'Current'),(14,'1',11,14,'Current'),(15,'1',11,15,'Current'),(16,'1',4,16,'Current'),(17,'1',4,17,'Current'),(18,'1',4,18,'Current'),(19,'1',4,19,'Current'),(20,'1',23,20,'Current'),(21,'1',23,21,'Current'),(22,'1',23,22,'Current'),(23,'1',4,23,'Current'),(24,'1',50,24,'Current'),(25,'1',50,25,'Current'),(26,'1',50,26,'Current'),(27,'1',50,27,'Current'),(28,'1',9,28,'Current'),(29,'1',47,29,'Current'),(30,'1',31,30,'Current'),(31,'1',9,31,'Current'),(32,'1',9,32,'Current'),(33,'1',5,33,'Current'),(34,'1',4,34,'Current'),(35,'1',12,35,'Current'),(36,'1',24,36,'Current'),(37,'1',36,37,'Current'),(38,'1',32,38,'Current'),(39,'1',21,39,'Current'),(40,'1',14,40,'Current'),(41,'1',4,41,'Current'),(42,'1',49,42,'Current'),(43,'1',51,43,'Current'),(44,'1',26,44,'Current'),(45,'1',19,45,'Current'),(46,'1',32,46,'Current'),(47,'1',4,47,'Current'),(48,'1',4,48,'Current'),(49,'1',4,49,'Current'),(50,'1',7,50,'Current'),(51,'1',4,51,'Current'),(52,'1',6,83,'Current'),(53,'1',4,52,'Current'),(54,'1',5,53,'Current'),(55,'1',7,53,'Current'),(56,'1',8,54,'Current'),(57,'1',12,55,'Changed'),(58,'1',17,56,'Current'),(59,'1',27,57,'Current'),(60,'1',17,64,'Current'),(61,'1',18,65,'Current'),(62,'1',37,66,'Current'),(63,'1',38,67,'Current'),(64,'1',39,68,'Current'),(65,'1',37,69,'Current'),(66,'1',40,70,'Current'),(67,'1',8,71,'Current'),(68,'1',27,72,'Current'),(69,'1',28,73,'Current'),(71,'2',4,1,'Current'),(72,'3',5,1,'Current'),(73,'4',6,1,'Current'),(78,'1',8,75,'Current'),(79,'1',19,76,'Current'),(80,'1',41,77,'Current'),(83,'1',43,80,'Current'),(84,'2',53,71,'Current'),(85,'2',53,75,'Current'),(86,'2',8,76,'Current'),(87,'2',19,77,'Current'),(88,'2',8,78,'Current'),(89,'2',20,79,'Current'),(90,'2',20,80,'Current'),(91,'3',8,71,'Current'),(92,'3',8,75,'Current'),(93,'3',8,76,'Current'),(94,'3',8,77,'Current'),(95,'3',8,78,'Current'),(96,'3',8,79,'Current'),(97,'3',8,80,'Current'),(98,'4',2,71,'Current'),(99,'4',2,75,'Current'),(100,'4',2,76,'Current'),(103,'4',2,79,'Current'),(104,'4',2,80,'Current'),(105,'4',53,80,'Current'),(109,'1',4,84,'Current'),(112,'1',4,87,'Current'),(113,'1',4,88,'Current'),(114,'1',4,89,'Current'),(115,'1',4,90,'Current'),(116,'1',4,91,'Current'),(117,'1',4,92,'Current'),(118,'1',4,93,'Current'),(119,'1',4,94,'Current'),(120,'1',4,95,'Current'),(121,'1',4,96,'Current'),(122,'1',4,97,'Current'),(123,'1',4,98,'Current'),(124,'1',4,99,'Current'),(125,'1',4,100,'Current'),(126,'1',4,101,'Current'),(127,'1',11,102,'Current'),(128,'1',25,103,'Current'),(129,'1',26,104,'Current'),(130,'1',27,105,'Current'),(131,'1',11,106,'Current'),(132,'1',28,107,'Current'),(133,'1',25,108,'Current'),(134,'1',26,109,'Current'),(135,'1',11,110,'Current'),(136,'1',27,111,'Current'),(137,'1',28,112,'Current'),(138,'1',25,113,'Current'),(139,'1',26,114,'Current'),(140,'1',12,115,'Current'),(141,'1',11,116,'Current'),(142,'1',12,117,'Current'),(143,'1',4,118,'Current'),(144,'1',12,119,'Current'),(145,'1',4,120,'Current'),(146,'1',11,121,'Current'),(147,'1',11,122,'Current'),(148,'1',12,123,'Current'),(149,'1',12,124,'Current'),(150,'1',27,125,'Current'),(151,'1',41,126,'Current'),(152,'1',4,127,'Current'),(153,'1',4,128,'Current'),(154,'1',4,129,'Current'),(155,'1',4,130,'Current'),(156,'1',4,131,'Current'),(157,'1',4,132,'Current'),(158,'1',4,133,'Current'),(159,'1',4,134,'Current'),(160,'1',4,135,'Current'),(161,'1',4,136,'Current'),(162,'1',6,137,'Current'),(163,'1',25,138,'Current'),(164,'1',4,139,'Current'),(165,'2',11,103,'Current'),(166,'2',11,104,'Current'),(167,'2',12,105,'Current'),(168,'2',12,107,'Current'),(169,'2',12,108,'Current'),(170,'2',11,109,'Current'),(171,'2',11,111,'Current'),(172,'2',12,112,'Current'),(173,'2',11,113,'Current'),(174,'2',12,116,'Current'),(175,'2',12,117,'Current'),(176,'2',12,118,'Current'),(177,'2',11,120,'Current'),(178,'2',12,121,'Current'),(179,'2',12,122,'Current'),(180,'2',12,124,'Current'),(181,'2',11,125,'Current'),(182,'2',12,126,'Current'),(183,'2',3,129,'Current'),(184,'2',3,130,'Current'),(185,'2',3,131,'Current'),(186,'2',3,133,'Current'),(187,'2',3,134,'Current'),(188,'2',3,135,'Current'),(189,'2',3,137,'Current'),(190,'2',3,138,'Current'),(191,'2',3,139,'Current'),(192,'3',9,103,'Current'),(193,'3',9,104,'Current'),(194,'3',9,105,'Current'),(195,'3',9,107,'Current'),(196,'3',9,108,'Current'),(197,'3',9,109,'Current'),(198,'3',9,111,'Current'),(199,'3',9,112,'Current'),(200,'3',9,113,'Current'),(201,'3',9,116,'Current'),(202,'3',9,117,'Current'),(203,'3',9,118,'Current'),(204,'3',9,120,'Current'),(205,'3',9,121,'Current'),(206,'3',9,122,'Current'),(207,'3',9,124,'Current'),(208,'3',9,125,'Current'),(209,'3',9,126,'Current'),(210,'3',9,129,'Current'),(211,'3',9,130,'Current'),(212,'3',9,131,'Current'),(213,'3',9,133,'Current'),(214,'3',9,134,'Current'),(215,'3',9,135,'Current'),(216,'3',9,137,'Current'),(217,'3',9,138,'Current'),(218,'3',9,139,'Current'),(219,'4',2,103,'Current'),(220,'4',2,104,'Current'),(221,'4',2,105,'Current'),(222,'4',2,107,'Current'),(223,'4',2,108,'Current'),(224,'4',2,109,'Current'),(225,'4',2,111,'Current'),(226,'4',2,112,'Current'),(227,'4',2,113,'Current'),(228,'4',2,116,'Current'),(229,'4',2,117,'Current'),(230,'4',2,118,'Current'),(231,'4',2,120,'Current'),(232,'4',2,121,'Current'),(233,'4',2,122,'Current'),(234,'4',2,124,'Current'),(235,'4',2,125,'Current'),(236,'4',2,126,'Current'),(237,'4',2,129,'Current'),(238,'4',2,130,'Current'),(239,'4',2,131,'Current'),(240,'4',2,133,'Current'),(241,'4',2,134,'Current'),(242,'4',2,135,'Current'),(243,'4',2,137,'Current'),(244,'4',2,138,'Current'),(245,'4',2,139,'Current'),(443,'1',15,55,'Current'),(444,'1',16,57,'Current'),(446,'1',34,65,'Current'),(447,'1',33,66,'Current');
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
  PRIMARY KEY (`TASKID`),
  KEY `fk_tasks_projects1_idx` (`projects_PROJECTID`),
  KEY `fk_tasks_tasks1_idx` (`tasks_TASKPARENT`),
  CONSTRAINT `fk_tasks_projects1` FOREIGN KEY (`projects_PROJECTID`) REFERENCES `projects` (`PROJECTID`),
  CONSTRAINT `fk_tasks_tasks1` FOREIGN KEY (`tasks_TASKPARENT`) REFERENCES `tasks` (`TASKID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=240 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES (1,'Main - Lease Offer','2018-06-15','2018-06-21','Complete','','1',1,'2018-06-15','2018-07-24',NULL,NULL,NULL),(2,'Sub - Review lease offer','2018-06-21','2018-06-21','Complete','','2',1,'2018-06-21','2018-07-24',1,NULL,NULL),(3,'Task - Read lease offer','2018-06-15','2018-06-20','Complete','done','3',1,'2018-06-15','2018-07-24',2,NULL,NULL),(4,'Task - Sign lease offer','2018-06-20','2018-06-21','Complete','','3',1,'2018-06-20','2018-06-21',2,NULL,NULL),(5,'Main - Assets','2018-06-22','2018-06-23','Complete','','1',1,'2018-06-22','2018-06-26',NULL,NULL,NULL),(6,'Sub - Procure initial assets','2018-06-23','2018-06-23','Complete','','2',1,'2018-06-23','2018-06-24',5,NULL,NULL),(7,'Task - Look for mixer','2018-06-23','2018-06-25','Ongoing','','3',1,'2018-06-23','',6,NULL,'2018-07-14'),(8,'Main - Quotations','2018-06-23','2018-06-25','Complete','','1',1,'2018-06-23','2018-06-25',NULL,NULL,NULL),(9,'Sub - Canvas','2018-06-23','2018-06-25','Complete','','2',1,'2018-06-23','2018-06-28',8,NULL,NULL),(10,'Task - Finalize logistics quotation','2018-06-25','2018-06-26','Complete','','3',1,'2018-06-25','2018-06-26',9,NULL,NULL),(11,'Task - Plan training calendar for bookkeeper','2018-06-23','2018-06-25','Complete','','3',1,'2018-06-23','2018-06-25',9,NULL,NULL),(12,'Main - Permits','2018-06-23','2018-06-30','Ongoing','','1',1,'2018-06-23','',NULL,NULL,NULL),(13,'Sub - Gather requirements for different permits','2018-06-23','2018-06-30','Ongoing','','2',1,'2018-06-23','',12,NULL,NULL),(14,'Task - Email','2018-06-23','2018-06-24','Complete','','3',1,'2018-06-23','2018-06-24',13,NULL,NULL),(15,'Task - Print','2018-06-24','2018-06-30','Ongoing','','3',1,'2018-06-24','',13,NULL,NULL),(16,'Main - Certificate of Registration','2018-06-30','2018-07-07','Ongoing','','1',1,'2018-06-30','',NULL,NULL,NULL),(17,'Sub - Acquire certification of registration','2018-06-30','2018-07-07','Complete','','2',1,'2018-06-30','2018-07-23',16,NULL,NULL),(18,'Task - Submit certificate of registration','2018-06-29','2018-06-30','Ongoing','','3',1,'2018-06-29','',17,NULL,NULL),(19,'Task - Claim certificate of registration','2018-07-06','2018-07-07','Complete','went on leave','3',1,'2018-07-06','2018-07-23',17,NULL,NULL),(20,'Sub - Training','2018-06-23','2018-07-30','Ongoing','','2',1,'2018-06-23','',16,NULL,NULL),(21,'Task - Employee training','2018-06-23','2018-07-30','Ongoing','','3',1,'2018-06-23','',20,NULL,NULL),(22,'Task - Staff training','2018-07-07','2018-07-14','Ongoing','','3',1,'2018-07-07','',20,NULL,NULL),(23,'Main - Construction','2018-06-23','2018-09-30','Ongoing','','1',1,'2018-06-23','',NULL,NULL,NULL),(24,'Sub - Materials for construction','2018-06-23','2018-06-30','Ongoing','','2',1,'2018-06-23','',23,NULL,NULL),(25,'Task - Order materials','2018-06-23','2018-06-24','Complete','','3',1,'2018-06-23','2018-06-24',24,NULL,NULL),(26,'Task - Receive materials','2018-06-29','2018-06-30','Ongoing','','3',1,'2018-06-29','',24,NULL,NULL),(27,'Sub - Start construction','2018-06-30','2018-09-30','Ongoing','','2',1,'2018-06-30','',23,NULL,NULL),(28,'Task - Mix cement','2018-06-23','2018-07-07','Ongoing','','3',1,'2018-06-23','',27,NULL,NULL),(29,'Task - Paint walls','2018-09-30','2018-10-07','Planning','','3',1,'','',27,NULL,NULL),(30,'Main - Finalize','2018-09-30','2018-10-07','Planning','','3',1,'','',27,NULL,NULL),(31,'Sub - Final check','2018-10-08','2018-10-12','Planning','','1',1,'','',NULL,NULL,NULL),(32,'Task - Dry run operation','2018-10-12','2018-10-16','Planning','','2',1,'','',31,NULL,NULL),(33,'Task - Turnover of store','2018-10-16','2018-10-16','Planning','','3',1,'','',32,NULL,NULL),(34,'Main - Conceptualize New Product','2018-06-19','2018-06-25','Complete','','1',3,'2018-06-19','2018-06-25',NULL,NULL,NULL),(35,'S - Meeting','2018-06-26','2018-10-26','Complete','','2',3,'2018-06-26','2018-06-27',34,NULL,NULL),(36,'T - Brainstorming','2018-06-26','2018-06-26','Ongoing','','3',3,'2018-06-26','',35,NULL,NULL),(37,'T - Distribute Tasks','2018-06-26','2018-06-27','Ongoing','','3',3,'2018-06-26','',35,NULL,NULL),(38,'Main - Conduct Research','2018-06-28','2018-07-28','Ongoing','','1',3,'2018-06-28','',NULL,NULL,NULL),(39,'S - Market Research','2018-06-28','2018-07-04','Ongoing','','2',3,'2018-06-28','',38,NULL,NULL),(40,'S - R&D','2018-06-28','2018-07-03','Ongoing','','2',3,'2018-06-28','',38,NULL,NULL),(41,'Main - Develop Product Prototyp','2018-07-06','2018-07-08','Ongoing','','1',3,'2018-07-06','',NULL,NULL,NULL),(42,'Main - Evaluate Product Concept','2018-07-06','2018-07-09','Ongoing','','1',3,'2018-07-06','',NULL,NULL,NULL),(43,'Main - Design Promotional Materials','2018-07-06','2018-09-14','Ongoing','','1',3,'2018-07-06','',NULL,NULL,NULL),(44,'Main - Order Ingredients','2018-09-08','2018-09-11','Planning','','1',3,'','',NULL,NULL,NULL),(45,'Main - Update PoS System','2018-09-04','2018-09-09','Planning','','1',3,'','',NULL,NULL,NULL),(46,'Main - Conduct Training','2018-09-09','2018-09-22','Planning','','1',3,'','',NULL,NULL,NULL),(47,'Main - Launch New Product','2018-09-10','2018-09-23','Planning','','1',3,'','',NULL,NULL,NULL),(48,'main - project 5','2018-06-26','2018-07-20','Complete',NULL,'1',5,'2018-06-26','2018-07-20',NULL,NULL,NULL),(49,'sub - project 5','2018-06-26','2018-07-20','Complete',NULL,'2',5,'2018-06-26','2018-07-20',NULL,NULL,NULL),(50,'Main - Project 6','2018-06-26','2018-06-28','Ongoing',NULL,'1',6,'2018-06-26',NULL,NULL,NULL,NULL),(51,'Main 1 - Project 7','2018-04-16','2018-05-04','Complete',NULL,'1',7,'2018-04-16','2018-05-04',NULL,NULL,NULL),(52,'Main 1','2018-06-26','2018-06-30','Ongoing',NULL,'1',8,'2018-06-26',NULL,NULL,NULL,NULL),(53,'Main 2','2018-06-26','2018-06-30','Ongoing',NULL,'1',8,'2018-06-26',NULL,NULL,NULL,NULL),(54,'task for project 4','2018-06-26','2018-06-30','Ongoing',NULL,'1',4,'2018-06-26',NULL,NULL,NULL,NULL),(55,'task1 -  project 5','2018-06-26','2018-07-01','Complete',NULL,'3',5,'2018-06-26','2018-07-01',NULL,NULL,NULL),(56,'Sub - Project 6','2018-06-26','2018-06-28','Ongoing',NULL,'2',6,'2018-06-26',NULL,NULL,NULL,NULL),(57,'task2 - project 5','2018-07-01','2018-07-20','Complete',NULL,'3',5,'2018-07-01','2018-07-18',NULL,NULL,NULL),(64,'Task1 - Project 6','2018-06-26','2018-06-26','Ongoing',NULL,'3',6,'2018-06-26',NULL,NULL,NULL,NULL),(65,'Task2 - Project 6','2018-06-26','2018-06-29','Ongoing',NULL,'3',6,'2018-06-26',NULL,NULL,NULL,NULL),(66,'Task3 - Project 6','2018-06-26','2018-06-29','Ongoing',NULL,'3',6,'2018-06-26',NULL,NULL,NULL,NULL),(67,'Task4 - Project 6','2018-06-26','2018-06-29','Ongoing',NULL,'3',6,'2018-06-26',NULL,NULL,NULL,NULL),(68,'Task5 - Project 6','2018-06-26','2018-06-29','Ongoing',NULL,'3',6,'2018-06-26',NULL,NULL,NULL,NULL),(69,'Task6 - Project 6','2018-06-26','2018-06-29','Ongoing',NULL,'3',6,'2018-06-26',NULL,NULL,NULL,NULL),(70,'Task7- Project 6','2018-06-26','2018-06-29','Ongoing',NULL,'3',6,'2018-06-26',NULL,NULL,NULL,NULL),(71,'Main 1 - Project 2','2018-07-01','2018-07-10','Complete',NULL,'1',2,'2018-07-01','2018-07-10',NULL,NULL,NULL),(72,'Task1 - Product Dev','2018-06-29','2018-07-29','Complete',NULL,'3',3,'2018-06-29','2018-07-29',NULL,NULL,NULL),(73,'Task2 - Product Dev','2018-06-29','2018-07-29','Ongoing',NULL,'3',3,'2018-06-29',NULL,NULL,NULL,NULL),(74,'Main 1 - Coke Project','2018-07-27','2018-07-28','Ongoing',NULL,'1',11,'2018-07-27',NULL,NULL,NULL,NULL),(75,'Sub 1.1 - Project 2','2018-07-01','2018-07-10','Complete',NULL,'2',2,'2018-07-01','2018-07-10',71,NULL,NULL),(76,'Task 1.2- Project 2','2018-07-01','2018-07-10','Complete',NULL,'3',2,'2018-07-01','2018-07-10',75,NULL,NULL),(77,'Main 2- Project 2','2018-07-11','2018-07-31','Ongoing',NULL,'1',2,'2018-07-11',NULL,NULL,NULL,NULL),(78,'Sub 2.1 - Project 2','2018-07-11','2018-07-31','Ongoing',NULL,'2',2,'2018-07-11',NULL,77,NULL,NULL),(79,'Task 2.1.1 - Project 2','2018-07-11','2018-07-31','Ongoing',NULL,'3',2,'2018-07-11',NULL,78,NULL,NULL),(80,'Task 2.1.2 - Project 2','2018-07-20','2018-07-31','Ongoing',NULL,'3',2,'2018-07-20',NULL,78,NULL,NULL),(83,'Sub 1 - Project 7','2018-04-16','2018-05-04','Complete',NULL,'2',7,'2018-04-16','2018-05-04',51,NULL,NULL),(84,'Task 1 - Project 7','2018-04-16','2018-05-04','Complete',NULL,'3',7,'2018-04-16','2018-05-04',83,NULL,NULL),(87,'Main 1','2018-07-22','2018-07-22','Complete',NULL,'1',15,'2018-07-22','2018-07-22',NULL,NULL,NULL),(88,'Main 2','2018-07-22','2018-07-22','Complete',NULL,'1',15,'2018-07-22','2018-07-22',NULL,NULL,NULL),(89,'1a','2018-07-22','2018-07-22','Complete',NULL,'2',15,'2018-07-22','2018-07-22',87,NULL,NULL),(90,'1b','2018-07-22','2018-07-22','Complete',NULL,'2',15,'2018-07-22','2018-07-22',87,NULL,NULL),(91,'2a','2018-07-22','2018-07-22','Complete',NULL,'2',15,'2018-07-22','2018-07-22',88,NULL,NULL),(92,'2b','2018-07-22','2018-07-22','Complete',NULL,'2',15,'2018-07-22','2018-07-22',88,NULL,NULL),(93,'aaa','2018-07-22','2018-07-22','Complete','','3',15,'2018-07-22','2018-07-22',89,NULL,NULL),(94,'aab','2018-07-22','2018-07-22','Complete','','3',15,'2018-07-22','2018-07-22',89,NULL,NULL),(95,'aba','2018-07-22','2018-07-22','Complete','','3',15,'2018-07-22','2018-07-22',90,NULL,NULL),(96,'abb','2018-07-22','2018-07-22','Complete','','3',15,'2018-07-22','2018-07-22',90,NULL,NULL),(97,'baa','2018-07-22','2018-07-22','Complete','','3',15,'2018-07-22','2018-07-22',91,NULL,NULL),(98,'bab','2018-07-22','2018-07-22','Complete','','3',15,'2018-07-22','2018-07-22',91,NULL,NULL),(99,'bba','2018-07-22','2018-07-22','Complete','','3',15,'2018-07-22','2018-07-22',92,NULL,NULL),(100,'bbb','2018-07-22','2018-07-22','Complete','','3',15,'2018-07-22','2018-07-22',92,NULL,NULL),(101,'Main1','2018-07-01','2018-07-10','Complete','','1',9,'2018-07-01','2018-07-10',NULL,NULL,NULL),(102,'Sub1.1','2018-07-01','2018-07-02','Complete','','2',9,'2018-07-01','2018-07-02',101,NULL,NULL),(103,'Task1.1.1','2018-07-01','2018-07-02','Complete','Can be done in a single day next time','3',9,'2018-07-01','2018-07-02',102,NULL,NULL),(104,'Task1.1.2','2018-07-01','2018-07-02','Complete','','3',9,'2018-07-01','2018-07-02',102,NULL,NULL),(105,'Task1.1.3','2018-07-01','2018-07-02','Complete','No internet','3',9,'2018-07-01','2018-07-02',102,NULL,NULL),(106,'Sub1.2','2018-07-03','2018-07-08','Complete','','2',9,'2018-07-03','2018-07-08',101,NULL,NULL),(107,'Task1.2.1','2018-07-03','2018-07-05','Complete','','3',9,'2018-07-03','2018-07-05',106,NULL,NULL),(108,'Task1.2.2','2018-07-04','2018-07-06','Complete','Too much workload. Over saw the task','3',9,'2018-07-04','2018-07-07',106,NULL,NULL),(109,'Task1.2.3','2018-07-03','2018-07-08','Complete','Time allowance is limited. Quality might be compromised','3',9,'2018-07-03','2018-07-08',106,NULL,NULL),(110,'Sub1.3','2018-07-09','2018-07-10','Complete','','2',9,'2018-07-09','2018-07-10',101,NULL,NULL),(111,'Task1.3.1','2018-07-09','2018-07-10','Complete','Thunderstorms prevented work on that day','3',9,'2018-07-09','2018-07-11',110,NULL,NULL),(112,'Task1.3.2','2018-07-09','2018-07-10','Complete','Government procedures will change next year','3',9,'2018-07-09','2018-07-10',110,NULL,NULL),(113,'Task1.3.3','2018-07-09','2018-07-10','Complete','','3',9,'2018-07-09','2018-07-10',110,NULL,NULL),(114,'Main2','2018-07-11','2018-07-24','Complete','','1',9,'2018-07-11','2018-07-24',NULL,NULL,NULL),(115,'Sub2.1','2018-07-11','2018-07-13','Complete','','2',9,'2018-07-11','2018-07-13',114,NULL,NULL),(116,'Task2.1.1','2018-07-11','2018-07-12','Complete','Server down','3',9,'2018-07-11','2018-07-13',115,NULL,NULL),(117,'Task2.1.2','2018-07-12','2018-07-12','Complete','Server issue caused our delay','3',9,'2018-07-12','2018-07-13',115,NULL,NULL),(118,'Task2.1.3','2018-07-12','2018-07-13','Complete','','3',9,'2018-07-12','2018-07-13',115,NULL,NULL),(119,'Sub2.2','2018-07-14','2018-07-20','Complete','','2',9,'2018-07-14','2018-07-20',114,NULL,NULL),(120,'Task2.2.1','2018-07-14','2018-07-15','Complete','','3',9,'2018-07-14','2018-07-15',119,NULL,NULL),(121,'Task2.2.2','2018-07-14','2018-07-18','Complete','','3',9,'2018-07-14','2018-07-18',119,NULL,NULL),(122,'Task2.2.3','2018-07-16','2018-07-20','Complete','','3',9,'2018-07-16','2018-07-20',119,NULL,NULL),(123,'Sub2.3','2018-07-21','2018-07-24','Complete','','2',9,'2018-07-21','2018-07-24',114,NULL,NULL),(124,'Task2.3.1','2018-07-21','2018-07-24','Complete','','3',9,'2018-07-21','2018-07-24',123,NULL,NULL),(125,'Task2.3.2','2018-07-22','2018-07-22','Complete','','3',9,'2018-07-22','2018-07-22',123,NULL,NULL),(126,'Task2.3.3','2018-07-24','2018-07-24','Complete','','3',9,'2018-07-24','2018-07-24',123,NULL,NULL),(127,'Main3-Final Preparations','2018-07-25','2018-08-18','Ongoing','','1',9,'2018-07-25','',NULL,NULL,NULL),(128,'Sub3.1-Construction','2018-07-25','2018-08-03','Complete','','2',9,'2018-07-25','2018-08-03',127,NULL,NULL),(129,'Task3.1.1-Attach sample product sizing','2018-07-25','2018-07-28','Complete','','3',9,'2018-07-25','2018-07-28',128,NULL,NULL),(130,'Task3.1.2-Attach Light Bulbs','2018-07-25','2018-07-30','Complete','','3',9,'2018-07-25','2018-07-30',128,NULL,NULL),(131,'Task3.1.3-Test all elecrical equipment','2018-07-25','2018-08-03','Complete','','3',9,'2018-07-25','2018-08-03',128,NULL,NULL),(132,'Sub3.2-Inventory','2018-07-25','2018-08-15','Complete','','2',9,'2018-07-25','2018-08-15',127,NULL,NULL),(133,'Task3.2.1-Packaging inventory','2018-07-25','2018-08-05','Complete','','3',9,'2018-07-25','2018-08-05',132,NULL,NULL),(134,'Task3.2.2-Food inventory','2018-07-25','2018-08-10','Complete','','3',9,'2018-07-25','2018-08-10',132,NULL,NULL),(135,'Task3.2.3-Equipment inventory','2018-07-25','2018-08-15','Complete','','3',9,'2018-07-25','2018-08-15',132,NULL,NULL),(136,'Sub3.3-Final Procurement','2018-07-25','2018-08-18','Ongoing','','2',9,'2018-07-25','',127,NULL,NULL),(137,'Task3.3.1-Purchase Menu Board','2018-07-25','2018-08-18','Ongoing','','3',9,'2018-07-25','',136,NULL,NULL),(138,'Task3.3.2-Upload Menu Art for Printing','2018-07-25','2018-08-16','Ongoing','','3',9,'2018-07-25','',136,NULL,NULL),(139,'Task3.3.3-Receive Digital Menu TVs','2018-07-25','2018-07-30','Ongoing','','3',9,'2018-07-25','',136,NULL,NULL);
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `templates`
--

DROP TABLE IF EXISTS `templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `templates` (
  `PROJECTID` int(11) NOT NULL AUTO_INCREMENT,
  `PROJECTSTATUS` int(11) NOT NULL,
  `PROJECTTITLE` varchar(100) NOT NULL,
  `PROJECTSTARTDATE` varchar(20) NOT NULL,
  `PROJECTENDDATE` varchar(20) NOT NULL,
  `PROJECTDESCRIPTION` text NOT NULL,
  `users_USERID` int(11) NOT NULL COMMENT 'PROJECT OWNER',
  PRIMARY KEY (`PROJECTID`),
  KEY `fk_projects_users1_idx` (`users_USERID`),
  CONSTRAINT `fk_projects_users10` FOREIGN KEY (`users_USERID`) REFERENCES `users` (`USERID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `templates`
--

LOCK TABLES `templates` WRITE;
/*!40000 ALTER TABLE `templates` DISABLE KEYS */;
INSERT INTO `templates` VALUES (3,15,'Template Test Template','2018-07-22','2018-07-22','Project templates',4);
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
  `departments_DEPARTMENTID` int(11) NOT NULL,
  `usertype_USERTYPEID` int(11) NOT NULL,
  `users_SUPERVISORS` int(11) DEFAULT NULL,
  PRIMARY KEY (`USERID`),
  KEY `fk_users_departments_idx` (`departments_DEPARTMENTID`),
  KEY `fk_users_usertype1_idx` (`usertype_USERTYPEID`),
  KEY `fk_users_users_idx` (`users_SUPERVISORS`),
  CONSTRAINT `fk_users_departments` FOREIGN KEY (`departments_DEPARTMENTID`) REFERENCES `departments` (`DEPARTMENTID`),
  CONSTRAINT `fk_users_users` FOREIGN KEY (`users_SUPERVISORS`) REFERENCES `users` (`USERID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_usertype1` FOREIGN KEY (`usertype_USERTYPEID`) REFERENCES `usertype` (`USERTYPEID`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin','admin','testing','Admin',6,1,NULL),(2,'President','President','president','testing','President',1,2,NULL),(3,'Executive1','Executive','executive1','testing','Executive',1,2,2),(4,'Marketing','Head','mkthead','testing','Marketing Manager',2,3,3),(5,'Finance','Head','finhead','testing','Finance Manager',3,3,3),(6,'Procurement','Head','prochead','testing','Procurement Manager',4,3,3),(7,'HR','Head','hrhead','testing','Human Resource Manager',5,3,3),(8,'MIS','Head','mishead','testing','Management Information System Manager',6,3,3),(9,'Store Operations','Head','storeopshead','testing','Store Operations Manager',7,3,3),(10,'FAD','Head','fadhead','testing','Facilities Administration Manager',8,3,3),(11,'Marketing1','Supervisor','mktsup1','testing','Marketing Supervisor',2,4,4),(12,'Marketing2','Supervisor','mktsup2','testing','Marketing Supervisor',2,4,4),(13,'Finance1','Supervisor','finsup1','testing','Finance Supervisor',3,4,5),(14,'Finance2','Supervisor','finsup2','testing','Finance Supervisor',3,4,5),(15,'Procurement1','Supervisor','procsup1','testing','Procurement Supervisor',4,4,6),(16,'Procurement2','Supervisor','procsup2','testing','Procurement Supervisor',4,4,6),(17,'HR1','Supervisor','hrsup1','testing','HR Supervisor ',5,4,7),(18,'HR2','Supervisor','hrsup2','testing','HR Supervisor ',5,4,7),(19,'MIS1','Supervisor','missup1','testing','MIS Supervisor',6,4,8),(20,'MIS2','Supervisor','missup2','testing','MIS Supervisor',6,4,8),(21,'Store Operations1','Supervisor','sopsup1','testing','Store Operations Supervisor',7,4,9),(22,'Store Operations2','Supervisor','sopsup2','testing','Store Operations Supervisor',7,4,9),(23,'FAD1','Supervisor','fadsup1','testing','FAD Supervisor',8,4,10),(24,'FAD2','Supervisor','fadsup2','testing','FAD Supervisor',8,4,10),(25,'Marketing1','Staff','mktstaff1','testing','Marketing Staff',2,5,11),(26,'Marketing2','Staff','mktstaff2','testing','Marketing Staff',2,5,11),(27,'Marketing3','Staff','mktstaff3','testing','Marketing Staff',2,5,12),(28,'Marketing4','Staff','mktstaff4','testing','Marketing Staff',2,5,12),(29,'Finance1','Staff','finstaff1','testing','Finance Staff',3,5,13),(30,'Finance2','Staff','finstaff2','testing','Finance Staff',3,5,13),(31,'Finance3','Staff','finstaff3','testing','Finance Staff',3,5,14),(32,'Finance4','Staff','finstaff4','testing','Finance Staff',3,5,14),(33,'Procurement1','Staff','procstaff1','testing','Procurement Staff',4,5,15),(34,'Procurement2','Staff','procstaff2','testing','Procurement Staff',4,5,15),(35,'Procurement3','Staff','procstaff3','testing','Procurement Staff',4,5,16),(36,'Procurement4','Staff','procstaff4','testing','Procurement Staff',4,5,16),(37,'HR1','Staff','hrstaff1','testing','HR Staff',5,5,17),(38,'HR2','Staff','hrstaff2','testing','HR Staff',5,5,17),(39,'HR3','Staff','hrstaff3','testing','HR Staff',5,5,18),(40,'HR4','Staff','hrstaff4','testing','HR Staff',5,5,18),(41,'MIS1','Staff','misstaff1','testing','MIS Staff',6,5,19),(42,'MIS2','Staff','misstaff2','testing','MIS Staff',6,5,19),(43,'MIS3','Staff','misstaff3','testing','MIS Staff',6,5,20),(44,'MIS4','Staff','misstaff4','testing','MIS Staff',6,5,20),(45,'Store Operations1','Staff','sopsstaff1','testing','Store Operations Staff',7,5,21),(46,'Store Operations2','Staff','sopsstaff2','testing','Store Operations Staff',7,5,21),(47,'Store Operations3','Staff','sopsstaff3','testing','Store Operations Staff',7,5,22),(48,'Store Operations4','Staff','sopsstaff4','testing','Store Operations Staff',7,5,22),(49,'FAD1','Staff','fadstaff1','testing','FAD Staff',8,5,23),(50,'FAD2','Staff','fadstaff2','testing','FAD Staff',8,5,23),(51,'FAD3','Staff','fadstaff3','testing','FAD Staff',8,5,24),(52,'FAD4','Staff','fadstaff4','testing','FAD Staff',8,5,24),(53,'Executive2','Exevutive','executive2','testing','Executive',1,2,2);
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
  PRIMARY KEY (`USERTYPEID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usertype`
--

LOCK TABLES `usertype` WRITE;
/*!40000 ALTER TABLE `usertype` DISABLE KEYS */;
INSERT INTO `usertype` VALUES (1,'Admin'),(2,'Executive'),(3,'Department Head'),(4,'Department Supervisor'),(5,'Department Staff');
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

-- Dump completed on 2018-08-15  5:57:51
