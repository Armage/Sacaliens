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

class Request {
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';
    
    protected $method;
    protected $tokens;
    
    public function __construct() {
        // slice URL
        $method = self::METHOD_GET ;
        if ($_SERVER['REQUEST_METHOD'] == "POST") $this->method = self::METHOD_POST;
        
        list($path  ) = explode('?', $_SERVER['REQUEST_URI']) ;
        $path = preg_replace('#^'.WEB_APP.'/#', '', $path) ;
        
        $this->tokens = explode('/', $path) ;
    }
    
    public function getMethod() {
        return $this->method;
    }
    
    public function getTokens() {
        return $this->tokens;
    }
    
    public function getParam($key, $defaultValue=null) {
        $value = $defaultValue;
        if (isset($_REQUEST[$key])) {
            $value = $_REQUEST[$key];
        }
        return $value;
    }
}