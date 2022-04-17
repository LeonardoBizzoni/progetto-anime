<?php
namespace app\models;

use app\core\BaseModel;

class RegisterModel extends BaseModel {
    public string $firstname;
    public string $lastname;
    public string $email;
    public string $username;
    public string $pass;
    public string $passConf;

    public function register() {
        echo "Creating new user";
    }

    public function rules(): array {
        return [
            "firstname" => [self::RULE_REQUIRED],
            "lastname" => [self::RULE_REQUIRED],
            "email" => [self::RULE_REQUIRED, self::RULE_EMAIL],
            "pass" => [self::RULE_REQUIRED, [ self::RULE_MIN, "min" => 20 ], [ self::RULE_MAX, "max" => 100 ]],
            "passConf" => [self::RULE_REQUIRED, [self::RULE_MATCH, "match" => "pass" ]]
        ];
    }
}
?>
