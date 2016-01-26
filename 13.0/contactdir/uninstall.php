<?php
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed');}
global $amp_conf;
$sql[] = "DROP TABLE contactdir_server_types";
$sql[] = "DROP TABLE contactdir_servers";
$sql[] = "DROP TABLE contactdir_list";
$sql[] = "DROP TABLE contactdir_details";

foreach ($sql as $statement)
{
	$check = $db->query($statement);

	if (DB::IsError($check)) 
	{
		die_freepbx( "Can not execute $statement : " . $check->getMessage() .  "\n");
	}
}


if (file_exists($amp_conf['AMPWEBROOT'] . '/recordings/modules/contactdir.module')) {
	unlink($amp_conf['AMPWEBROOT'] . '/recordings/modules/contactdir.module');
}
if (file_exists($amp_conf['AMPWEBROOT'] . '/aastra/contactdir')) {
	unlink($amp_conf['AMPWEBROOT'] . '/aastra/contactdir');
}
?>
