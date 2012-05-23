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

include_once('./utils.php');

$db = armgDB::getInstance(DB_HOST, DB_BASE, DB_USER, DB_PASS);

// get all urls
$sql = "SELECT id, url FROM ".DB_TABLE_PREFIX."url";
$urls = $db->queryFetchAllAssoc($sql);

foreach($urls as $url) {
	$fragments = parse_url($url['url']);
	
	// parse_url returns false on malformed url...
	if($fragments) {
		$items = array('scheme', 'host', 'user', 'pass', 'path', 'query', 'fragment') ;
		$fields = array();
		$values = array();
		
		foreach($items as $item) {
			if (!empty($fragments[$item])) {
				$fields[] = $item;
				$values[] = $fragments[$item];
			}
		}
		$sql_field = join(', ', $fields) ;
		$sql_values = "'" . join("', '", $values) . "'";
		
		$sql = "INSERT INTO sac_fragments (id_url, " . $sql_field . ") VALUES (" . $url['id'] . ", " . $sql_values . ")";
		debug($sql);
		$db->query($sql);
	}
}