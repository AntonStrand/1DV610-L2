<?php

namespace view;

use \Exception;
use \model\SessionState;
use \model\UserCredentials;

class LoginView
{
    private static $login = 'LoginView::Login';
    private static $logout = 'LoginView::Logout';
    private static $name = 'LoginView::UserName';
    private static $password = 'LoginView::Password';
    private static $cookieName = 'LoginView::CookieName';
    private static $cookiePassword = 'LoginView::CookiePassword';
    private static $keep = 'LoginView::KeepMeLoggedIn';
    private static $messageId = 'LoginView::Message';

    private $user;

    public function __construct(SessionState $user)
    {
        $this->user = $user;
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
        return $this->createUserCredetials();
    }

    /**
     * Create HTTP response
     *
     * Should be called after a login attempt has been determined
     *
     * @return  void BUT writes to standard output and cookies!
     */
    public function response()
    {
        $message = '';

        if ($this->hasClickedLogin()) {
            $message .= $this->getFormError();
        }

        if ($this->user->isAuthenticated()) {
            $message = 'Welcome';
            $response = $this->generateLogoutButtonHTML($message);
        } else {
            $response = $this->generateLoginFormHTML($message);
        }

        return $response;
    }
    /**
     * Generate HTML code on the output buffer for the logout button
     * @param $message, String output message
     * @return  void, BUT writes to standard output!
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
     * @return  void, BUT writes to standard output!
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

    private function getFormError(): string
    {
        try {
            $this->createUserCredetials();
            return '';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private function isInputValid(): bool
    {
        try {
            $this->createUserCredetials();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function createUserCredetials(): UserCredentials
    {
        $username = $this->getCleanedRequestUsername();
        $password = $this->getCleanedRequestPassword();
        $keepLoggedIn = $this->keepLoggedIn();

        return new UserCredentials($username, $password, $keepLoggedIn);
    }

    private function cleanInput(string $input): string
    {
        return trim(strip_tags($input));
    }

    ### LOCAL GETTERS ###
    private function getCleanedRequestUsername(): string
    {
        return isset($_POST[self::$name])
        ? $this->cleanInput($_POST[self::$name])
        : '';
    }

    private function getCleanedRequestPassword(): string
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
        $username = $this->user->getUsername();
        return $username !== '' ? $username : $this->getCleanedRequestUsername();
    }
}
