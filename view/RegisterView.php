<?php

namespace view;

use \model\UserCredentials;

class RegisterView implements IView
{
    private static $register = 'RegisterView::Register';
    private static $name = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $repeatPwd = 'RegisterView::PasswordRepeat';
    private static $messageId = 'RegisterView::Message';

    public function shouldRegister(): bool
    {
        return $this->hasClickedRegister() && $this->isValidInput();
    }

    public function getUserCredentials(): UserCredentials
    {
        return new UserCredentials(
            $this->getCleanedUsername(),
            $this->getCleanedPassword())
        ;
    }

    /**
     * Create HTTP response
     *
     * Should be called when a register attempt has been determined
     *
     * @return string and writes to cookies!
     */
    public function response(): string
    {
        return $this->generateRegisterFormHTML($this->getMessage());
    }

    private function getMessage(): string
    {
        if ($this->hasClickedRegister()) {
            return $this->getErrorMessages();
        }
        return '';
    }

    private function getErrorMessages(): string
    {
        $errorsAsString = '';
        $errors = array();

        # Test username
        try {
            $username = $this->getUsername();
            $filtered = $this->getCleanedUsername();

            if (strlen($username) === strlen($filtered)) {
                new \model\Username($filtered);
            } else {
                $errors[] = 'Username contains invalid characters.';
            }

        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        # Test password
        try {
            new \model\Password($this->getCleanedPassword());
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        # Test matching password if no errors where found
        if (count($errors) === 0 && !$this->isPasswordMatching()) {
            $errors[] = 'Passwords do not match.';
        }

        # Turn errors into a string
        foreach ($errors as $error) {
            $errorsAsString .= $error . '<br>';
        }

        return $errorsAsString;
    }

    private function isValidInput(): bool
    {
        return strlen($this->getErrorMessages()) === 0 && $this->isPasswordMatching();
    }

    private function isPasswordMatching(): bool
    {
        $isMatch = false;

        if ($this->hasPassword() && $this->hasRepeatedPassword()) {
            try {
                $pwd1 = new \model\Password($this->getCleanedPassword());
                $pwd2 = new \model\Password($this->getRepeatedPassword());
                $isMatch = $pwd1->getPassword() === $pwd2->getPassword();
            } catch (\Exception $e) {
                $isMatch = false;
            }
        }
        return $isMatch;

    }

    private function hasClickedRegister(): bool
    {
        return isset($_POST[self::$register]);
    }

    private function hasUsername(): bool
    {
        return isset($_POST[self::$name]);
    }

    private function hasPassword(): bool
    {
        return isset($_POST[self::$password]) && strlen($_POST[self::$password]) > 0;
    }

    private function hasRepeatedPassword(): bool
    {
        return isset($_POST[self::$repeatPwd]) && strlen($_POST[self::$repeatPwd]) > 0;
    }

    private function getUsername(): string
    {
        return $this->hasUsername()
        ? $_POST[self::$name]
        : '';
    }

    private function getCleanedUsername(): string
    {
        return $this->cleanInput($this->getUsername());

    }

    private function getCleanedPassword(): string
    {
        return $this->hasPassword()
        ? $this->cleanInput($_POST[self::$password])
        : '';
    }

    private function getRepeatedPassword(): string
    {
        return $this->hasRepeatedPassword()
        ? $_POST[self::$repeatPwd]
        : '';
    }

    private function cleanInput(string $input): string
    {
        return trim(strip_tags($input));
    }

    /**
     * Generate HTML code on the output buffer for the logout button
     * @param $message, String output message
     * @return string
     */
    private function generateRegisterFormHTML($message): string
    {
        return '
            <h2>Register new user</h2>
			<form method="post" >
                <fieldset>
                    <legend>Register a new user - Write Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>

					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getCleanedUsername() . '" /><br>
					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" /><br>
					<label for="' . self::$repeatPwd . '">Repeat password :</label>
					<input type="password" id="' . self::$repeatPwd . '" name="' . self::$repeatPwd . '" /><br>

					<input type="submit" name="' . self::$register . '" value="Register" />
				</fieldset>
			</form>
		';
    }
}
