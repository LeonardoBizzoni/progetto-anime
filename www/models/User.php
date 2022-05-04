<?php

namespace app\models;

use app\core\DbModel;

class User extends DbModel
{
    public string $firstname = "";
    public string $lastname = "";
    public string $email = "";
    public string $username = "";
    public string $password = "";
    public string $confirm = "";

    public static function tableName(): string
    {
        return "users";
    }

    public static function primaryKey(): string
    {
        return "id";
    }

    public function attributes(): array {
        return [ "firstname", "lastname", "email", "username", "password" ];
    }

    public function register()
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return $this->save();
    }

    public function rules(): array
    {
        return [
            "firstname" => [self::RULE_REQUIRED],
            "lastname" => [self::RULE_REQUIRED],
            "username" => [self::RULE_REQUIRED],
            "email" => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, "class" => self::class ]],
            "password" => [self::RULE_REQUIRED, [self::RULE_MIN, "min" => 1], [self::RULE_MAX, "max" => 100]],
            "confirm" => [self::RULE_REQUIRED, [self::RULE_MATCH, "match" => "password"]]
        ];
    }

    public function labels(): array
    {
        return [
            "firstname" => "First name",
            "lastname" => "Last name",
            "username" => "Your username",
            "email" => "Email",
            "password" => "Your super secret password",
            "confirm" => "Confirm your super secret password",
        ];
    }
}
