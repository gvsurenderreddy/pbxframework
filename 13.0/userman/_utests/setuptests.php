<?php

global $amp_conf, $db;
if (!@include_once(getenv('FREEPBX_CONF') ? getenv('FREEPBX_CONF') : '/etc/freepbx.conf')) {
	include_once('/etc/asterisk/freepbx.conf');
}
restore_error_handler();
error_reporting(-1);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
