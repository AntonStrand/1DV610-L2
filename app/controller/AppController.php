<?php

namespace app\controller;

class AppController
{
    /**
     * Move most of index.php here
     */
    public function __construct()
    {
        $db = new \app\model\Database();
        $todoDAL = new \app\model\TodoDAL($db);

        $todoForm = new \app\view\TodoForm();
        $todoFormController = new \app\controller\TodoFormController($todoForm);
        $layoutView = new \app\view\LayoutView();
        $dateTimeView = new \app\view\DateTimeView();
        $auth = new \authentication\Authentication(new \app\model\Database());
        $authView = $auth->getAuthenticationView($layoutView->wantsToRegister());
        $isAuth = $auth->isAuthenticated();
        if ($isAuth) {
            $usename = $auth->getUsername();
            $todoFormController->handleTodoForm($todoDAL, $usename);
        }

        $layoutView->render(
            $isAuth,
            $authView,
            $dateTimeView
        );
    }
}
