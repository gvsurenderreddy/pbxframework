<?php
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed');}
//	License for all code of this FreePBX module can be found in the license file inside the module directory
//	Copyright 2013 Schmooze Com Inc.
//
function contactdir_get_all_servers()
{
	global $db;
	
	//$sql = "select *, cst.id as server_type_id, cs.id as server_id from contactdir_servers as cs, contactdir_server_types as cst where cs.server_type = cst.id";
	$sql = 'SELECT * FROM contactdir_servers';
	$results = $db->getAll($sql, DB_FETCHMODE_ASSOC);

	if (DB::IsError($results)) 
	{
		die_freepbx($result->getDebugInfo());
	}

	return $results;
}

function contactdir_add_server($server_type, $name, $host, $port, $ssl)
{
	global $db;

	if ($ssl)
	{
		$ssl = 1;
	}

	$sql = "insert into contactdir_servers (server_type, display_name, host, port, usessl) values('$server_type', '$name', '$host', '$port', '$ssl')";
	sql($sql);
}

function contactdir_update_server($id, $server_type, $name, $host, $port, $ssl)
{
	global $db;
	
	if ($ssl)
	{
		$ssl = 1;
	}
	else
	{
		$ssl = '';
	}

	$sql = "update contactdir_servers set server_type = '$server_type', display_name = '$name', host = '$host', port = '$port', usessl = '$ssl' where id = $id";
	sql($sql);
}

function contactdir_delete_server($id)
{
	global $db;

	$sql = "delete from contactdir_servers where id = $id";
	sql($sql);
}

function contactdir_get_all_server_types()
{
	global $db;
	
	$sql = "select * from contactdir_server_types";

	$results = $db->getAll($sql, DB_FETCHMODE_ASSOC);

	if (DB::IsError($results)) 
	{
		die_freepbx($result->getDebugInfo());
	}

	return $results;
}
