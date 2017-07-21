/*
SQLyog Enterprise v10.42 
MySQL - 5.5.5-10.1.21-MariaDB : Database - smsgw_engine_log
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`smsgw_engine_log` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `smsgw_engine_log`;

/*Table structure for table `tb_dr_2017_07_21` */

DROP TABLE IF EXISTS `tb_dr_2017_07_21`;

CREATE TABLE `tb_dr_2017_07_21` (
  `id_dr` int(11) NOT NULL AUTO_INCREMENT,
  `telco` varchar(20) DEFAULT NULL,
  `shortcode` varchar(20) DEFAULT NULL,
  `msisdn` varchar(20) DEFAULT NULL,
  `trx_id` varchar(50) DEFAULT NULL,
  `trx_date` varchar(50) DEFAULT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  `session_date` varchar(50) DEFAULT NULL,
  `stat` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_dr`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `tb_dr_2017_07_21` */

/*Table structure for table `tb_dr_summary` */

DROP TABLE IF EXISTS `tb_dr_summary`;

CREATE TABLE `tb_dr_summary` (
  `id_dr` int(11) NOT NULL AUTO_INCREMENT,
  `telco` varchar(20) DEFAULT NULL,
  `shortcode` varchar(20) DEFAULT NULL,
  `msisdn` varchar(20) DEFAULT NULL,
  `trx_id` varchar(50) DEFAULT NULL,
  `trx_date` varchar(50) DEFAULT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  `session_date` varchar(50) DEFAULT NULL,
  `stat` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_dr`)
) ENGINE=InnoDB AUTO_INCREMENT=27963 DEFAULT CHARSET=latin1;

/*Data for the table `tb_dr_summary` */

insert  into `tb_dr_summary`(`id_dr`,`telco`,`shortcode`,`msisdn`,`trx_id`,`trx_date`,`session_id`,`session_date`,`stat`) values (27960,'xl','912345','6281966655241','9922112241','2017-07-21 14-32-29','16375103','2017-07-21 02:32:29','3'),(27961,'xl','912345','6281966655241','ID-173004655','2017-07-21 14-32-29','44426848','2017-07-21 02:32:29','3'),(27962,'xl','912345','6281966655241','ID-928423836','2017-07-21 14-32-29','860113','2017-07-21 02:32:29','3');

/*Table structure for table `tb_dr_today` */

DROP TABLE IF EXISTS `tb_dr_today`;

CREATE TABLE `tb_dr_today` (
  `id_dr` int(11) NOT NULL AUTO_INCREMENT,
  `telco` varchar(20) DEFAULT NULL,
  `shortcode` varchar(20) DEFAULT NULL,
  `msisdn` varchar(20) DEFAULT NULL,
  `trx_id` varchar(50) DEFAULT NULL,
  `trx_date` varchar(50) DEFAULT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  `session_date` varchar(50) DEFAULT NULL,
  `stat` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_dr`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `tb_dr_today` */

/*Table structure for table `tb_mo_2017_07_21` */

DROP TABLE IF EXISTS `tb_mo_2017_07_21`;

CREATE TABLE `tb_mo_2017_07_21` (
  `id_mo` int(11) NOT NULL AUTO_INCREMENT,
  `telco` varchar(20) DEFAULT NULL,
  `shortcode` varchar(20) DEFAULT NULL,
  `msisdn` varchar(20) DEFAULT NULL,
  `sms_field` varchar(200) DEFAULT NULL,
  `keyword` varchar(50) DEFAULT NULL,
  `trx_id` varchar(50) DEFAULT NULL,
  `trx_date` varchar(50) DEFAULT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  `session_date` varchar(50) DEFAULT NULL,
  `reg_type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_mo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `tb_mo_2017_07_21` */

/*Table structure for table `tb_mo_summary` */

DROP TABLE IF EXISTS `tb_mo_summary`;

CREATE TABLE `tb_mo_summary` (
  `id_mo` int(11) NOT NULL AUTO_INCREMENT,
  `telco` varchar(20) DEFAULT NULL,
  `shortcode` varchar(20) DEFAULT NULL,
  `msisdn` varchar(20) DEFAULT NULL,
  `sms_field` varchar(200) DEFAULT NULL,
  `keyword` varchar(50) DEFAULT NULL,
  `trx_id` varchar(50) DEFAULT NULL,
  `trx_date` varchar(50) DEFAULT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  `session_date` varchar(50) DEFAULT NULL,
  `reg_type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_mo`)
) ENGINE=InnoDB AUTO_INCREMENT=13670 DEFAULT CHARSET=latin1;

/*Data for the table `tb_mo_summary` */

insert  into `tb_mo_summary`(`id_mo`,`telco`,`shortcode`,`msisdn`,`sms_field`,`keyword`,`trx_id`,`trx_date`,`session_id`,`session_date`,`reg_type`) values (13666,'xl','912345','6281966655241','reg bola','bola','9922112241','2017-07-21','12881393','2017-07-21 01:34:19','reg'),(13667,'xl','912345','6281966655241','reg bola','bola','9922112241','2017-07-21','12881393','2017-07-21 01:34:19','reg'),(13668,'xl','912345','6281966655241','reg bola','bola','9922112241','2017-07-21','12881393','2017-07-21 01:34:19','reg'),(13669,'xl','912345','6281966655241','reg bola','bola','9922112241','2017-07-21','12881393','2017-07-21 01:34:19','reg');

/*Table structure for table `tb_mo_today` */

DROP TABLE IF EXISTS `tb_mo_today`;

CREATE TABLE `tb_mo_today` (
  `id_mo` int(11) NOT NULL AUTO_INCREMENT,
  `telco` varchar(20) DEFAULT NULL,
  `shortcode` varchar(20) DEFAULT NULL,
  `msisdn` varchar(20) DEFAULT NULL,
  `sms_field` varchar(200) DEFAULT NULL,
  `keyword` varchar(50) DEFAULT NULL,
  `trx_id` varchar(50) DEFAULT NULL,
  `trx_date` varchar(50) DEFAULT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  `session_date` varchar(50) DEFAULT NULL,
  `reg_type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_mo`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*Data for the table `tb_mo_today` */

/*Table structure for table `tb_push_2017_07_21` */

DROP TABLE IF EXISTS `tb_push_2017_07_21`;

CREATE TABLE `tb_push_2017_07_21` (
  `id_push` int(11) NOT NULL AUTO_INCREMENT,
  `telco` varchar(20) DEFAULT NULL,
  `shortcode` varchar(20) DEFAULT NULL,
  `msisdn` varchar(20) DEFAULT NULL,
  `sms_field` varchar(200) DEFAULT NULL,
  `keyword` varchar(100) DEFAULT NULL,
  `content_number` int(11) DEFAULT NULL,
  `content_field` varchar(200) DEFAULT NULL,
  `trx_id` varchar(250) DEFAULT NULL,
  `trx_date` varchar(20) DEFAULT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `session_date` varchar(20) DEFAULT NULL,
  `reg_type` varchar(10) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `cost` varchar(10) DEFAULT NULL,
  `send_status` varchar(10) DEFAULT NULL,
  `response_code` varchar(10) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_push`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tb_push_2017_07_21` */

/*Table structure for table `tb_push_summary` */

DROP TABLE IF EXISTS `tb_push_summary`;

CREATE TABLE `tb_push_summary` (
  `id_push` int(11) NOT NULL AUTO_INCREMENT,
  `telco` varchar(20) DEFAULT NULL,
  `shortcode` varchar(20) DEFAULT NULL,
  `msisdn` varchar(20) DEFAULT NULL,
  `sms_field` varchar(200) DEFAULT NULL,
  `id_app` int(11) DEFAULT NULL,
  `keyword` varchar(100) DEFAULT NULL,
  `content_number` int(11) DEFAULT NULL,
  `content_field` varchar(200) DEFAULT NULL,
  `trx_id` varchar(250) DEFAULT NULL,
  `trx_date` varchar(20) DEFAULT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `session_date` varchar(20) DEFAULT NULL,
  `reg_type` varchar(10) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `cost` varchar(10) DEFAULT NULL,
  `send_status` varchar(10) DEFAULT NULL,
  `response_code` varchar(10) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_push`)
) ENGINE=InnoDB AUTO_INCREMENT=7544 DEFAULT CHARSET=latin1;

/*Data for the table `tb_push_summary` */

/*Table structure for table `tb_push_today` */

DROP TABLE IF EXISTS `tb_push_today`;

CREATE TABLE `tb_push_today` (
  `id_push` int(11) NOT NULL AUTO_INCREMENT,
  `telco` varchar(20) DEFAULT NULL,
  `shortcode` varchar(20) DEFAULT NULL,
  `msisdn` varchar(20) DEFAULT NULL,
  `sms_field` varchar(200) DEFAULT NULL,
  `id_app` int(11) DEFAULT NULL,
  `keyword` varchar(100) DEFAULT NULL,
  `content_number` int(11) DEFAULT NULL,
  `content_field` varchar(200) DEFAULT NULL,
  `trx_id` varchar(250) DEFAULT NULL,
  `trx_date` varchar(20) DEFAULT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `session_date` varchar(20) DEFAULT NULL,
  `reg_type` varchar(10) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `cost` varchar(10) DEFAULT NULL,
  `send_status` varchar(10) DEFAULT NULL,
  `response_code` varchar(10) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_push`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

/*Data for the table `tb_push_today` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
