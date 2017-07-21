/*
SQLyog Enterprise v10.42 
MySQL - 5.5.5-10.1.21-MariaDB : Database - smsgw_engine_db
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`smsgw_engine_db` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `smsgw_engine_db`;

/*Table structure for table `tb_app_config` */

DROP TABLE IF EXISTS `tb_app_config`;

CREATE TABLE `tb_app_config` (
  `id_app` int(11) NOT NULL AUTO_INCREMENT,
  `app_desc` varchar(200) DEFAULT NULL,
  `cost_pull` int(20) DEFAULT NULL,
  `cost_push` int(20) DEFAULT NULL,
  `push_time` varchar(100) DEFAULT NULL,
  `app_create` varchar(20) DEFAULT NULL,
  `partner` varchar(200) DEFAULT NULL,
  `contact` varchar(200) DEFAULT NULL,
  `marketing` varchar(200) DEFAULT NULL,
  `pic` varchar(200) DEFAULT NULL,
  `now_date` varchar(100) DEFAULT NULL,
  `config_status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_app`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `tb_app_config` */

insert  into `tb_app_config`(`id_app`,`app_desc`,`cost_pull`,`cost_push`,`push_time`,`app_create`,`partner`,`contact`,`marketing`,`pic`,`now_date`,`config_status`) values (1,'bola',1000,10000,'mon,wed,fri','2017-05-29 05:09:56','Jati','Lens','Office','Denis','2017-07-21','1'),(2,'cantik',900,900,'1,3,5,21','2017-05-29 05:09:56','Denis','Telpon','Jualan','Jati','2017-07-21','1');

/*Table structure for table `tb_apps_content` */

DROP TABLE IF EXISTS `tb_apps_content`;

CREATE TABLE `tb_apps_content` (
  `id_content` int(11) NOT NULL AUTO_INCREMENT,
  `id_app` int(11) DEFAULT NULL,
  `keyword` varchar(100) DEFAULT NULL,
  `content_number` int(11) DEFAULT NULL,
  `content_field` varchar(200) DEFAULT NULL,
  `content_create` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_content`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `tb_apps_content` */

insert  into `tb_apps_content`(`id_content`,`id_app`,`keyword`,`content_number`,`content_field`,`content_create`) values (1,1,'bola',1,'Reply Bola Pertama','2017-05-29 05:09:56'),(2,1,'bola',2,'Reply Bola Kedua','2017-05-29 05:09:56');

/*Table structure for table `tb_keyword` */

DROP TABLE IF EXISTS `tb_keyword`;

CREATE TABLE `tb_keyword` (
  `id_keyword` int(11) NOT NULL AUTO_INCREMENT,
  `id_app` int(11) DEFAULT NULL,
  `telco` varchar(100) DEFAULT NULL,
  `shortcode` varchar(100) DEFAULT NULL,
  `keyword` varchar(100) DEFAULT NULL,
  `keyword_status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_keyword`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `tb_keyword` */

insert  into `tb_keyword`(`id_keyword`,`id_app`,`telco`,`shortcode`,`keyword`,`keyword_status`) values (1,1,'xl','912345','bola','1');

/*Table structure for table `tb_members` */

DROP TABLE IF EXISTS `tb_members`;

CREATE TABLE `tb_members` (
  `id_member` int(11) NOT NULL AUTO_INCREMENT,
  `telco` varchar(100) DEFAULT NULL,
  `shortcode` varchar(100) DEFAULT NULL,
  `msisdn` varchar(20) DEFAULT NULL,
  `id_app` int(11) DEFAULT NULL,
  `keyword` varchar(100) DEFAULT NULL,
  `join_date` varchar(50) DEFAULT NULL,
  `reg_types` varchar(10) DEFAULT NULL,
  `content_seq` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_member`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `tb_members` */

insert  into `tb_members`(`id_member`,`telco`,`shortcode`,`msisdn`,`id_app`,`keyword`,`join_date`,`reg_types`,`content_seq`) values (12,'xl','912345','6281966655241',1,'bola','2017-07-21 01:34:19','reg',1);

/*Table structure for table `tb_partner` */

DROP TABLE IF EXISTS `tb_partner`;

CREATE TABLE `tb_partner` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `id_partner` int(8) DEFAULT NULL,
  `name_partner` varchar(100) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tb_partner` */

/*Table structure for table `tb_telco_config` */

DROP TABLE IF EXISTS `tb_telco_config`;

CREATE TABLE `tb_telco_config` (
  `id_telco` int(11) NOT NULL AUTO_INCREMENT,
  `telco_name` varchar(50) DEFAULT NULL,
  `push_limit` int(11) DEFAULT NULL,
  `pull_limit` int(11) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `shortname` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_telco`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `tb_telco_config` */

insert  into `tb_telco_config`(`id_telco`,`telco_name`,`push_limit`,`pull_limit`,`address`,`username`,`password`,`shortname`) values (1,'xl',40,10,'http://localhost/simulator-php/push.php','adminDB','passwordDb','ddd');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
