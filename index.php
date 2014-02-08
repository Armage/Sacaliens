<?php

/* 
 * This file is part of Sacaliens
 * Copyright (c) 2009 Patrick Paysant
 *
 * Sacaliens is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * Sacaliens is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */

include_once('./sacaliens.conf') ;
require_once SYS_APP . '/src/Armg/Autoloader.php';
include_once('utils.php') ;

use Armg\Autoloader;
use Armg\Tpl;

$autoloader = new Autoloader();
spl_autoload_register(array($autoloader, 'load'));

//----------------------------------------------- display
/**
 * Login form
 *
 * @param string error message
 *
 */
function display($msg='') {
	global $_t ;

	$tpl = new Tpl(SYS_TPL) ;
	$tpl->addData(array(
		'error_msg' => $msg, 
		'self' => $_SERVER['PHP_SELF'],
		'signin' => $_t['signin'],
		'login' => $_t['login'],
		'password' => $_t['password'],
		'send' => $_t['send'])) ;

	if (isAndroid()) {
		$tpl->runTpl('android/index.tpl') ;
	}
	else {
		$tpl->runTpl('index.tpl') ;
	}
}

//----------------------------------------------- login
/**
 * Authentication
 *
 */
function login() {
	global $_t ;

	$login = addslashes(htmlspecialchars($_POST['login'])) ;
	$password = addslashes(htmlspecialchars($_POST['passwd'])) ;

	if (trim($login) == '') {
		//display("Veuiller entrer votre identifiant") ;
		display($_t['ask_login']) ;
		exit() ;
	}

	if (trim($password) == '') {
		//display("Veuillez entrer votre mot de passe");
		display($_t['ask_password']);
		exit() ;
	}

	$db = \Armg\DB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS) ;

	$sql = "select * from ".DB_TABLE_PREFIX."user where login = '".$login."' and " ;
	$sql .= "password = '".$password."' ;" ;

	$result = $db->queryFetchAllAssoc($sql) ;
  
	if (count($result) > 1) { // if more than one id ( = problem !)
    		//display ('La base de donnees est sans doute corrompue. Contacter l\'administrateur') ;
    		display ($_t['corrupted_database']) ;
    		exit() ;
  	} // if no id found (login error)
  	elseif (count($result) <= 0) {
    		//display('Mauvais identifiant ou mauvais mot de passe') ;
    		display($_t['bad_login_password']) ;
    		exit() ;
  	} 
  	else { // id found
    		$row = $result[0] ;
    		$id = $row['id'] ;
			$login = $row['login'] ;

    		// starting session
    		session_name(SESSION_NAME) ;
    		session_start() ;

    		$_SESSION['s_id_user'] = $id ;
			$_SESSION['s_login'] = $login ;
    		$_SESSION['s_date_death'] = time() + 3600*4 ; // 4 heures

    		// if there is some stored url in cookie, go to it
    		if (!empty($_COOKIE['request'])) {
    			header("Location: ".$_COOKIE['request']);;
    		}
    		else {
    			header("Location: ".WEB_APP."/urls/") ;
    		}
  	}
}

$act = 'display';
if (isset($_REQUEST['act'])) {
    $act = $_REQUEST['act'] ;
}

switch ($act) {
 case 'display' : {
   display() ;
   break ;
 }
 case 'bad' : {
   display($_t['bad_login_password']) ;
   break ;
 }
 case 'late' : {
   display($_t['too_late']) ;
   break ;
 }
 case 'login' : {
   login() ;
   break ;
 }
}

# Local Variables:
# mode: C
# End:

?>
