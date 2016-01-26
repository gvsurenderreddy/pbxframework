<?php
//	License for all code of this FreePBX module can be found in the license file inside the module directory
//	Copyright 2013 Schmooze Com Inc.
	
 /* 
 	* set application to call like: contactdir.php?user=$$SIPUSRENAME$$
 	* USAGE:
 	* This app works as follows:
 	* Step 1: check to see if we have the users prefered input method saved. 
 	* 			 Promt if we dont. There are two options:
 	* 			1. One-Touch: the user uses the telephone keypad to spell out the
 	* 									contacts name. Whilst the display show NUMBERS, the user
 	* 									can 'type' naturaly and press each button ONCE for ANY 
 	* 									desired letter. I.e. pressing 2 can mean 'a' or 'b' or 'c'
 	* 			2. T9: this is the method many users are accustomed to when sending
 	* 							sms/email from mobile phobnes that dont have a qwerty keyboard 
 	*	Step 2: Promt user for seach term
 	* Step 3: Show the user any matched contacts, or display an error if none are
 	* 				found
 	* Step 4: Allow the user to chose (dial) from any of the contacts details;
 	* 				return an error if none found
 	* 
 	*/

// PHP customization for includes and warnings
$os = strtolower(PHP_OS);
if(strpos($os, "win") === false) ini_set('include_path',ini_get('include_path').':include:../include:../../../../aastra/include');
else ini_set('include_path',ini_get('include_path').';include;../include:../../../../aastra/include');
//error_reporting(E_ERROR | E_PARSE);

//includes
require_once('AastraIPPhoneInputScreen.class.php'); 
require_once('AastraIPPhoneTextMenu.class.php');
require_once('AastraIPPhoneTextScreen.class.php');
require_once('AastraIPPhoneFormattedTextScreen.class.php');
require_once('AastraCommon.php'); //nedded for $XML_SERVER_PATH
require_once('DB.php');

//database parms
$config=Aastra_readINIfile('/etc/amportal.conf','#','=');
$engine = $config['']['ampdbengine'];
$db_host = $config['']['ampdbhost'];
$db_database = $config['']['ampdbname'];
$db_username = $config['']['ampdbuser'];
$db_password = $config['']['ampdbpass'];
$db_url = $engine."://".$db_username.":".$db_password."@".$db_host."/".$db_database;
$db = DB::connect($db_url);

//get arguments
$user = $_GET['user'];
$searchmethod=isset($_GET['searchmethod'])?$_GET['searchmethod']:null; //search method : onetouch or t9
$search=isset($_GET['search'])?$_GET['search']:null; //name of number to search for
$searchterm=isset($_GET['searchterm'])?$_GET['searchterm']:null; // "" ""
$contact=isset($_GET['contact'])?$_GET['contact']:null; //id of a specific contact

//declare classes
$input = new AastraIPPhoneInputScreen();
$menu = new AastraIPPhoneTextMenu();
$text = new AastraIPPhoneTextScreen();
$screen = new AastraIPPhoneFormattedTextScreen();

//if no $searchmethod is set, check for users perferd input method
if (!$searchmethod){
	$sql = 'SELECT val from contactdir_details where extension = ? and type =?';
	$searchmethod=$db->getRow($sql, array($user, 'searchmethod'));
	if ($searchmethod){$searchmethod = implode(",", $searchmethod);}
}

//step 1&2: ensure that the search term hasnt been entered yet, and promt for it. If
//we dont have a search method either, return default to prompt for method
if (!$search){
	switch ($searchmethod){
		case 't9':
			$sql = 'DELETE FROM contactdir_details WHERE extension = ? and type = ?';
			$db->query($sql, array($user, 'searchmethod'));
			$sql = 'INSERT INTO contactdir_details (extension, type, val) VALUES (?, ?, ?)';
			$db->query($sql, array($user, 'searchmethod', 't9'));
			$input->setTitle('Contact Directory'); 
			$input->setPrompt('Enter first three chars.'); 
			$input->setType('string'); 
			$input->setParameter('search');
			$input->setDefault($searchterm);  
			$input->setURL($XML_SERVER_PATH.'contactdir.php?searchmethod=t9'); 
			$input->setDestroyOnExit(); 
			$input->addSoftkey('1', 'One-Touch', $XML_SERVER_PATH.'contactdir.php?user='.$user.'&searchmethod=onetouch'); 
			$input->addSoftkey('5', 'Back', $XML_SERVER_PATH.'contactdir.php');
			$input->addSoftkey('4', 'Search', 'SoftKey:Submit');
			$input->addSoftkey('6', 'BKSpace', 'SoftKey:BackSpace');
			$input->addSoftkey('3', 'Exit', 'SoftKey:Exit'); 
			$input->output();
		break;
		case 'onetouch':
			$sql = 'DELETE FROM contactdir_details WHERE extension = ? and type = ?';
			$db->query($sql, array($user, 'searchmethod'));
			$sql = 'INSERT INTO contactdir_details (extension, type, val) VALUES (?, ?, ?)';
			$db->query($sql, array($user, 'searchmethod', 'onetouch'));
			$input->setTitle('Contact Directory'); 
			$input->setPrompt('Enter first three chars.');   
			$input->setType('number'); 
			$input->setParameter('search');
			$input->setDefault($searchterm); 
			$input->setURL($XML_SERVER_PATH.'contactdir.php?user='.$user.'&searchmethod=onetouch');  
			$input->addSoftkey('1', 'T9', $XML_SERVER_PATH.'contactdir.php?user='.$user.'&searchmethod=t9'); 
			//$input->addSoftkey('2', 'History', $XML_SERVER_PATH.'contactdir.php?user='.$user.'&searchmethod=t9&history=show');
			$input->addSoftkey('5', 'Back', $XML_SERVER_PATH.'contactdir.php?user='.$user);
			$input->addSoftkey('4', 'Search', 'SoftKey:Submit');
			$input->addSoftkey('6', 'BKSpace', 'SoftKey:BackSpace');
			$input->addSoftkey('3', 'Exit', 'SoftKey:Exit'); 
			$input->output();
		break;
		case 'help':
			$text->setTitle('Contact Directory');
			$text->setText('To search for contacts, use one of the following methods: One-touch: allows you to press each key once for the desired letter (i.e. to spell boy: \'2\' for \'b\', \'6\' for \'o\', \'9\' for \'y\'). T9: hit each number untill the desired letter appears on the screen (i.e. to spell boy: \'22\' for \'b\', \'666\' for \'o\', \'999\' for \'y\').'); 
			$text->setDestroyOnExit();
			$text->addSoftkey('3', 'Back', $XML_SERVER_PATH.'contactdir.php?user='.$user);
			$text->output();
		break;
		default: //step 1: promt for search method
			$text->setTitle('Contact Directory'); 
			$text->setText('Chose input method to use for search:'); 
			$text->setDestroyOnExit(); 
			$text->addSoftkey('1', 'One-Touch', $XML_SERVER_PATH.'contactdir.php?user='.$user.'&searchmethod=onetouch'); 
			$text->addSoftkey('2', 'T9', $XML_SERVER_PATH.'contactdir.php?user='.$user.'&searchmethod=t9');
			$text->addSoftkey('3', 'Help', $XML_SERVER_PATH.'contactdir.php?user='.$user.'&searchmethod=help');
			$text->addSoftkey('6', 'Exit', 'SoftKey:Exit'); 
			$text->output();
		break;
	}
//step3: if we do have a search term, see if there are any contacts for said term
} elseif(!$contact){
	$contacts=search($search);
	if ($contacts){
		$menu->setTitle('Please select a contact'); 
		$menu->setDestroyOnExit(); 
		foreach ($contacts as $contact=>$val){
			$menu->addEntry($val['first_name'].' '.$val['last_name'],	$XML_SERVER_PATH.'contactdir.php?user='.$user.'&contact='.$val['id'].'&searchmethod='.$searchmethod.'&search='.$search);	
		}
		$menu->natsortByName();
		$menu->addSoftkey('3', 'Exit', 'SoftKey:Exit'); 
		$menu->addSoftkey('5', 'Back', $XML_SERVER_PATH.'contactdir.php?user='.$user.'&searchmethod='.$searchmethod.'&searchterm='.$search); 
		$menu->addSoftkey('6', 'Select', 'SoftKey:Select'); 
		$menu->output();
	} else { //if no contacts are found, return error and prompt again
	/*
		$text->setTitle('Error');
		$text->setText('No Matches Found!'); 
		$text->setDestroyOnExit();
		$text->setRefresh('3',$XML_SERVER_PATH.'contactdir.php?user='.$user.'&searchmethod='.$searchmethod.'&searchterm='.$search);
		$text->addSoftKey('6','','SoftKey:Exit'); //hide default buttons
		$text->output();
	*/
		$screen->setdestroyOnExit();
		$screen->setBeep();
		$screen->addLine('');
		$screen->addLine('');
		$screen->addLine('');
		$screen->addLine('No Matches Found!','double','center');
		$screen->setRefresh('2',$XML_SERVER_PATH.'contactdir.php?user='.$user.'&searchmethod='.$searchmethod.'&searchterm='.$search);
		$screen->addSoftKey('6','','SoftKey:Exit'); 
		$screen->output();
	}
//step 4: otherwise, we have a valid contacts, if they have details, show them 
} else {
	$contact=getContact($contact); 
	if ($contact['tel']||$contact['home']||$contact['mobile']){
		logit('$contact',print_r($contact,1));
		$menu->setTitleWrap();
		$menu->setTitle('Please select a detail for '.$contact['first_name'].' '.$contact['last_name']); 
		$menu->setDestroyOnExit();
		if($contact['tel']){$menu->addEntry($contact['tel'],	null, null, '1', $contact['tel']);};
		if($contact['home']){$menu->addEntry($contact['home'],	null, null, '2', $contact['home']);};
		if($contact['mobile']){$menu->addEntry($contact['mobile'],	null, null, '3', $contact['mobile']);};
		$menu->natsortByName(); 
		$menu->addSoftkey('5', 'Back', $XML_SERVER_PATH.'contactdir.php?user='.$user.'&searchmethod='.$searchmethod.'&search='.$search); 
		$menu->addSoftkey('6', 'Dial', 'SoftKey:Dial2');
		$menu->addSoftkey('3', 'Exit', 'SoftKey:Exit'); 
		$menu->addIcon('1', '000000664E5A4E6600000000'); 
		$menu->addIcon('2', '000000103E7A3E1000000000'); 
		$menu->addIcon('3', '000000007E565AFE00000000');
		$menu->output(); 
	} else { //if they dont have details, notify and promt for new search
	/*	$text->setTitle('Error');
		$text->setText('No Details Found!'); 
		$text->setDestroyOnExit();
		$text->setRefresh('3',$XML_SERVER_PATH.'contactdir.php?user='.$user.'&searchmethod='.$searchmethod.'&search='.$search);
		$text->output();
	*/
		$screen->setdestroyOnExit();
		$screen->setBeep();
		$screen->addLine('');
		$screen->addLine('No Details Found','double','center');
		$screen->addLine('- for -','','center');
		$screen->addLine($contact['first_name'].' '.$contact['last_name'],'','center');
		$screen->setRefresh('2',$XML_SERVER_PATH.'contactdir.php?user='.$user.'&searchmethod='.$searchmethod.'&search='.$search);
		$screen->addSoftKey('6','','SoftKey:Exit'); 
		$screen->output();    
	}
}

function search($term){
	global $user;
	global $db;
	//should we search for local contacts as well?
	$sql='SELECT val FROM contactdir_details WHERE extension = ? AND type = \'includelocal\'';
	$res = $db->getRow($sql, array($user), DB_FETCHMODE_ASSOC);
	$includelocal = $res['val'];
	//if the user used the one-touch method, convert numbers to text
	if (is_numeric($term)){
		$regex=true;
		$term=str_replace('2','[abc]',$term);
		$term=str_replace('3','[def]',$term);
		$term=str_replace('4','[ghi]',$term);
		$term=str_replace('5','[jkl]',$term);
		$term=str_replace('6','[mno]',$term);
		$term=str_replace('7','[pqrs]',$term);
		$term=str_replace('8','[tuv]',$term);
		$term=str_replace('9','[wxyz]',$term);
		$term=str_replace('*','',$term);
		$term=str_replace('#','',$term);
		$term=str_replace(' ','',$term);
	}
	if($regex){
		$term='^'.$term;
		$sql='SELECT last_name, first_name, id FROM contactdir_list WHERE first_name REGEXP ? or last_name REGEXP ? and extension = ?';
		$contacts=$db->getAll($sql, array($term, $term, $user), DB_FETCHMODE_ASSOC);
		if ($includelocal == '1'){//include local contacs as well
			$sql='SELECT name, extension FROM users WHERE name REGEXP ?';
			$contacts2=$db->getAll($sql, array($term), DB_FETCHMODE_ASSOC);
			foreach ($contacts2 as $contact){
				$name=explode(' ', $contact['name'],2);
				$contacts[]=array('last_name' => $name['1'], 'first_name' => $name['0'], 'id' => 'local'.$contact['extension']);
			}
		}
	}else{
		$term=$db->escapeSimple($term).'%';
		$sql='SELECT last_name, first_name, id FROM contactdir_list WHERE first_name LIKE ? or last_name LIKE ? and extension = ?';
		$contacts=$db->getAll($sql, array($term, $term, $user), DB_FETCHMODE_ASSOC);
		if ($includelocal == '1'){//include local contacs as well
			$sql='SELECT name, extension FROM users WHERE name LIKE ?';
			$contacts2=$db->getAll($sql, array($term), DB_FETCHMODE_ASSOC);
			foreach ($contacts2 as $contact){
				$name=explode(' ', $contact['name'],2);
				$contacts[]=array('last_name' => $name['1'], 'first_name' => $name['0'], 'id' => 'local'.$contact['extension']);
			}
		}
	}
	return $contacts;
}

function getContact($id){
	global $db;
	if (substr($id, 0, 5) == 'local'){
		$id=substr($id, 5);//remove 'local' from id string
		$sql = 'SELECT name, extension FROM users WHERE extension = ?';
		$contact = $db->getRow($sql, array($id), DB_FETCHMODE_ASSOC);
		$name=explode(' ',$contact['name'],2);
		$contact['last_name']=$name['1'];
		$contact['first_name']=$name['0'];
		$contact['tel']=$contact['extension'];
	}else{
		$sql = 'SELECT last_name, first_name, tel, home, mobile FROM contactdir_list WHERE id = ?';
		$contact = $db->getRow($sql, array($id), DB_FETCHMODE_ASSOC);
	}
	return $contact;
}


//debuging function
function logit($disc,$msg)
{
	$logit=false;//global turn debug on or off
	if ($logit){
	$myFile = "/tmp/phonebookdir-debug";
	$fh = fopen($myFile, 'a') or die("can't open file");
	$msg=date("Y-M-d H:i:s").' '.$disc.': '.$msg."\n";
	fwrite($fh, $msg);
	fclose($fh);
	}
}
?>