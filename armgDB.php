<?php

/* 
 * This file is part of Sacaliens
 * Copyright (c) 2009 Patrick Paysant
 *
 * PHP Bookin is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * PHP Bookin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */
Class ArmgDB {
	private static $instance ;
	private $db ;

	private function __construct($host, $base, $login, $password) {
		$this->db = mysql_connect($host, $login, $password) or die("Fatal : ".$_t['dbserver_connect_error']) ;
		mysql_select_db($base, $this->db) or die("Fatal : ".$_t['database_connect_error']) ;
		$this->query("SET NAMES utf8") ;
	}

	public static function getInstance($host, $base, $login, $password) {
		if (!isset(self::$instance)) {
			self::$instance = new ArmgDB($host, $base, $login, $password) ;
		}
		return self::$instance ;
	}

	public function lastInsertId() {
		return mysql_insert_id($this->db);
	}

	public function query($sql) {
		@mysql_query($sql, $this->db) or die("Fatal : ".$_t['sql_error']) ;
	}

	public function queryFetchAllAssoc($sql) {
		$datas = array() ;

		$result = @mysql_query($sql, $this->db) or die("Fatal : ".$_t['sql_error']) ;
		if (mysql_num_rows($result) > 0) {
			while($row = mysql_fetch_assoc($result)) {
				$datas[] = $row ;
			}
		}

		return $datas ;
	}
	
	/**
	 * queryFetchFirstField
	 * @param $sql string
	 * @return the first field value from the first result's row or null if no result
	 **/
	public function queryFetchFirstField($sql) {
		$data = null ;

		$result = @mysql_query($sql, $this->db) or die("Fatal : ".$_t['sql_error']) ;
		if (mysql_num_rows($result) > 0) {
			while($row = mysql_fetch_row($result)) {
				$data = $row[0] ;
			}
		}

		return $data ;
	}
}

?>
