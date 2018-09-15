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
require_once './controller/Router.php';

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// TODO: Wrap in a try catch before going live.
$router = new \Controller\Router();
$router->route();
