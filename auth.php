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

/**
 * script d'authentification
 */

include_once('./utils.php') ;

session_name (SESSION_NAME) ;
session_start() ;

if (isset($_SESSION['s_id_user'])) {
  if ($_SESSION['s_date_death'] > time()) {
    $_SESSION['s_date_death'] = time() + 3600*4 ;
  }
  else {
  	storeUrlInCookie($_SERVER['REQUEST_URI']);
    session_destroy() ;
	if (preg_match('/XMLHttpRequest/i', @$_SERVER['HTTP_X_REQUESTED_WITH']))  exit() ; 
    else header ('Location: '.WEB_APP.'/index.php?act=late') ;
  }
}
else {
	if (preg_match('/XMLHttpRequest/i', @$_SERVER['HTTP_X_REQUESTED_WITH'])) exit() ;
	else header ('Location: '.WEB_APP.'/index.php') ;
}

?>
