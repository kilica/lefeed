CREATE TABLE `{prefix}_{dirname}_entry` (
  `entry_id` int(11) unsigned NOT NULL  auto_increment,
  `uid` mediumint(8) unsigned NOT NULL,
  `category_id` mediumint(8) unsigned NOT NULL,
  `dirname` varchar(25) NOT NULL,
  `dataname` varchar(25) NOT NULL,
  `data_id` int(11) unsigned NOT NULL,
  `pubdate` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`entry_id`),
  KEY `category_id` (`category_id`, `pubdate`),
  KEY `module` (`dirname`, `dataname`, `pubdate`),
  KEY `mixed` (`category_id`, `dirname`, `dataname`, `pubdate`)
  ) ENGINE=MyISAM;

