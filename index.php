<?php

require_once 'authentication/Authentication.php';
require_once 'Settings.php';

require_once 'app/view/LayoutView.php';
require_once 'app/view/DateTimeView.php';
require_once 'app/view/View.php';
require_once 'app/view/Error500.php';
require_once 'app/model/Database.php';
require_once 'app/view/TodoForm.php';
require_once 'app/view/TodoList.php';
require_once 'app/view/TodoPage.php';
require_once 'app/view/Todo.php';
require_once 'app/model/Todo.php';
require_once 'app/model/TodoDAL.php';
require_once 'app/controller/TodoFormController.php';
require_once 'app/controller/TodoListController.php';
require_once 'app/controller/TodoController.php';
require_once 'app/controller/AppController.php';

ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// try {
new \app\controller\AppController();
// } catch (\Exception $e) {
//     new \app\view\Error500();
// }
