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

    private $message;
    private $state;

    private $cookie;

    public function __construct(SessionState $state)
    {
        $this->state = $state;
        $this->cookie = new \model\Cookie();
    }

    public function response()
    {
        $message = $this->getMessage();

        return $this->state->isAuthenticated()
        ? $this->generateLogoutButtonHTML($message)
        : $this->generateLoginFormHTML($message);
    }

    public function shouldLogin(): bool
    {
        return $this->hasClickedLogin() && $this->isInputValid() && !$this->state->isAuthenticated();
    }

    public function shouldLogout(): bool
    {
        return isset($_POST[self::$logout]);
    }

    public function getUserCredentials(): UserCredentials
    {
        assert($this->hasRequiredInput());

        $keepLoggedIn = $this->keepLoggedIn();
        $username = $this->getRequestUsername();
        $password = $this->getRequestPassword();

        return new UserCredentials($username, $password, $keepLoggedIn);
    }

    public function shouldSaveCookie(): bool
    {
        return $this->keepLoggedIn();
    }

    public function shouldLoginByCookie(): bool
    {
        return !$this->state->isAuthenticated() && $this->isCookieSet();
    }

    public function useDefaultWelcomeMessage(): void
    {
        $this->message = $this->keepLoggedIn()
        ? "Welcome and you will be remembered"
        : "Welcome";
    }

    public function useLoginByCookieMessage(): void
    {
        $this->message = "Welcome back with cookie";
    }

    public function useCookieErrorMessage(): void
    {
        $this->message = "Wrong information in cookies";
    }

    public function useLoginFailedMessage(): void
    {
        $this->message = "Wrong name or password";
    }

    public function useLogoutMessage(): void
    {
        $this->message = "Bye bye!";
    }

    public function setCookie(): void
    {
        $aDayInSeconds = 86400;
        $username = $this->getUsername();
        $password = bin2hex(random_bytes(20));
        $this->cookie->set(self::$cookieName, $username, $aDayInSeconds);
        $this->cookie->set(self::$cookiePassword, $password, $aDayInSeconds);
    }

    public function removeCookie(): void
    {
        $this->cookie->delete(self::$cookieName);
        $this->cookie->delete(self::$cookiePassword);
    }

    public function getCookieData(): UserCredentials
    {
        assert($this->isCookieSet());
        $username = $this->cookie->get(self::$cookieName);
        $password = $this->cookie->get(self::$cookiePassword);
        return new UserCredentials($username, $password);

    }

    private function isCookieSet(): bool
    {
        return $this->cookie->has(self::$cookieName) && $this->cookie->has(self::$cookiePassword);
    }

    private function isNewMember(): bool
    {
        return !$this->state->isAuthenticated() && $this->state->hasUsername();
    }

    private function getMessage(): string
    {
        if ($this->message != null) {
            return $this->message;
        }

        if ($this->isNewMember()) {
            return "Registered new user.";
        }

        if ($this->hasClickedLogin()) {
            return $this->getFormError();
        }

        return "";
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
            return "Wrong name or password";
        }

        return '';
    }

    private function isInputValid(): bool
    {
        if (!$this->hasRequiredInput()) {
            return false;
        }

        try {
            $this->getUserCredentials();
            return true;
        } catch (Exception $e) {
            return false;
        }

    }

    private function generateLogoutButtonHTML($message)
    {
        return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message . '</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
    }

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
        return isset($_POST[self::$name]) &&
        !empty($this->cleanInput($this->getRequestUsername()));
    }

    private function hasPassword(): bool
    {
        return isset($_POST[self::$password]) &&
        !empty($this->cleanInput($this->getRequestPassword()));
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

    private function getUsername(): string
    {
        return $this->state->hasUsername()
        ? $this->state->getUsername()
        : $this->getRequestUsername();
    }
}
