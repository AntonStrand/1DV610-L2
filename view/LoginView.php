<?php

namespace view;

use \Exception;
use \model\SessionState;
use \model\UserCredentials;

class LoginView implements IView
{
    private static $login = 'LoginView::Login';
    private static $logout = 'LoginView::Logout';
    private static $name = 'LoginView::UserName';
    private static $password = 'LoginView::Password';
    private static $cookieName = 'LoginView::CookieName';
    private static $cookiePassword = 'LoginView::CookiePassword';
    private static $keep = 'LoginView::KeepMeLoggedIn';
    private static $messageId = 'LoginView::Message';
    private static $register = 'register';

    private $state;

    public function __construct(SessionState $state)
    {
        $this->state = $state;
    }

    public function wantsToRegister(): bool
    {
        return isset($_GET[self::$register]);
    }

    public function shouldLogin(): bool
    {
        return $this->hasClickedLogin() && $this->isInputValid();
    }

    public function shouldLogout(): bool
    {
        return $this->hasClickedLogout();
    }

    public function getUserCredentials(): UserCredentials
    {
        if ($this->hasRequiredInput()) {
            $keepLoggedIn = $this->keepLoggedIn();
            $username = $this->getRequestUsername();
            $password = $this->getRequestPassword();

            return new UserCredentials($username, $password, $keepLoggedIn);
        }
    }

    public function shouldSaveCookie(): bool
    {
        return $this->state->isAuthenticated() && $this->state->keepLoggedIn();
    }

    public function getCookieData(): UserCredentials
    {
        if (!$this->shouldSaveCookie()) {
            throw new Exception("The cookie should not be saved");
        }

        if ($this->isCookieSet()) {
            $username = $_COOKIE[self::$cookieName];
            $password = $_COOKIE[self::$cookiePassword];
            return new UserCredentials($username, $password);
        }

        $this->setCookie();
        return $this->getCookieData();
    }

    /**
     * Create HTTP response
     *
     * Should be called after a login attempt has been determined
     *
     * @return string and writes to cookies!
     */
    public function response()
    {
        if ($this->shouldSaveCookie()) {
            $this->setCookie();
        }

        $message = $this->getFormMessage();

        if ($this->state->isAuthenticated()) {
            $response = $this->generateLogoutButtonHTML($message);
        } else {
            $response = $this->generateLoginFormHTML($message);
        }

        return $response;
    }

    private function isCookieSet(): bool
    {
        return isset($_COOKIE[self::$cookieName]) && isset($_COOKIE[self::$cookiePassword]);
    }

    private function setCookie(): viod
    {
        $expires = time() + 86400;
        $username = $this->$state->getUsername();
        $randomPassword = bin2hex(random_bytes(20));
        setcookie(self::$cookieName, $username, $expires);
        setcookie(self::$cookiePassword, $randomPassword, $expires);
    }

    /**
     * Generate HTML code on the output buffer for the logout button
     * @param $message, String output message
     * @return void, BUT writes to standard output!
     */
    private function generateLogoutButtonHTML($message)
    {
        return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message . '</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
    }

    /**
     * Generate HTML code on the output buffer for the logout button
     * @param $message, String output message
     * @return string
     */
    private function generateLoginFormHTML($message)
    {
        return '
			<form method="post" >
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>

					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getUsername() . '" />
					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />
					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />

					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
    }

    private function getFormMessage(): string
    {
        echo $this->state->keepLoggedIn() ? 'Yes' : 'No';
        if ($this->loginFailed()) {
            $message = "Wrong name or password";

        } else if ($this->state->isFirstLogin() && $this->keepLoggedIn()) {
            $message = "Welcome and you will be remembered";

        } else if ($this->state->isFirstLogin()) {
            $message = "Welcome";

        } else if ($this->state->isFirstLogout()) {
            $message = "Bye bye!";

        } else if ($this->state->isNewUser()) {
            $message = "Registered new user.";

        } else if ($this->hasClickedLogin()) {
            $message = $this->getFormError();

        } else {
            $message = "";
        }

        return $message;
    }

    private function getFormError(): string
    {
        if (!$this->hasUsername()) {
            return 'Username is missing';
        }

        if (!$this->hasPassword()) {
            return 'Password is missing';
        }

        try {
            $this->getUserCredentials();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return '';
    }

    private function isInputValid(): bool
    {
        if ($this->hasRequiredInput()) {
            try {
                $this->getUserCredentials();
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        return false;
    }

    private function cleanInput(string $input): string
    {
        return trim(strip_tags($input));
    }

    ### LOCAL GETTERS ###
    private function hasRequiredInput(): bool
    {
        return $this->hasUsername() && $this->hasPassword();
    }

    private function hasUsername(): bool
    {
        return isset($_POST[self::$name]) && !empty($this->cleanInput($this->getRequestUsername()));
    }
    private function hasPassword(): bool
    {
        return isset($_POST[self::$password]) && !empty($this->cleanInput($this->getRequestPassword()));
    }

    private function getRequestUsername(): string
    {
        return isset($_POST[self::$name])
        ? $_POST[self::$name]
        : '';
    }

    private function getRequestPassword(): string
    {
        return isset($_POST[self::$password])
        ? $this->cleanInput($_POST[self::$password])
        : '';
    }

    private function keepLoggedIn(): bool
    {
        return isset($_POST[self::$keep]);
    }

    private function hasClickedLogin(): bool
    {
        return isset($_POST[self::$login]);
    }

    private function hasClickedLogout(): bool
    {
        return isset($_POST[self::$logout]);
    }

    private function getUsername(): string
    {
        $username = $this->state->getUsername();
        return $username !== '' ? $username : $this->getRequestUsername();
    }

    private function loginFailed(): bool
    {
        return strlen($this->getFormError()) == 0 && !$this->state->isAuthenticated();
    }
}
