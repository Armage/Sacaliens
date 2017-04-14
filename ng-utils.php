<?php

/*
 * This file is part of Sacaliens
 * Copyright (c) 2009 Patrick Paysant
 *
 * Saaliens is free software; you can redistribute it and/or
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

define('DEBUG', true) ;

function ajax_debug($var, $text="") {
      if (DEBUG) {
        $file = "/tmp/log.txt" ;
        $h = fopen($file, 'a') ;
        if (is_array($var)) {
          fputs($h, date('d/m/Y H:i:s').' array '.$text."\n") ;
          foreach($var as $key => $val) {
            fputs($h, '    ['.$key.'] => :'.$val.":\n") ;
          }
        }
        else {
          fputs($h, date('d/m/Y H:i:s').' '.$text. ' :'.$var.":\n") ;
        }
        fclose($h) ;
      }
}

////////////////////////////////////////////////////////////////////////////////
// Display variables, for debug
// sort of mix of print_r and var_dump...
// $var mixed variable to display
// $msg string a message to display
// $lvl int nothing to worry, used to indent display correctly, if unsure put 0 (zero)
// $border bool set it "true" if you want a nicer display
////////////////////////////////////////////////////////////////////////////////
function debug($var, $msg="", $lvl=0, $border=false) {
  $tabul = str_repeat("&nbsp;&nbsp;", $lvl) ; ;

  if ($border) {
    echo '<div style="background-color:#d99;text-align:left;margin:5px;padding:5px;color:black;border:3px solid red;">' ;
  }

  if (is_array($var)) {
    echo $tabul."$msg (array # " . count($var) . ")<br />\n" ;
    foreach($var as $key => $val) {
      debug($val, "[$key]", $lvl+1) ;
    }
  }
  elseif(is_object($var)) {
    $array = array() ;
    $array = (array)$var ;
    echo $tabul ."$msg (object ". get_class($var) .") <br/>\n" ;
    debug($array, "", $lvl+1) ;
  }
  elseif(is_bool($var)) {
    $boolean2string = ($var)?"TRUE":"FALSE" ;
    echo $tabul .$msg ." (boolean):". $boolean2string .":<br/>\n" ;
  }
  else {
    echo $tabul ."$msg (". gettype($var) ."):$var:<br />\n" ;
  }

  if ($border) {
    echo "</div>" ;
  }
}
