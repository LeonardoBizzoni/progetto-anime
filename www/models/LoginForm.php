<?php
namespace app\models;

use app\core\Application;
use app\core\BaseModel;

class LoginForm extends BaseModel
{
    public string $email = "";
    public string $password = "";

    public function rules(): array
    {
        return [
            "email" => [self::RULE_REQUIRED, self::RULE_EMAIL],
            "password" => [self::RULE_REQUIRED]
        ];
    }

    public function login() {
        $user = User::findOne(["email" => $this->email]);

        if (!$user) {
            $this->addError("email", "User does not exist!");
            return false;
        } else {
            if (!password_verify($this->password, $user->password)) {
                $this->addError("password", "Password is incorrect");
                return false;
            }
        }

        return Application::$app->login($user);
    }

    public function labels(): array
    {
        return [
            "email" => "Your account email",
            "password" => "The super secret password to your epic account"
        ];
    }
}
