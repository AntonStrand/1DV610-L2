<?php

namespace app\controller;

# Model
require_once 'app/model/Database.php';
require_once 'app/model/Todo.php';
require_once 'app/model/TodoDAL.php';

# View
require_once 'app/view/LayoutView.php';
require_once 'app/view/DateTimeView.php';
require_once 'app/view/View.php';
require_once 'app/view/Error500.php';
require_once 'app/view/TodoForm.php';
require_once 'app/view/TodoList.php';
require_once 'app/view/TodoPage.php';
require_once 'app/view/Todo.php';

# Controller
require_once 'app/controller/TodoFormController.php';
require_once 'app/controller/TodoListController.php';
require_once 'app/controller/TodoController.php';

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
