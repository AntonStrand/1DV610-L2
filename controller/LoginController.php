<?php
namespace Controller;

class LoginController
{
    private $view;
    private $db;

    public function __construct(\LoginView $loginView)
    {
        $this->view = $loginView;
        $this->db = new \Model\Database();
        $this->handleSubmit();
    }

    private function handleSubmit(): void
    {
        if ($this->view->shouldSubmit()) {
            $formData = $this->view->getFormData();
            echo "is valid" . $this->db->authenticateUser($formData["username"], $formData["password"]);
        }
    }
}
