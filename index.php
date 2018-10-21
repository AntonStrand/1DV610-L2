<?php

require_once 'app/controller/AppController.php';
require_once 'authentication/Authentication.php';
require_once 'Settings.php';

ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);

try {
    new \app\controller\AppController();
} catch (\Exception $e) {
    new \app\view\Error500();
}
