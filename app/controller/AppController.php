<?php

namespace app\controller;

class AppController
{
    public function __construct()
    {
        $db = new \app\model\Database();
        $todoDAL = new \app\model\TodoDAL($db);

        $todoForm = new \app\view\TodoForm();
        $todoFormController = new \app\controller\TodoFormController($todoForm);
        $layoutView = new \app\view\LayoutView();
        $dateTimeView = new \app\view\DateTimeView();
        $auth = new \authentication\Authentication($db);
        $authView = $auth->getAuthenticationView($layoutView->wantsToRegister());
        $isAuth = $auth->isAuthenticated();
        $todoList = new \app\view\TodoList();
        $todoController = new \app\controller\TodoController($todoDAL, $todoForm, $todoList);
        $todoPage = null;

        if ($isAuth) {
            $username = $auth->getUsername();
            $todoController->handleUser($username);
            $todoPage = new \app\view\TodoPage($todoForm, $todoList);
        }

        $layoutView->render(
            $isAuth,
            $authView,
            $dateTimeView,
            $todoPage
        );
    }
}
