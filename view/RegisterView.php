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

    private $isUsernameTaken = false;

    public function shouldRegister(): bool
    {
        return $this->hasClickedRegister() && $this->isValidInput();
    }

    public function getUserCredentials(): UserCredentials
    {
        return new UserCredentials(
            $this->getCleanedUsername(),
            $this->getTrimmedPassword()
        );
    }

    public function usernameIsTaken(): void
    {
        $this->isUsernameTaken = true;
    }

    public function response(): string
    {
        return $this->generateRegisterFormHTML($this->getMessage());
    }

    private function getMessage(): string
    {
        return ($this->hasClickedRegister())
        ? $this->getErrorMessages()
        : '';
    }

    private function getErrorMessages(): string
    {
        if ($this->isUsernameTaken) {
            return "User exists, pick another username.";
        }

        $errors = array_merge(
            $this->getUsernameErrors(),
            $this->getPasswordErrors()
        );

        if (count($errors) > 0) {
            return implode("<br>", $errors);
        }

        if (!$this->isPasswordMatching()) {
            return "Passwords do not match.";
        }

        return '';
    }

    private function getUsernameErrors(): array
    {
        $errors = array();
        try {
            new \model\Username(trim($this->getUsername()));
        } catch (\model\exception\username\InvalidCharactersException $e) {
            $errors[] = "Username contains invalid characters.";
        } catch (\model\exception\username\TooShortException $e) {
            $errors[] = "Username has too few characters, at least 3 characters.";
        }
        return $errors;
    }

    private function getPasswordErrors(): array
    {
        $errors = array();
        try {
            new \model\Password($this->getTrimmedPassword());
        } catch (\model\exception\password\TooShortException $e) {
            $errors[] = "Password has too few characters, at least 6 characters.";
        }
        return $errors;
    }

    private function isPasswordMatching(): bool
    {
        $isMatch = false;

        if ($this->hasPassword() && $this->hasRepeatedPassword()) {
            try {
                $pwd1 = new \model\Password($this->getTrimmedPassword());
                $pwd2 = new \model\Password($this->getRepeatedPassword());
                $isMatch = $pwd1->isSame($pwd2);
            } catch (\Exception $e) {
                $isMatch = false;
            }
        }
        return $isMatch;
    }

    private function isValidInput(): bool
    {
        return strlen($this->getErrorMessages()) === 0 && $this->isPasswordMatching();
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
        return trim(strip_tags($this->getUsername()));

    }

    private function getTrimmedPassword(): string
    {
        return $this->hasPassword()
        ? trim($_POST[self::$password])
        : '';
    }

    private function getRepeatedPassword(): string
    {
        return $this->hasRepeatedPassword()
        ? $_POST[self::$repeatPwd]
        : '';
    }

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
