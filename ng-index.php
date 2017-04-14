<?php

require_once('./ng-data.php');
require_once('./ng-sac.php');

use sacaliens\Sacaliens;
use sacaliens\Datas;

require_once('ng-bootstrap.php');

$dataProvider = new Datas;
$sac = new Sacaliens($dataProvider);
$sac->index();
