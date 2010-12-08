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
Class armgTpl {
	private $tplPath ;
	private $layoutPath ;
	private $datas ;

	public function __construct($tplPath, $layoutPath="") {
		$this->setTplPath($tplPath) ; 
		$this->layoutPath = $layoutPath ;

		$this->datas = array() ;
	}

	public function setTplPath($tplPath) {
		if (($tplPath != "/") and (substr($tplPath,-1) != "/")) {
			$tplPath = $tplPath . "/" ;
		}
		if (!is_dir($tplPath) or !is_readable($tplPath)) {
			die ('Fatal : tplPath "'.$tplPath.'" is not valid !') ;
		}
		$this->tplPath = $tplPath ;
	}

	public function addData($datas) {
		if (is_array($datas)) {
			foreach($datas as $key => $value) {
				$this->datas[$key] = $value ;
			}
		}
	}

	public function runTpl($tplFile) {
		if ($tplFile == "") {
			die ("Fatal : please choose a tplFile") ;
		}
		while ((substr($tplFile, 1) == ".") or (substr($tplFile, 1) == "/")) {
			$tplFile = ltrim($tplFile, "./") ;
		}
		if (!is_readable($this->tplPath.$tplFile)) {
			die ("Fatal : tplFile $tplFile is not readable") ;
		}
		if (count($this->datas) > 0) extract($this->datas) ;
		require($this->tplPath.$tplFile) ;
	}

}

?>
