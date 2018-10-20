<?php

require_once 'authentication/Authentication.php';
require_once 'Settings.php';

require_once 'app/view/LayoutView.php';
require_once 'app/view/DateTimeView.php';
require_once 'app/view/Error500.php';
require_once 'app/model/Database.php';
require_once 'app/view/View.php';
require_once 'app/view/TodoForm.php';

ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

try {
    $todoForm = new \app\view\TodoForm();
    echo $todoForm->response();
    $layoutView = new \app\view\LayoutView();
    $dateTimeView = new \app\view\DateTimeView();
    $auth = new \authentication\Authentication(new \app\model\Database());
    $authView = $auth->getAuthenticationView($layoutView->wantsToRegister());
    $isAuth = $auth->isAuthenticated();

    $layoutView->render(
        $isAuth,
        $authView,
        $dateTimeView
    );
} catch (\Exception $e) {
    new \app\view\Error500();
}
