<?php
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }
//	License for all code of this FreePBX module can be found in the license file inside the module directory
//	Copyright 2013 Schmooze Com Inc.
//
 /* $Id$ */

// The destinations this module provides
// returns a associative arrays with keys 'destination' and 'description'
function ivr_destinations() {
	global $module_page;

	//get the list of IVR's
	$results = ivr_get_details();

	// return an associative array with destination and description
	if (isset($results)) {
		foreach($results as $result){
			$name = $result['name'] ? $result['name'] : 'IVR ' . $result['id'];
			$extens[] = array('destination' => 'ivr-'.$result['id'].',s,1', 'description' => $name);
		}
	}
	if (isset($extens)) {
		return $extens;
	} else {
		return null;
	}

}

//dialplan generator
function ivr_get_config($engine) {
	global $ext;

	switch($engine) {
		case "asterisk":
			$ddial_contexts = array();
			$ivrlist = ivr_get_details();
			if(!is_array($ivrlist)) {
				break;
			}

			if (function_exists('queues_list')) {
				//draw a list of ivrs included by any queues
				$queues = queues_list(true);
				$qivr = array();
				foreach ($queues as $q) {
					$thisq = queues_get($q[0]);
					if ($thisq['context'] && strpos($thisq['context'], 'ivr-') === 0) {
						$qivr[] = str_replace('ivr-', '', $thisq['context']);
					}
				}
			}

			foreach($ivrlist as $ivr) {
				$c = 'ivr-' . $ivr['id'];
				$ivr = ivr_get_details($ivr['id']);
				$ext->addSectionComment($c, $ivr['name'] ? $ivr['name'] : 'IVR ' . $ivr['id']);

				if ($ivr['directdial']) {
					if ($ivr['directdial'] == 'ext-local') {
						$ext->addInclude($c, 'from-did-direct-ivr'); //generated in core module
					} else {
						//generated by directory
						$ext->addInclude($c, 'from-ivr-directory-' . $ivr['directdial']);
						$directdial_contexts[$ivr['directdial']] = $ivr['directdial'];
					}
				}

				//set variables for loops when used
				if ($ivr['timeout_loops'] != 'disabled' && $ivr['timeout_loops'] > 0) {
					$ext->add($c, 's', '', new ext_setvar('TIMEOUT_LOOPCOUNT', 0));
				}
				if ($ivr['invalid_loops'] != 'disabled' && $ivr['invalid_loops'] > 0) {
					$ext->add($c, 's', '', new ext_setvar('INVALID_LOOPCOUNT', 0));
				}

				$ext->add($c, 's', '', new ext_setvar('_IVR_CONTEXT_${CONTEXT}', '${IVR_CONTEXT}'));
				$ext->add($c, 's', '', new ext_setvar('_IVR_CONTEXT', '${CONTEXT}'));
				if ($ivr['retvm']) {
					$ext->add($c, 's', '', new ext_setvar('__IVR_RETVM', 'RETURN'));
				} else {
					//TODO: do we need to set anything at all?
					$ext->add($c, 's', '', new ext_setvar('__IVR_RETVM', ''));
				}

				$ext->add($c, 's', '', new ext_gotoif('$["${CHANNEL(state)}" = "Up"]','skip'));
				$ext->add($c, 's', '', new ext_answer(''));
				$ext->add($c, 's', '', new ext_wait('1'));

				$ivr_announcement = recordings_get_file($ivr['announcement']);
				$ext->add($c, 's', 'skip', new ext_set('IVR_MSG', $ivr_announcement));

				$ext->add($c, 's', 'start', new ext_digittimeout(3));
				//$ext->add($ivr_id, 's', '', new ext_responsetimeout($ivr['timeout_time']));

				$ext->add($c, 's', '', new ext_execif('$["${IVR_MSG}" != ""]','Background','${IVR_MSG}'));
				$ext->add($c, 's', '', new ext_waitexten($ivr['timeout_time']));


				// Actually add the IVR entires now
				$entries = ivr_get_entries($ivr['id']);

				if ($entries) {
					foreach($entries as $e) {
						//dont set a t or i if there already defined above
						if ($e['selection'] == 't' && $ivr['timeout_loops'] != 'disabled') {
						 	continue;
						}
						if ($e['selection'] == 'i' && $ivr['invalid_loops'] != 'disabled') {
						 	continue;
						}

						//only display these two lines if the ivr is included in any queues
						if (function_exists('queues_list') && in_array($ivr['id'], $qivr)) {
							$ext->add($c, $e['selection'],'', new ext_macro('blkvm-clr'));
							$ext->add($c, $e['selection'], '', new ext_setvar('__NODEST', ''));
						}

						if ($e['ivr_ret']) {
							$ext->add($c, $e['selection'], '',
								new ext_gotoif('$["x${IVR_CONTEXT_${CONTEXT}}" = "x"]',
									$e['dest'] . ':${IVR_CONTEXT_${CONTEXT}},return,1'));
						} else {
							$ext->add($c, $e['selection'],'ivrsel-' . $e['selection'], new ext_goto($e['dest']));
						}
					}
				}

				// add invalid destination if required
				if ($ivr['invalid_loops'] != 'disabled') {
					if ($ivr['invalid_loops'] > 0) {
						$ext->add($c, 'i', '', new ext_set('INVALID_LOOPCOUNT', '$[${INVALID_LOOPCOUNT}+1]'));
						$ext->add($c, 'i', '',	new ext_gotoif('$[${INVALID_LOOPCOUNT} > ' . $ivr['invalid_loops'] . ']','final'));
						switch ($ivr['invalid_retry_recording']) {
							case 'default':
								$invalid_annoucement = 'no-valid-responce-pls-try-again';
								break;
							case '':
								$invalid_annoucement = '';
								break;
							default:
								$invalid_annoucement = recordings_get_file($ivr['invalid_retry_recording']);
								break;
						}

						if ($ivr['invalid_append_announce'] || $invalid_annoucement == '') {
							$invalid_annoucement .= '&' . $ivr_announcement;
						}
						$ext->add($c, 'i', '', new ext_set('IVR_MSG', trim($invalid_annoucement, '&')));
						$ext->add($c, 'i', '', new ext_goto('s,start'));
					}

					$label = 'final';
					switch ($ivr['invalid_recording']) {
						case 'default':
							$ext->add($c, 'i', $label, new ext_playback('no-valid-responce-transfering'));
							$label ='';
							break;
						case '':
							break;
						default:
							$ext->add($c, 'i', $label,
								new ext_playback(recordings_get_file($ivr['invalid_recording'])));
							$label = '';
							break;
					}
					if (!empty($ivr['invalid_ivr_ret'])) {
						$ext->add($c, 'i', $label,
							new ext_gotoif('$["x${IVR_CONTEXT_${CONTEXT}}" = "x"]',
								$ivr['invalid_destination'] . ':${IVR_CONTEXT_${CONTEXT}},return,1'));
					} else {
						$ext->add($c, 'i', $label, new ext_goto($ivr['invalid_destination']));
					}
				} else {
					// If no invalid destination provided we need to do something
					$ext->add($c, 'i', '', new ext_playback('sorry-youre-having-problems'));
					$ext->add($c, 'i', '', new ext_goto('1','hang'));
				}

				// Apply timeout destination if required
				if ($ivr['timeout_loops'] != 'disabled') {
					if ($ivr['timeout_loops'] > 0) {
						$ext->add($c, 't', '', new ext_set('TIMEOUT_LOOPCOUNT', '$[${TIMEOUT_LOOPCOUNT}+1]'));
						$ext->add($c, 't', '', new ext_gotoif('$[${TIMEOUT_LOOPCOUNT} > ' . $ivr['timeout_loops'] . ']','final'));

						switch ($ivr['timeout_retry_recording']) {
							case 'default':
								$timeout_annoucement = 'no-valid-responce-pls-try-again';
								break;
							case '':
								$timeout_annoucement = '';
								break;
							default:
								$timeout_annoucement = recordings_get_file($ivr['timeout_retry_recording']);
								break;
						}

						if ($ivr['timeout_append_announce'] || $timeout_annoucement == '') {
							$timeout_annoucement .= '&' . $ivr_announcement;
						}
						$ext->add($c, 't', '', new ext_set('IVR_MSG', trim($timeout_annoucement, '&')));
						$ext->add($c, 't', '', new ext_goto('s,start'));
					}

					$label = 'final';
					switch ($ivr['timeout_recording']) {
						case 'default':
							$ext->add($c, 't', $label, new ext_playback('no-valid-responce-transfering'));
							$label = '';
							break;
						case '':
							break;
						default:
							$ext->add($c, 't', $label,
								new ext_playback(recordings_get_file($ivr['timeout_recording'])));
							$label = '';
							break;
					}
					if (!empty($ivr['timeout_ivr_ret'])) {
						$ext->add($c, 't', $label,
							new ext_gotoif('$["x${IVR_CONTEXT_${CONTEXT}}" = "x"]',
								$ivr['timeout_destination'] . ':${IVR_CONTEXT_${CONTEXT}},return,1'));
					} else {
						$ext->add($c, 't', $label, new ext_goto($ivr['timeout_destination']));
					}
				} else {
					// If no invalid destination provided we need to do something
					$ext->add($c, 't', '', new ext_playback('sorry-youre-having-problems'));
					$ext->add($c, 't', '', new ext_goto('1','hang'));
				}

				// these need to be reset or inheritance problems makes them go away in some conditions
				//and infinite inheritance creates other problems
				$ext->add($c, 'return', '', new ext_setvar('_IVR_CONTEXT', '${CONTEXT}'));
				$ext->add($c, 'return', '', new ext_setvar('_IVR_CONTEXT_${CONTEXT}', '${IVR_CONTEXT_${CONTEXT}}'));
				$ext->add($c, 'return', '', new ext_set('IVR_MSG', $ivr_announcement));
				$ext->add($c, 'return', '', new ext_goto('s,start'));

				//h extension
				$ext->add($c, 'h', '', new ext_hangup(''));
				$ext->add($c, 'hang', '', new ext_playback('vm-goodbye'));
				$ext->add($c, 'hang', '', new ext_hangup(''));
			}


			//generate from-ivr-directory contexts for direct dialing a directory entire
			if (!empty($directdial_contexts)) {
				foreach($directdial_contexts as $dir_id) {
					$c = 'from-ivr-directory-' . $dir_id;
					$entries = function_exists('directory_get_dir_entries') ? directory_get_dir_entries($dir_id) : array();
					foreach ($entries as $dstring) {
						$exten = $dstring['dial'] == '' ? $dstring['foreign_id'] : $dstring['dial'];
						if ($exten == '' || $exten == 'custom') {
							continue;
						}
						$ext->add($c, $exten, '', new ext_macro('blkvm-clr'));
						$ext->add($c, $exten, '', new ext_setvar('__NODEST', ''));
						$ext->add($c, $exten, '', new ext_goto('1', $exten, 'from-internal'));
					}
				}
			}
		break;
	}
}

//replaces ivr_list(), returns all details of any ivr
function ivr_get_details($id = '') {
	return FreePBX::Ivr()->getDetails($id);
}

//get all ivr entires
function ivr_get_entries($id) {
	global $db;

	//+0 to convert string to an integer
	$sql = "SELECT * FROM ivr_entries WHERE ivr_id = ? ORDER BY selection + 0";
	$res = $db->getAll($sql, array($id), DB_FETCHMODE_ASSOC);
	if ($db->IsError($res)) {
		die_freepbx($res->getDebugInfo());
	}
	return $res;
}


//draw ivr options page
function ivr_configpageload() {
	global $currentcomponent, $display;
	return true;
}

function ivr_configpageinit($pagename) {
	global $currentcomponent;
	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

	if($pagename == 'ivr'){
		$currentcomponent->addprocessfunc('ivr_configprocess');

		//dont show page if there is no action set
		if ($action && $action != 'delete' || $id) {
			$currentcomponent->addguifunc('ivr_configpageload');
		}

    return true;
	}
}

//prosses received arguments
function ivr_configprocess(){
	if (isset($_REQUEST['display']) && $_REQUEST['display'] == 'ivr'){
		global $db;
		//get variables
		$get_var = array('id', 'name', 'description', 'announcement',
						'directdial', 'invalid_loops', 'invalid_retry_recording',
						'invalid_destination', 'invalid_recording',
						'retvm', 'timeout_time', 'timeout_recording',
						'timeout_retry_recording', 'timeout_destination', 'timeout_loops',
						'timeout_append_announce', 'invalid_append_announce',
						'timeout_ivr_ret', 'invalid_ivr_ret');
		foreach($get_var as $var){
			$vars[$var] = isset($_REQUEST[$var]) 	? $_REQUEST[$var]		: '';
		}
		$vars['timeout_append_announce'] = empty($vars['timeout_append_announce']) ? '0' : '1';
		$vars['invalid_append_announce'] = empty($vars['invalid_append_announce']) ? '0' : '1';
		$vars['timeout_ivr_ret'] = empty($vars['timeout_ivr_ret']) ? '0' : '1';
		$vars['invalid_ivr_ret'] = empty($vars['invalid_ivr_ret']) ? '0' : '1';

		$action		= isset($_REQUEST['action'])	? $_REQUEST['action']	: '';
		$entries	= isset($_REQUEST['entries'])	? $_REQUEST['entries']	: '';

		switch ($action) {
			case 'save':
				//get real dest
				$_REQUEST['id'] = $vars['id'] = ivr_save_details($vars);
        $_REQUEST['action'] = 'edit';
				ivr_save_entries($vars['id'], $entries);
				needreload();
				//$_REQUEST['action'] = 'edit';
				$this_dest = ivr_getdest($vars['id']);
				fwmsg::set_dest($this_dest[0]);
				//redirect_standard_continue('id');
			break;
			case 'delete':
				ivr_delete($vars['id']);
        isset($_REQUEST['id'])?$_REQUEST['id'] = null:'';
        isset($_REQUEST['action'])?$_REQUEST['action'] = null:'';
				needreload();
				//redirect_standard_continue();
			break;
		}
	}
}

//save ivr settings
function ivr_save_details($vals){
	global $db, $amp_conf;

	foreach($vals as $key => $value) {
		$vals[$key] = $db->escapeSimple($value);
	}

	if ($vals['id']) {
		$start = "REPLACE INTO `ivr_details` (";
	} else {
		unset($vals['id']);
		$start = "INSERT INTO `ivr_details` (";
	}

	$end = ") VALUES (";
	foreach ($vals as $k => $v) {
		$start .= "$k, ";
		$end .= ":$k, ";
	}

	$sql = substr($start, 0, -2).substr($end, 0, -2).")";
	$foo = $db->query($sql, $vals);
	if($db->IsError($foo)) {
		die_freepbx(print_r($vals,true).' '.$foo->getDebugInfo());
	}
	// Was this a new one?
	if (!isset($vals['id'])) {
		$sql = ( ($amp_conf["AMPDBENGINE"]=="sqlite3") ? 'SELECT last_insert_rowid()' : 'SELECT LAST_INSERT_ID()');
		$id = $db->getOne($sql);
		if ($db->IsError($id)){
			die_freepbx($id->getDebugInfo());
		}
		$vals['id'] = $id;
	}

	return $vals['id'];
}

//save ivr entires
function ivr_save_entries($id, $entries){
	global $db;
	$id = $db->escapeSimple($id);
	sql('DELETE FROM ivr_entries WHERE ivr_id = "' . $id . '"');
	if ($entries) {
		$entries['ivr_ret'] = array_values($entries['ivr_ret']);
		for ($i = 0; $i < count($entries['ext']); $i++) {
			//make sure there is an extension & goto set - otherwise SKIP IT
			if (trim($entries['ext'][$i]) != '' && $entries['goto'][$i]) {
				$d[] = array(
							'ivr_id'	=> $id,
							'selection' 	=> $entries['ext'][$i],
							'dest'		=> $entries['goto'][$i],
							'ivr_ret'	=> (isset($entries['ivr_ret'][$i]) ? $entries['ivr_ret'][$i] : '')
						);
			}

		}
		$sql = $db->prepare('INSERT INTO ivr_entries VALUES (?, ?, ?, ?)');
		$res = $db->executeMultiple($sql, $d);
		if ($db->IsError($res)){
			die_freepbx($res->getDebugInfo());
		}
	}

	return true;
}

//draw uvr entires table header
function ivr_draw_entries_table_header_ivr() {
	return  array(_('Ext'), _('Destination'), fpbx_label(_('Return'), _('Return to IVR')), _('Delete'));
}

//draw actualy entires
function ivr_draw_entries($id){
	$headers		= mod_func_iterator('draw_entries_table_header_ivr');
	$ivr_entries	= ivr_get_entries($id);

	if ($ivr_entries) {
		foreach ($ivr_entries as $k => $e) {
			$entries[$k]= $e;
			$array = array('id' => $id, 'ext' => $e['selection']);
			$entries[$k]['hooks'] = mod_func_iterator('draw_entries_ivr', $array);
		}
	}

	$entries['blank'] = array('selection' => '', 'dest' => '', 'ivr_ret' => '');
	//assign to a vatriable first so that it can be passed by reference
	$array = array('id' => '', 'ext' => '');
	$entries['blank']['hooks'] = mod_func_iterator('draw_entries_ivr', $array);

	return load_view(dirname(__FILE__) . '/views/entries.php',
				array(
					'headers'	=> $headers,
					'entries'	=>  $entries
				)
			);

}

//delete an ivr + entires
function ivr_delete($id) {
	global $db;
	sql('DELETE FROM ivr_details WHERE id = "' . $db->escapeSimple($id) . '"');
	sql('DELETE FROM ivr_entries WHERE ivr_id = "' . $db->escapeSimple($id) . '"');
}
//----------------------------------------------------------------------------
// Dynamic Destination Registry and Recordings Registry Functions
function ivr_check_destinations($dest=true) {
	global $active_modules;

	$destlist = array();
	if (is_array($dest) && empty($dest)) {
		return $destlist;
	}
	$sql = "SELECT dest, name, selection, a.id id FROM ivr_details a INNER JOIN ivr_entries d ON a.id = d.ivr_id  ";
	if ($dest !== true) {
		$sql .= "WHERE dest in ('".implode("','",$dest)."')";
	}
	$sql .= "ORDER BY name";
	$results = sql($sql,"getAll",DB_FETCHMODE_ASSOC);

	foreach ($results as $result) {
		$thisdest = $result['dest'];
		$thisid   = $result['id'];
		$name = $result['name'] ? $result['name'] : 'IVR ' . $thisid;
		$destlist[] = array(
			'dest' => $thisdest,
			'description' => sprintf(_("IVR: %s / Option: %s"),$name,$result['selection']),
			'edit_url' => 'config.php?display=ivr&action=edit&id='.urlencode($thisid),
		);
	}
	return $destlist;
}



function ivr_change_destination($old_dest, $new_dest) {
	global $db;
 	$sql = "UPDATE ivr_entires SET dest = '$new_dest' WHERE dest = '$old_dest'";
 	$db->query($sql);

}


function ivr_getdest($exten) {
	return array('ivr-'.$exten.',s,1');
}

function ivr_getdestinfo($dest) {
	global $active_modules;

	if (substr(trim($dest),0,4) == 'ivr-') {
		$exten = explode(',',$dest);
		$exten = substr($exten[0],4);

		$thisexten = ivr_get_details($exten);
		if (empty($thisexten)) {
			return array();
		} else {
			//$type = isset($active_modules['ivr']['type'])?$active_modules['ivr']['type']:'setup';
			return array('description' => sprintf(_("IVR: %s"), ($thisexten['name'] ? $thisexten['name'] : $thisexten['id'])),
			             'edit_url' => 'config.php?display=ivr&action=edit&id='.urlencode($exten),
								  );
		}
	} else {
		return false;
	}
}

function ivr_recordings_usage($recording_id) {
	global $active_modules;

	$sql = "SELECT `id`, `name` FROM `ivr_details` WHERE `announcement` = '$recording_id' OR `invalid_retry_recording` = '$recording_id' OR `invalid_recording` = '$recording_id' OR `timeout_recording` = '$recording_id' OR `timeout_retry_recording` = '$recording_id'";
	$results = sql($sql, "getAll",DB_FETCHMODE_ASSOC);
	if (empty($results)) {
		return array();
	} else {
		foreach ($results as $result) {
			$usage_arr[] = array(
				'url_query' => 'config.php?display=ivr&action=edit&id='.urlencode($result['id']),
				'description' => sprintf(_("IVR: %s"), ($result['name'] ? $result['name'] : $result['id'])),
			);
		}
		return $usage_arr;
	}
}

?>