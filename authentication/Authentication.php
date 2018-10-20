<?php

# Interface
require_once 'view/IView.php';

# Exceptions
require_once 'model/exception/username/TooShortException.php';
require_once 'model/exception/username/InvalidCharactersException.php';
require_once 'model/exception/password/TooShortException.php';

# View
require_once 'view/RegisterView.php';
require_once 'view/LoginView.php';

# Controller
require_once 'controller/LoginController.php';
require_once 'controller/MainController.php';
require_once 'controller/RegisterController.php';

# Model
require_once 'model/SessionState.php';
require_once 'model/Username.php';
require_once 'model/Password.php';
require_once 'model/UserCredentials.php';
require_once 'model/Storage.php';
require_once 'model/Session.php';
require_once 'model/Cookie.php';
require_once 'model/Database.php';
require_once 'model/UserDAL.php';
require_once 'model/TemporaryUserDAL.php';
require_once 'model/UserCredentialsDAL.php';

use \view\IView;

class Authentication
{
    private $main;

    public function __construct(\model\Database $db)
    {
        $this->main = new \controller\MainController($db);
    }

    public function getAuthenticationView(bool $wantsToRegister): IView
    {
        return $this->main->route($wantsToRegister);
    }

    public function isAuthenticated(): bool
    {
        return $this->main->isAuthenticated();
    }

    public function getUsername(): string
    {
        return $this->main->getUsername();
    }
}
