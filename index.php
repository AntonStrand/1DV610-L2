<?php

require_once 'authentication/Authentication.php';
require_once 'Settings.php';

ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// try {
$layoutView = new \view\LayoutView();
$dateTimeView = new \view\DateTimeView();

$auth = new \Authentication();
$authView = $auth->getAuthenticationView($layoutView->wantsToRegister());
$layoutView->render(
    false,
    $authView,
    $dateTimeView
);
// } catch (\Exception $e) {
//     new \view\Error500();
// }
