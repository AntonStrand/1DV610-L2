<?php
namespace Controller;

class LoginController
{
    private $view;

    public function __construct(\LoginView $loginView)
    {
        $this->view = $loginView;
        $this->handleSubmit();
    }

    private function handleSubmit(): void
    {
        if ($this->view->shouldSubmit()) {
            $formData = $this->view->getFormData();
        }
    }
}
