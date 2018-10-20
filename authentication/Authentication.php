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
require_once 'view/DateTimeView.php';
require_once 'view/LayoutView.php';
require_once 'view/Error500.php';

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

class Authentication
{
    public function getAuthenticationView(bool $wantsToRegister): \view\IView
    {
        $main = new \controller\MainController();
        return $main->route($wantsToRegister);
    }
}
// try {
// } catch (\Exception $e) {
//     new \view\Error500();
// }
