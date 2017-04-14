<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once('./sacaliens.conf') ;
require_once('./ng-utils.php') ;

ORM::configure('mysql:host=' . DB_HOST . ';dbname=' . DB_BASE);
ORM::configure('username', DB_USER);
ORM::configure('password', DB_PASS);
ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
ORM::configure('logging', true);


