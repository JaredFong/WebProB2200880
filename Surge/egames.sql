/*
SQLyog Community v8.4 RC2
MySQL - 5.7.19 : Database - egames
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`egames` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `egames`;

/*Table structure for table `games_index` */

DROP TABLE IF EXISTS `games_index`;

CREATE TABLE `games_index` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `games_name` varchar(255) DEFAULT NULL,
  `index_images` varchar(255) DEFAULT NULL,
  `new_images` varchar(255) DEFAULT NULL,
  `url_link` varchar(255) DEFAULT NULL,
  `status` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `games_index` */

insert  into `games_index`(`id`,`games_name`,`index_images`,`new_images`,`url_link`,`status`) values (1,'Counter Strike 2','cs2.png','CS2banner.png','games/CS2GamePage/CS2GamePage.html','Active'),(2,'Rainbow Six','r6(2).png','RainbowBanner.jpg','games/R6GamePage/R6GamePage.html','Active'),(3,'APEX Legends','apex.png','ALUpdateBanner.jpg','games/ALGamesPage/ALGamePage.html','Active');

/*Table structure for table `news_update` */

DROP TABLE IF EXISTS `news_update`;

CREATE TABLE `news_update` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_games` int(11) NOT NULL,
  `images` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `news` longtext,
  `created_date` date NOT NULL,
  PRIMARY KEY (`id`,`id_games`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `news_update` */

insert  into `news_update`(`id`,`id_games`,`images`,`title`,`news`,`created_date`) values (1,1,'ALnews1.png','TITLE','test...','2024-07-05'),(7,2,'R6news1.png','Roadmap Update','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','2024-07-05'),(8,2,'R6news2.png','Roadmap Season Update','Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.','2024-07-05');

/*Table structure for table `reviews` */

DROP TABLE IF EXISTS `reviews`;

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_games` int(11) DEFAULT NULL,
  `posted` longtext,
  `posted_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `reviews` */

insert  into `reviews`(`id`,`id_games`,`posted`,`posted_by`,`created_date`) values (1,1,'I recently had the pleasure of diving into \"CS 2,\" and I must say, it’s one of the most captivating games I’ve played in recent years. Here’s a breakdown of my experience',1,'2024-07-04 20:05:00'),(2,1,'The graphics in \"CS 2\" are outstanding. The developers have clearly put a lot of effort into enhancing the visual experience. The environments are highly detailed, with realistic textures and lighting that bring the game to life. Character models are meticulously designed, and the animations are fluid and lifelike, making every match feel intense and immersive.',1,'2024-07-05 20:15:00'),(5,1,'The audio design in \"CS 2\" is top-notch. The sound effects are crisp and realistic, from the clinking of reloading to the distant echo of footsteps, which adds a layer of depth to the gameplay. The soundtrack is well-composed, enhancing the tension and excitement during matches without being overly intrusive.',1,'2024-07-05 02:46:00'),(6,1,'test',1,'2024-07-05 22:47:04'),(10,2,'test',1,'2024-07-12 09:52:50'),(12,1,'Overall, &quot;CS 2&quot; is a phenomenal game that stands out in the crowded FPS market. Its stunning visuals, tight gameplay mechanics, and immersive audio design make it a must-play for any fan of the genre. While there are occasional server issues and some balance tweaks needed, these are minor compared to the overall experience.\r\n\r\nIf you\'re looking for a thrilling and engaging shooter that will keep you coming back for more, &quot;CS 2&quot; is definitely worth your time. Jump in, gear up, and get ready for some intense action!',1,'2024-07-12 09:56:28');

/*Table structure for table `setting` */

DROP TABLE IF EXISTS `setting`;

CREATE TABLE `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` longtext,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `setting` */

insert  into `setting`(`id`,`message`,`status`) values (1,'Welcome to our gaming portal forum! This is a place where gamers can come together to discuss their favorite games, share opinions, ask questions, and engage in discussions about various gaming topics. Whether you\'re looking for tips, want to share your latest achievement, or simply chat about gaming news, you\'re in the right place. Enjoy your stay and happy gaming!','Announcement'),(2,'Threads on arguments or sensitive issues are prohibited.','Regulation'),(3,'No spamming or unofficial promotion in respective threads.','Regulation'),(4,'Keep posts relevant to the topic to prevent sidetracking.','Regulation'),(5,'Use appropriate language in thread discussions.','Regulation');

/*Table structure for table `thread_post` */

DROP TABLE IF EXISTS `thread_post`;

CREATE TABLE `thread_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_thread` int(11) DEFAULT NULL,
  `posted` longtext,
  `posted_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `thread_post` */

insert  into `thread_post`(`id`,`id_thread`,`posted`,`posted_by`,`created_date`) values (1,1,'test',1,'2024-07-04 20:05:00'),(2,1,'test 2',1,'2024-07-05 20:15:00'),(5,1,'test 2',1,'2024-07-05 02:46:00'),(6,1,'test',1,'2024-07-05 22:47:04');

/*Table structure for table `thread_title` */

DROP TABLE IF EXISTS `thread_title`;

CREATE TABLE `thread_title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_games` int(11) DEFAULT NULL,
  `thread_title` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `thread_title` */

insert  into `thread_title`(`id`,`id_games`,`thread_title`,`created_by`,`created_date`) values (1,1,'Popular Opinion: CS2 is better than CS1',1,'2024-07-05 20:59:04'),(2,2,'Tom Clancy is releasing an amazing feature',1,'2024-07-05 20:59:04'),(3,3,'How to get good in Apex Legends?',1,'2024-07-05 20:59:04'),(8,1,'Test Tajuk',1,'2024-07-05 20:59:23'),(9,3,'Test Tajuk 2',1,'2024-07-05 21:01:05');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(25) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `role` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`password`,`name`,`profile_image`,`role`) values (1,'arifwahab@rocketmail.com','123','Warlock','pngtree-man-avatar-image-for-profile-png-image_13001882','User'),(2,'demptarif@gmail.com','123','Admin',NULL,'Admin'),(3,'artacia@gmail.com','123','Artacia','pngtree-user-profile-avatar-png-image_13369988','User'),(4,'teh@rocketmail.com','123','test','images.png','User');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
