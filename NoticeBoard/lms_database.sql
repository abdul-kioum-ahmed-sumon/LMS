/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 10.4.32-MariaDB : Database - lms_database
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`lms_database` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `lms_database`;

/*Table structure for table `notices` */

DROP TABLE IF EXISTS `notices`;

CREATE TABLE `notices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `notices` */

insert  into `notices`(`id`,`title`,`content`,`created_at`,`photo`) values 
(28,'Notice For Holiday!','ggggg','2024-04-15 22:30:45',NULL),
(30,'bbddkabkdbakdbkabdk','kbdaskbdkasbkd','2024-04-15 22:34:52',NULL),
(31,'hahdhahdj','MAHHSBDBAjbbAdeee','2024-04-15 22:36:35',NULL);

/*Table structure for table `notices2` */

DROP TABLE IF EXISTS `notices2`;

CREATE TABLE `notices2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `notices2` */

insert  into `notices2`(`id`,`title`,`content`,`file_path`,`created_at`) values 
(4,'Notice For Holiday!','afdfesdfdsfdsfds','uploads/172802.webp','2024-04-15 23:43:34'),
(6,'eeeeee','hellow akij','uploads/gaming-19.jpg','2024-04-16 11:23:31');

/*Table structure for table `staff` */

DROP TABLE IF EXISTS `staff`;

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `position` varchar(100) NOT NULL,
  PRIMARY KEY (`staff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `staff` */

insert  into `staff`(`staff_id`,`name`,`email`,`password`,`position`) values 
(7,'Anika','sumaiyajafrin0@gmail.com','Anika','teacher'),
(9,'b nccgcghc','chhfxx@gmail.com','fcdhdhgchc','Sales Rep'),
(10,'seeeeeeeee','sumaiyajafrin@gmail.com','sumaiya','manager');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users_3` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `batch` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `users` */

insert  into `users`(`user_id`,`student_id`,`name`,`department`,`phone`,`batch`,`created_at`) values 
(7,'220201013','Anika','CSE','013666454','15','2024-04-16 01:45:15'),
(10,'21311','vgchgchgcghc','chccghv','3256111614','15','2024-04-16 10:49:28'),
(11,'220201013','wrrtygdssc','CSE','099987654334','15','2024-04-16 11:16:26');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
