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
namespace Utils;

use Armg\Tpl;
use Armg\DB;

class Auth {
    
    /**
     * 
     * @return boolean
     */
    public function isAuthorized() {
        if (isset($_SESSION['s_id_user'])) {
            if ($_SESSION['s_date_death'] > time()) {
                $_SESSION['s_date_death'] = time() + 3600*4 ;
                
                return true;
            }
            else {
                storeUrlInCookie($_SERVER['REQUEST_URI']);
                session_destroy() ;
//                 if (preg_match('/XMLHttpRequest/i', @$_SERVER['HTTP_X_REQUESTED_WITH']))  exit() ;
//                 else header ('Location: '.WEB_APP.'/index.php?act=late') ;
                
                return false;
            }
        }
        else {
//             if (preg_match('/XMLHttpRequest/i', @$_SERVER['HTTP_X_REQUESTED_WITH'])) exit() ;
//             else header ('Location: '.WEB_APP.'/index.php') ;
            
            return false;
        }
    }
    
    //----------------------------------------------- display
    /**
     * Login form
     *
     * @param string error message
     *
     */
    function display($msg = '')
    {
        global $_t;
        
        $tpl = new Tpl(SYS_TPL);
        $tpl->addData(array(
            'error_msg' => $msg,
            'self' => $_SERVER['PHP_SELF'],
            'signin' => $_t['signin'],
            'login' => $_t['login'],
            'password' => $_t['password'],
            'send' => $_t['send']
        ));
        
        if (isAndroid()) {
            $tpl->runTpl('android/index.tpl');
        } 
        else {
            $tpl->runTpl('index.tpl');
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
            $this->display($_t['ask_login']) ;
            exit() ;
        }
    
        if (trim($password) == '') {
            //display("Veuillez entrer votre mot de passe");
            $this->display($_t['ask_password']);
            exit() ;
        }
    
        $db = DB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS) ;
    
        $sql = "select * from ".DB_TABLE_PREFIX."user where login = '".$login."' and " ;
        $sql .= "password = '".$password."' ;" ;
    
        $result = $db->queryFetchAllAssoc($sql) ;
    
        if (count($result) > 1) { // if more than one id ( = problem !)
            //display ('La base de donnees est sans doute corrompue. Contacter l\'administrateur') ;
            $this->display ($_t['corrupted_database']) ;
            exit() ;
        } // if no id found (login error)
        elseif (count($result) <= 0) {
            //display('Mauvais identifiant ou mauvais mot de passe') ;
            $this->display($_t['bad_login_password']) ;
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
}