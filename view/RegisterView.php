<?php

namespace view;

class RegisterView implements IView
{
    private static $register = 'RegisterView::Register';
    private static $name = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $repeatPwd = 'RegisterView::PasswordRepeat';
    private static $messageId = 'RegisterView::Message';

    public function shouldRegister(): bool
    {
        return $this->hasClickedRegister() && $this->isInputValid();
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
        try {
            new \model\Username($this->getCleanedUsername());
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        try {
            new \model\Password($this->getCleanedPassword());
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $errorsAsString .= $error . '<br>';
            }
        }

        if (count($errors) === 0 && $this->hasPassword() && $this->hasRepeatedPassword()) {
            $errorsAsString .= $this->isPasswordMatching() ? '' : 'Passwords do not match.';
        }

        return $errorsAsString;
    }

    private function isValidInput(): bool
    {
        return count($this->getErrorMessages) === '' && $this->isPasswordMatching();
    }

    private function isPasswordMatching(): bool
    {
        try {
            $pwd1 = new \model\Password($this->getCleanedPassword());
            $pwd2 = new \model\Password($this->getCleanedRepeatedPassword());
            return $pwd1->getPassword() === $pwd2->getPassword();
        } catch (\Exception $e) {
            return false;
        }
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

    private function getCleanedUsername(): string
    {
        return $this->hasUsername()
        ? $this->cleanInput($_POST[self::$name])
        : '';
    }

    private function getCleanedPassword(): string
    {
        return $this->hasPassword()
        ? $this->cleanInput($_POST[self::$password])
        : '';
    }

    private function getCleanedRepeatedPassword(): string
    {
        return $this->hasRepeatedPassword()
        ? $this->cleanInput($_POST[self::$repeatPwd])
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
