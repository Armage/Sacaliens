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
namespace Armg;

Class Autoloader {
    /**
     * 
     * @param unknown $class
     */
    public function load($class) {
        $class = ltrim($class, '\\');
        
        $subpath = '';
        $dir = SYS_APP . '/src';
        
        $pos = strrpos($class, '\\');
        if ($pos !== false) {
            $ns = substr($class, 0, $pos);
            $subpath = str_replace('\\', DIRECTORY_SEPARATOR, $ns). DIRECTORY_SEPARATOR;
            $class = substr($class, $pos + 1);
        }
        
        $subpath .= str_replace('_', DIRECTORY_SEPARATOR, $class);                
        $file = $dir . DIRECTORY_SEPARATOR . $subpath . '.php';

        require $file;
    }
}