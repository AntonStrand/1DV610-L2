<?php

namespace View;

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

    /**
     * Get validated data from form
     *
     * @throws Exception if any data is invalid
     * @return \Model\UserCredentials
     */
    public function getFormData(): array
    {
        $username = $this->getRequestUserName();
        $password = $this->getRequestPassword();
        $keepLoggedIn = $this->keepLoggedIn();

        return new \Model\UserCredentials($username, $password, $keepLoggedIn);
    }

    /**
     * Returns true if the submit button is clicked and the input is valid
     *
     * @return boolean
     */
    public function shouldSubmit(): bool
    {
        try {
            if ($this->isLoginClicked()) {
                $this->getFormData();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function shouldLogout(): bool
    {
        return isset($_POST[self::$logout]);
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
        $isLoggedIn = \Session::getVariable(\Session::IS_LOGGED_IN) || false;

        if ($this->loginFailed()) {
            $message = 'Wrong name or password';
        } else if ($this->isLoginClicked()) {
            $errors = $this->getFormErrors();
            $message = empty($errors) ? 'Welcome' : $errors;
        } else if ($this->shouldLogout()) {
            $message = 'Bye bye!';
        } else {
            $message = '';
        }

        $response = $isLoggedIn
        ? $this->generateLogoutButtonHTML($message)
        : $this->generateLoginFormHTML($message);
        // $response .= $this->generateLogoutButtonHTML($message);
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
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getRequestUserName() . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />

					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
    }

    private function cleanInput($input): string
    {
        return trim(strip_tags($input));
    }

    /**
     * Get the user name from the form
     *
     * @return String - The provided user name
     */
    private function getRequestUserName(): string
    {
        return isset($_POST[self::$name]) ? cleanInput($_POST[self::$name]) : '';
    }

    /**
     * Get the password from the form
     *
     * @return string the provided password
     */
    private function getRequestPassword(): string
    {
        return isset($_POST[self::$password]) ? cleanInput($_POST[self::$password]) : '';
    }

    /**
     * Checks if the user wants to be kept logged in between sessions
     *
     * @return boolean - True if the checkbox is checked
     */
    private function keepLoggedIn(): bool
    {
        return isset($_POST[self::$keep]);
    }

    /**
     * If the login button is clicked
     *
     * @return boolean
     */
    private function isLoginClicked(): bool
    {
        return isset($_POST[self::$login]);
    }

    /**
     * Either validation error as string or an empty string if there was no errors.
     *
     * @return string Either error as string or an empty string if there was no errors.
     */
    private function getFormErrors(): string
    {
        try {
            $this->getFormData();
            return '';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * If the submit button is clicked and there is no form errors the user info is incorrect.
     *
     * @return boolean
     */
    private function loginFailed(): bool
    {
        $isLoggedIn = \Session::getVariable(\Session::IS_LOGGED_IN) || false;
        return ($this->isLoginClicked() && !$isLoggedIn && strlen($this->getFormErrors()) === 0);
    }

}
