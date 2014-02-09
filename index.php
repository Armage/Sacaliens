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

use Armg\Autoloader;
use Armg\DB;
use Armg\Tpl;
use Utils\Auth;
use Utils\Request;
use Utils\Utils;
use Sacaliens\Sacaliens;

$autoloader = new Autoloader();
spl_autoload_register(array($autoloader, 'load'));

$lang = Utils::getLang() ;
Utils::loadLang($lang) ;

$request = new Request();

$auth = new Auth();
$db = DB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS);
$auth->setDB($db);
$tpl = new Tpl(SYS_TPL);
$tpl->addData(array(
    'resourcesUrl' => WEB_RES,
));
$auth->setTpl($tpl);

if ($auth->isAuthorized()) {
    dispatch($request, $auth);
}
else {
    if ($request->getMethod() == Request::METHOD_POST) {
        $auth->login();
    }
    else {
        $auth->display();
    }
}

/**
 *
 * @param Request $request
 */
function dispatch(Request $request, Auth $auth)
{
    $sacaliens = new Sacaliens();

    $db = DB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS);
    $sacaliens->setDB($db);

    $tpl = new Tpl(SYS_TPL);
    $tpl->addData(array(
        'resourcesUrl' => WEB_RES,
    ));
    $sacaliens->setTpl($tpl);

    $tokens = $request->getTokens();
    $method = $request->getMethod();

    // "/"
    if ($tokens[0] == '') {
        $sacaliens->display() ;
    }
    // "/urls[/{tag1}[+{tagn}]*]"
    elseif ($tokens[0] == 'urls') {
        if ($method == Request::METHOD_GET) {
            Utils::storeUrlInCookie($_SERVER['REQUEST_URI']);
            if (isset($tokens[1]) and $tokens[1] !== '') $sacaliens->tagfilter($tokens[1]) ;
            else $sacaliens->display() ;
        }
    }
    // "/edit/url[/{id}]"
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
    // "/delete/url/{id}"
    elseif (($tokens[0] == 'delete') and ($tokens[1] == 'url')) {
        $sacaliens->urlDelete($tokens[2]) ;
    }
    // "/search/tags" ou "/search/url/{keywords}"
    elseif ($tokens[0] == 'search') {
        if (isset($tokens[1]) and $tokens[1] == 'tags') $sacaliens->tagsearch() ;
        elseif (isset($tokens[1]) and $tokens[1] == 'url') {
            Utils::storeUrlInCookie($_SERVER['REQUEST_URI']);
            $sacaliens->display(array('search' => $tokens[2]));
        }
    }
    // "/tags"
    elseif ($tokens[0] == 'tags') {
        if ($method == Request::METHOD_GET) $sacaliens->tagDisplay() ;
        elseif (($method == Request::METHOD_POST) and (isset($_POST['tagedit']))) $sacaliens->tagEdit() ;
        elseif (($method == Request::METHOD_POST) and (isset($_POST['tagfusion']))) $sacaliens->tagFusion() ;
    }
    elseif ($tokens[0] == 'logout') {
        $auth->logout();
    }
}

?>
