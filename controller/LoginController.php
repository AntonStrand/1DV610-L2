<?php
namespace Controller;

class LoginController
{
    private $view;
    private $db;

    public function __construct(\View\LoginView $loginView)
    {
        $this->view = $loginView;
        $this->db = new \Model\Database();
        $this->handleSubmit();
        $this->handleLogout();
    }

    public function isLoggedIn(): bool
    {
        return \Session::getVariable(\Session::IS_LOGGED_IN) || false;
    }

    private function handleSubmit(): void
    {
        if ($this->view->shouldSubmit()) {
            $formData = $this->view->getFormData();
            $isLoggedIn = $this->isLoggedIn() || $this->db->authenticateUser($formData["username"], $formData["password"]);

            if ($isLoggedIn) {
                \Session::setVariable(\Session::IS_LOGGED_IN, true);
            } else {
                \Session::setVariable(\Session::IS_LOGGED_IN, false);
            }
        }
    }

    private function handleLogout(): void
    {
        if ($this->view->shouldLogout()) {
            \Session::setVariable(\Session::IS_LOGGED_IN, false);
        }
    }

}
