<?php

namespace Controller;

class Router
{
    private $layoutView;
    private $loginView;
    private $timeView;
    private $loginController;

    public function __construct()
    {
        $this->initViews();
        $this->initControllers();
    }

    public function route()
    {
        $isLoggedIn = $this->loginController->isLoggedIn();

        $this->layoutView->render($isLoggedIn, $this->loginView, $this->timeView);
    }

    private function initViews(): void
    {
        $this->layoutView = new \View\LayoutView();
        $this->loginView = new \View\LoginView();
        $this->timeView = new \View\DateTimeView();
    }

    private function initControllers(): void
    {
        $this->loginController = new LoginController($this->loginView);
    }
}
