<?php

# Interface
require_once 'view/IView.php';

# View
require_once 'view/LoginView.php';
require_once 'view/DateTimeView.php';
require_once 'view/LayoutView.php';

# Controller
require_once 'controller/LoginController.php';

# Model
require_once 'model/SessionState.php';
require_once 'model/Username.php';
require_once 'model/Password.php';
require_once 'model/UserCredentials.php';
require_once 'model/Storage.php';
require_once 'model/Session.php';

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$storage = new \model\Storage();
$state = $storage->getSessionState();

//CREATE OBJECTS OF THE VIEWS
$v = new \view\LoginView($state);
$dtv = new \view\DateTimeView();
$lv = new \view\LayoutView();

$c = new \controller\LoginController($v, $storage, $state);

$lv->render($state->isAuthenticated(), $v, $dtv);

## USE TO ROUTER REGISTER
// echo isset($_GET["register"]) ? 'register' : 'nope';
