<?php

# View
require_once 'view/LoginView.php';
require_once 'view/DateTimeView.php';
require_once 'view/LayoutView.php';

# Controller
require_once 'controller/LoginController.php';

# Model
require_once 'model/Username.php';
require_once 'model/Password.php';
require_once 'model/UserCredentials.php';

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE VIEWS
$v = new \view\LoginView();
$dtv = new \view\DateTimeView();
$lv = new \view\LayoutView();
$lv->render(false, $v, $dtv);

$c = new \controller\LoginController($v);
