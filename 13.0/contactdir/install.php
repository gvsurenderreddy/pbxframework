<?php
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed');}
global $amp_conf, $db;

// Sql needed to drive this module
//check for already installed
$testQ		= 'SELECT * FROM contactdir_server_types';
$check		= $db->getAll($testQ, DB_FETCHMODE_ASSOC);
//run these on new install only!(defined by select returning with an error)
if(DB::IsError($check)) {
	$sql[] = "CREATE TABLE if not exists contactdir_server_types (
		id int(6) NOT NULL auto_increment,
		server_type varchar(255) NOT NULL,
		PRIMARY KEY (id))";
	
	$sql[] = "CREATE TABLE if not exists contactdir_servers (
		id int(6) NOT NULL auto_increment,
		server_type int(6) NOT NULL,
		display_name varchar(64) NOT NULL,
		host varchar(255) NOT NULL,
		port varchar(8) NOT NULL,
		usessl tinyint(1) NOT NULL,
		PRIMARY KEY  (id))";
	
	$sql[] = "CREATE TABLE if not exists contactdir_list (
		`extension` varchar(10) default NULL,
	  `user_name` varchar(150) NOT NULL,
	  `server_id` varchar(15) NOT NULL,
	  `id` varchar(25) NOT NULL,
	  `first_name` varchar(255) default NULL,
	  `last_name` varchar(255) default NULL,
	  `email` varchar(150) default NULL,
	  `tel` varchar(25) default NULL,
	  `tel_spdial` varchar(3) default NULL,
	  `home` varchar(25) default NULL,
	  `home_spdial` varchar(3) default NULL,
	  `mobile` varchar(25) default NULL,
	  `mobile_spdial` varchar(3) default NULL,
	  `lastupdate` varchar(15) default NULL,
	  `mgcbtn` varchar(10) default '0',
  	`grammer` varchar(50) default NULL,
	  PRIMARY KEY  (`id`)
	)";
	
	$sql[] ="CREATE TABLE IF NOT EXISTS `contactdir_details` (
  `extension` varchar(10) default NULL,
  `type` varchar(20) default NULL,
  `val` varchar(100) default NULL
	)";
	
	$sql[] = "INSERT INTO contactdir_servers (id, server_type, display_name) values('1', '1', 'Local DB')";
	$sql[] = "INSERT INTO contactdir_server_types (id, server_type) values('1', 'Local DB')";
	$sql[] = "INSERT INTO contactdir_server_types (id, server_type) values('2', 'Exchange 2003')";
	$sql[] = "INSERT INTO contactdir_server_types (id, server_type) values('3', 'Exchange 2007')";
	
	foreach ($sql as $statement) {
		$check = $db->query($statement);
		if (DB::IsError($check)) {
			die_freepbx( "Can not execute $statement : " . $check->getMessage() .  "\n");
		}
	}
}

if (!is_dir($amp_conf['AMPWEBROOT'] . '/aastra/')) {
	mkdir($amp_conf['AMPWEBROOT'] . '/aastra/');
}
if (!file_exists($amp_conf['AMPWEBROOT'] . '/aastra/contactdir')) {
	symlink($amp_conf['AMPWEBROOT'] . '/admin/modules/contactdir/contactdir',$amp_conf['AMPWEBROOT'].'/aastra/contactdir');
}
