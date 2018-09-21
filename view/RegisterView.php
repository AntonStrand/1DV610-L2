<?php

namespace view;

class RegisterView
{
    private static $register = 'RegisterView::Register';
    private static $name = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $repeatPwd = 'RegisterView::repeatPwd';
    private static $messageId = 'RegisterView::Message';

    /**
     * Create HTTP response
     *
     * Should be called when a register attempt has been determined
     *
     * @return void BUT writes to standard output and cookies!
     */
    public function response()
    {
        $message = '';

        $response = $this->generateRegisterFormHTML($message);

        return $response;
    }

    /**
     * Generate HTML code on the output buffer for the logout button
     * @param $message, String output message
     * @return  void, BUT writes to standard output!
     */
    private function generateRegisterFormHTML($message)
    {
        return '
			<form method="post" >
				<fieldset>
					<legend>Register a new user - Write Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>

					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="" />
					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />
					<label for="' . self::$repeatPwd . '">Repeat password :</label>
					<input type="password" id="' . self::$repeatPwd . '" name="' . self::$repeatPwd . '" />

					<input type="submit" name="' . self::$register . '" value="Register" />
				</fieldset>
			</form>
		';
    }
}
