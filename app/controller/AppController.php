<?php

namespace app\controller;

use app\controller\TodoController;
use app\model\Database;
use app\model\TodoDAL;
use app\view\DateTimeView;
use app\view\LayoutView;
use app\view\TodoForm;
use app\view\TodoList;
use app\view\TodoPage;
use authentication\Authentication;

class AppController
{
    public function __construct()
    {
        $db = new Database();
        $todoDAL = new TodoDAL($db);

        # Views
        $todoForm = new TodoForm();
        $todoList = new TodoList();
        $layoutView = new LayoutView();
        $dateTimeView = new DateTimeView();
        $todoPage = null;

        # Authenticate
        $auth = new Authentication($db);
        $authView = $auth->getAuthenticationView($layoutView->wantsToRegister());
        $isAuthenticated = $auth->isAuthenticated();

        if ($isAuthenticated) {
            $todoController = new TodoController($todoDAL, $todoForm, $todoList);
            $username = $auth->getUsername();
            $todoController->handleUser($username);
            $todoPage = new TodoPage($todoForm, $todoList);
        }

        $layoutView->render(
            $isAuthenticated,
            $authView,
            $dateTimeView,
            $todoPage
        );
    }
}
