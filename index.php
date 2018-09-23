<?php

# Interface
require_once 'view/IView.php';

# View
require_once 'view/RegisterView.php';
require_once 'view/LoginView.php';
require_once 'view/DateTimeView.php';
require_once 'view/LayoutView.php';

# Controller
require_once 'controller/LoginController.php';
require_once 'controller/MainController.php';
require_once 'controller/RegisterController.php';

# Model
require_once 'model/SessionState.php';
require_once 'model/Username.php';
require_once 'model/Password.php';
require_once 'model/UserCredentials.php';
require_once 'model/Storage.php';
require_once 'model/Session.php';
require_once 'model/Database.php';

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);

// TODO: Add try catch
$main = new \controller\MainController();
$main->route();
