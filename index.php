<?php

// Models
require_once 'model/Username.php';
require_once 'model/Password.php';
require_once 'model/Database.php';

// Views
require_once 'view/LoginView.php';
require_once 'view/DateTimeView.php';
require_once 'view/LayoutView.php';

// Controllers
require_once 'controller/LoginController.php';

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE VIEWS
$v = new LoginView();
$dtv = new DateTimeView();
$lv = new LayoutView();

$lv->render(false, $v, $dtv);
new \Controller\LoginController($v);
