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

session_name (SESSION_NAME) ;
session_start() ;

require_once SYS_APP . '/src/Armg/Autoloader.php';
include_once('utils.php') ;

use Armg\Autoloader;
use Utils\Auth;
use Utils\Request;
use Sacaliens\Sacaliens;

$autoloader = new Autoloader();
spl_autoload_register(array($autoloader, 'load'));

$request = new Request();

$auth = new Auth();
if ($auth->isAuthorized()) {
    dispatch($request);
}
else {
    if ($request->getMethod() == Request::METHOD_POST) {
        $auth->login();
    }
    else {
        $auth->display();
    }
}

function dispatch(Request $request) {
    $sacaliens = new Sacaliens();
    
    // dispatch
    $tokens = $request->getTokens();
    $method = $request->getMethod();

    if ($tokens[0] == '') {
        $sacaliens->display() ;
    }
    elseif ($tokens[0] == 'urls') {
        if ($method == Request::METHOD_GET) {
            storeUrlInCookie($_SERVER['REQUEST_URI']);
            if (isset($tokens[1]) and $tokens[1] !== '') $sacaliens->tagfilter($tokens[1]) ;
            else $sacaliens->display() ;
        }
    }
    elseif (($tokens[0] == 'edit') and ($tokens[1] == 'url')) {
        if ($method == Request::METHOD_GET) {
            if (isset($tokens[2])) $sacaliens->formEditDisplay($tokens[2]) ;
            else $sacaliens->formAddDisplay() ;
        }
        elseif ($method == Request::METHOD_POST) {
            if (isset($tokens[2])) $sacaliens->urlUpdate($tokens[2]) ;
            else $sacaliens->urlInsert() ;
        }
    }
    elseif (($tokens[0] == 'delete') and ($tokens[1] == 'url')) {
        $sacaliens->urlDelete($tokens[2]) ;
    }
    elseif ($tokens[0] == 'search') {
        if (isset($tokens[1]) and $tokens[1] == 'tags') $sacaliens->tagsearch() ;
        elseif (isset($tokens[1]) and $tokens[1] == 'url') {
            storeUrlInCookie($_SERVER['REQUEST_URI']);
            $sacaliens->display(array('search' => $tokens[2]));
        }
    }
    elseif ($tokens[0] == 'tags') {
        if ($method == Request::METHOD_GET) $sacaliens->tagDisplay() ;
        elseif (($method == Request::METHOD_POST) and (isset($_POST['tagedit']))) $sacaliens->tagEdit() ;
        elseif (($method == Request::METHOD_POST) and (isset($_POST['tagfusion']))) $sacaliens->tagFusion() ;
    }
}

?>
