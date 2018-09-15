<?php
namespace Controller;

class LoginController
{
    private $view;
    private $db;
    private $isLoggedIn = false;

    public function __construct(\View\LoginView $loginView)
    {
        $isLoggedIn = false;
        $this->view = $loginView;
        $this->db = new \Model\Database();
        $this->handleSubmit();
    }

    public function isLoggedIn(): bool
    {
        return $this->isLoggedIn;
    }

    private function handleSubmit(): void
    {
        if ($this->view->shouldSubmit()) {
            $formData = $this->view->getFormData();
            $this->isLoggedIn = $this->db->authenticateUser($formData["username"], $formData["password"]);
        }
    }

}
