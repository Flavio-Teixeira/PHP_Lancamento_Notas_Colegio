/*
SQLyog Community Edition- MySQL GUI v8.12 
MySQL - 5.1.30-community-log : Database - griddemo
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`griddemo` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `griddemo`;

/*Table structure for table `clients` */

DROP TABLE IF EXISTS `clients`;

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(120) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

/*Data for the table `clients` */

insert into `clients` (`name`, `gender`, `company`) values('Maria Anders','male','Alfreds Futterkiste');
insert into `clients` (`name`, `gender`, `company`) values('Ana Trujillo','male','Ana Trujillo Emparedados y helados');
insert into `clients` (`name`, `gender`, `company`) values('Antonio Moreno','male','Antonio Moreno Taquería');
insert into `clients` (`name`, `gender`, `company`) values('Hanna Moos','male','Blauer See Delikatessen');
insert into `clients` (`name`, `gender`, `company`) values('Frédérique Citeaux','female','Blondel père et fils');
insert into `clients` (`name`, `gender`, `company`) values('Laurence Lebihan','female','Bon app\'');
insert into `clients` (`name`, `gender`, `company`) values('Elizabeth Lincoln','female','Bottom-Dollar Markets');
insert into `clients` (`name`, `gender`, `company`) values('Victoria Ashworth','female','B\'s Beverages');
insert into `clients` (`name`, `gender`, `company`) values('Patricio Simpson','female','Cactus Comidas para llevar');
insert into `clients` (`name`, `gender`, `company`) values('Francisco Chang','female','Centro comercial Moctezuma');
insert into `clients` (`name`, `gender`, `company`) values('Yang Wang','female','Chop-suey Chinese');
insert into `clients` (`name`, `gender`, `company`) values('Pedro Afonso','male','Comércio Mineiro');
insert into `clients` (`name`, `gender`, `company`) values('Elizabeth Brown','female','Consolidated Holdings');
insert into `clients` (`name`, `gender`, `company`) values('Sven Ottlieb','male','Drachenblut Delikatessen');
insert into `clients` (`name`, `gender`, `company`) values('Janine Labrune','female','Du monde entier');
insert into `clients` (`name`, `gender`, `company`) values('Ann Devon','female','Eastern Connection');
insert into `clients` (`name`, `gender`, `company`) values('Roland Mendel','male','Ernst Handel');
insert into `clients` (`name`, `gender`, `company`) values('Aria Cruz','female','Familia Arquibaldo');
insert into `clients` (`name`, `gender`, `company`) values('Diego Roel','female','FISSA Fabrica Inter. Salchichas S.A.');
insert into `clients` (`name`, `gender`, `company`) values('Martine Rancé','male','Folies gourmandes');
insert into `clients` (`name`, `gender`, `company`) values('Maria Larsson','female','Folk och fä HB');
insert into `clients` (`name`, `gender`, `company`) values('Peter Franken','female','Frankenversand');
insert into `clients` (`name`, `gender`, `company`) values('Carine Schmitt','male','France restauration');
insert into `clients` (`name`, `gender`, `company`) values('Paolo Accorti','female','Franchi S.p.A.');
insert into `clients` (`name`, `gender`, `company`) values('Lino Rodriguez','male','Furia Bacalhau e Frutos do Mar');
insert into `clients` (`name`, `gender`, `company`) values('Eduardo Saavedra','male','Galería del gastrónomo');
insert into `clients` (`name`, `gender`, `company`) values('José Pedro Freyre','male','Godos Cocina Típica');
insert into `clients` (`name`, `gender`, `company`) values('André Fonseca','male','Gourmet Lanchonetes');
insert into `clients` (`name`, `gender`, `company`) values('Howard Snyder','female','Great Lakes Food Market');
insert into `clients` (`name`, `gender`, `company`) values('Manuel Pereira','male','GROSELLA-Restaurante');
insert into `clients` (`name`, `gender`, `company`) values('Mario Pontes','female','Hanari Carnes');
insert into `clients` (`name`, `gender`, `company`) values('Carlos Hernández','male','HILARIÓN-Abastos');
insert into `clients` (`name`, `gender`, `company`) values('Yoshi Latimer','male','Hungry Coyote Import Store');
insert into `clients` (`name`, `gender`, `company`) values('Patricia McKenna','female','Hungry Owl All-Night Grocers');
insert into `clients` (`name`, `gender`, `company`) values('Helen Bennett','female','Island Trading');
insert into `clients` (`name`, `gender`, `company`) values('Philip Cramer','female','Königlich Essen');
insert into `clients` (`name`, `gender`, `company`) values('Daniel Tonini','male','La corne d\'abondance');
insert into `clients` (`name`, `gender`, `company`) values('Annette Roulet','male','La maison d\'Asie');
insert into `clients` (`name`, `gender`, `company`) values('Yoshi Tannamuri','female','Laughing Bacchus Wine Cellars');
insert into `clients` (`name`, `gender`, `company`) values('John Steel','male','Lazy K Kountry Store');
insert into `clients` (`name`, `gender`, `company`) values('Renate Messner','male','Lehmanns Marktstand');
insert into `clients` (`name`, `gender`, `company`) values('Jaime Yorres','female','Let\'s Stop N Shop');
insert into `clients` (`name`, `gender`, `company`) values('Carlos González','male','LILA-Supermercado');
insert into `clients` (`name`, `gender`, `company`) values('Felipe Izquierdo','male','LINO-Delicateses');
insert into `clients` (`name`, `gender`, `company`) values('Fran Wilson','male','Lonesome Pine Restaurant');
insert into `clients` (`name`, `gender`, `company`) values('Giovanni Rovelli','male','Magazzini Alimentari Riuniti');
insert into `clients` (`name`, `gender`, `company`) values('Catherine Dewey','female','Maison Dewey');
insert into `clients` (`name`, `gender`, `company`) values('Jean Fresnière','female','Mère Paillarde');
insert into `clients` (`name`, `gender`, `company`) values('Alexander Feuer','female','Morgenstern Gesundkost');
insert into `clients` (`name`, `gender`, `company`) values('Simon Crowther','male','North/South');
insert into `clients` (`name`, `gender`, `company`) values('Yvonne Moncada','male','Océano Atlántico Ltda.');
insert into `clients` (`name`, `gender`, `company`) values('Rene Phillips','male','Old World Delicatessen');
insert into `clients` (`name`, `gender`, `company`) values('Henriette Pfalzheim','male','Ottilies Käseladen');
insert into `clients` (`name`, `gender`, `company`) values('Marie Bertrand','male','Paris spécialités');
insert into `clients` (`name`, `gender`, `company`) values('Guillermo Fernández','male','Pericles Comidas clásicas');
insert into `clients` (`name`, `gender`, `company`) values('Georg Pipps','female','Piccolo und mehr');
insert into `clients` (`name`, `gender`, `company`) values('Isabel de Castro','male','Princesa Isabel Vinhos');
insert into `clients` (`name`, `gender`, `company`) values('Bernardo Batista','male','Que Delícia');
insert into `clients` (`name`, `gender`, `company`) values('Lúcia Carvalho','male','Queen Cozinha');
insert into `clients` (`name`, `gender`, `company`) values('Horst Kloss','female','QUICK-Stop');
insert into `clients` (`name`, `gender`, `company`) values('Sergio Gutiérrez','male','Rancho grande');
insert into `clients` (`name`, `gender`, `company`) values('Paula Wilson','female','Rattlesnake Canyon Grocery');
insert into `clients` (`name`, `gender`, `company`) values('Maurizio Moroni','female','Reggiani Caseifici');
insert into `clients` (`name`, `gender`, `company`) values('Janete Limeira','female','Ricardo Adocicados');
insert into `clients` (`name`, `gender`, `company`) values('Michael Holz','female','Richter Supermarkt');
insert into `clients` (`name`, `gender`, `company`) values('Alejandra Camino','female','Romero y tomillo');
insert into `clients` (`name`, `gender`, `company`) values('Jonas Bergulfsen','female','Santé Gourmet');
insert into `clients` (`name`, `gender`, `company`) values('Jose Pavarotti','female','Save-a-lot Markets');
insert into `clients` (`name`, `gender`, `company`) values('Hari Kumar','female','Seven Seas Imports');
insert into `clients` (`name`, `gender`, `company`) values('Jytte Petersen','male','Simons bistro');
insert into `clients` (`name`, `gender`, `company`) values('Dominique Perrier','female','Spécialités du monde');
insert into `clients` (`name`, `gender`, `company`) values('Art Braunschweiger','male','Split Rail Beer & Ale');
insert into `clients` (`name`, `gender`, `company`) values('Pascale Cartrain','male','Suprêmes délices');
insert into `clients` (`name`, `gender`, `company`) values('Liz Nixon','female','The Big Cheese');
insert into `clients` (`name`, `gender`, `company`) values('Liu Wong','male','The Cracker Box');
insert into `clients` (`name`, `gender`, `company`) values('Karin Josephs','female','Toms Spezialitäten');
insert into `clients` (`name`, `gender`, `company`) values('Miguel Angel Paolino','female','Tortuga Restaurante');
insert into `clients` (`name`, `gender`, `company`) values('Anabela Domingues','male','Tradição Hipermercados');
insert into `clients` (`name`, `gender`, `company`) values('Helvetius Nagy','male','Trail\'s Head Gourmet Provisioners');
insert into `clients` (`name`, `gender`, `company`) values('Palle Ibsen','male','Vaffeljernet');
insert into `clients` (`name`, `gender`, `company`) values('Mary Saveley','female','Victuailles en stock');
insert into `clients` (`name`, `gender`, `company`) values('Paul Henriot','male','Vins et alcools Chevalier');
insert into `clients` (`name`, `gender`, `company`) values('Rita Müller','female','Die Wandernde Kuh');
insert into `clients` (`name`, `gender`, `company`) values('Pirkko Koskitalo','male','Wartian Herkku');
insert into `clients` (`name`, `gender`, `company`) values('Paula Parente','male','Wellington Importadora');
insert into `clients` (`name`, `gender`, `company`) values('Karl Jablonski','female','White Clover Markets');
insert into `clients` (`name`, `gender`, `company`) values('Matti Karttunen','female','Wilman Kala');
insert into `clients` (`name`, `gender`, `company`) values('Zbyszek Piestrzeniewicz','male','Wolski  Zajazd');

/*Table structure for table `invheader` */

DROP TABLE IF EXISTS `invheader`;

CREATE TABLE `invheader` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invdate` date NOT NULL,
  `client_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `note` char(100) DEFAULT NULL,
  `closed` char(3) DEFAULT 'No',
  `ship_via` char(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

/*Data for the table `invheader` */

insert  into `invheader`(`id`,`invdate`,`client_id`,`amount`,`tax`,`total`,`note`,`closed`,`ship_via`) values (1,'2007-10-01',1,'100.00','20.00','120.00','note 1','No',NULL);
insert  into `invheader`(`id`,`invdate`,`client_id`,`amount`,`tax`,`total`,`note`,`closed`,`ship_via`) values (2,'2007-10-03',1,'200.00','40.00','240.00','note 2','No',NULL);
insert  into `invheader`(`id`,`invdate`,`client_id`,`amount`,`tax`,`total`,`note`,`closed`,`ship_via`) values (3,'2007-10-02',2,'300.00','60.00','360.00','note for invoice 3','yes','');
insert  into `invheader`(`id`,`invdate`,`client_id`,`amount`,`tax`,`total`,`note`,`closed`,`ship_via`) values (4,'2007-10-04',3,'150.00','0.00','150.00','no tax','No',NULL);
insert  into `invheader`(`id`,`invdate`,`client_id`,`amount`,`tax`,`total`,`note`,`closed`,`ship_via`) values (5,'2007-10-05',3,'100.00','0.00','100.00','no tax','No',NULL);
insert  into `invheader`(`id`,`invdate`,`client_id`,`amount`,`tax`,`total`,`note`,`closed`,`ship_via`) values (6,'2007-10-05',1,'50.00','10.00','60.00',NULL,'No',NULL);
insert  into `invheader`(`id`,`invdate`,`client_id`,`amount`,`tax`,`total`,`note`,`closed`,`ship_via`) values (7,'2007-10-05',2,'120.00','12.00','134.00',NULL,'No',NULL);
insert  into `invheader`(`id`,`invdate`,`client_id`,`amount`,`tax`,`total`,`note`,`closed`,`ship_via`) values (8,'2007-10-06',3,'200.00','0.00','200.00',NULL,'No',NULL);
insert  into `invheader`(`id`,`invdate`,`client_id`,`amount`,`tax`,`total`,`note`,`closed`,`ship_via`) values (9,'2007-10-06',1,'200.00','40.00','240.00',NULL,'No',NULL);
insert  into `invheader`(`id`,`invdate`,`client_id`,`amount`,`tax`,`total`,`note`,`closed`,`ship_via`) values (10,'2007-10-06',2,'100.00','20.00','120.00','','No3','');
insert  into `invheader`(`id`,`invdate`,`client_id`,`amount`,`tax`,`total`,`note`,`closed`,`ship_via`) values (11,'2007-10-06',1,'600.00','120.00','720.00',NULL,'No',NULL);
insert  into `invheader`(`id`,`invdate`,`client_id`,`amount`,`tax`,`total`,`note`,`closed`,`ship_via`) values (12,'2007-10-06',2,'700.00','140.00','840.00',NULL,'No',NULL);
insert  into `invheader`(`id`,`invdate`,`client_id`,`amount`,`tax`,`total`,`note`,`closed`,`ship_via`) values (13,'2007-10-06',3,'1000.00','0.00','1000.00',NULL,'No',NULL);

/*Table structure for table `invlines` */

DROP TABLE IF EXISTS `invlines`;

CREATE TABLE `invlines` (
  `id` int(11) NOT NULL,
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `item` char(20) DEFAULT NULL,
  `qty` decimal(8,2) NOT NULL DEFAULT '0.00',
  `unit` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`,`num`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `invlines` */

insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (1,1,'item 1','1.00','20.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (1,2,'item 2','2.00','40.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (2,1,'item 1','2.00','20.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (2,2,'item 2','4.00','40.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (3,1,'item 3','1.00','100.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (3,2,'item 4','1.00','200.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (4,1,'item 1','1.00','100.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (4,2,'item 2','1.00','50.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (5,1,'item 3','1.00','100.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (6,1,'item 4','1.00','50.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (7,1,'item 5','2.00','10.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (7,2,'item 1','1.00','100.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (8,1,'item 3','1.00','50.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (8,2,'item 2','1.00','120.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (8,3,'item 3','1.00','30.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (9,1,'item 6','1.00','140.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (9,2,'item 3','1.00','60.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (10,1,'item 5','3.00','10.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (10,2,'item 4','1.00','70.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (11,1,'item 1','2.00','100.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (11,2,'item 2','3.00','50.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (11,3,'item 3','1.00','50.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (11,4,'item 4','1.00','200.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (12,1,'item 4','1.00','300.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (12,2,'item 2','1.00','400.00');
insert  into `invlines`(`id`,`num`,`item`,`qty`,`unit`) values (13,1,'item 13','1.00','1000.00');

/*Table structure for table `items` */

DROP TABLE IF EXISTS `items`;

CREATE TABLE `items` (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(200) DEFAULT NULL,
  `item_cd` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `items` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
