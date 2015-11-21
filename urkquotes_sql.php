CREATE TABLE quotes (
	`id` int(10) unsigned NOT NULL auto_increment,
	`rating` varchar(250) NOT NULL,
	`quote` text NOT NULL,
	`status` varchar(250) NOT NULL,
	`datestamp` varchar(250) NOT NULL,
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;
