<?php
namespace app\models;

use app\core\BaseModel;

class RegisterModel extends BaseModel {
    public string $Firstname = "";
    public string $Lastname = "";
    public string $Email = "";
    public string $Username = "";
    public string $Password = "";
    public string $Confirm = "";

    public function register() {
        echo "Creating new user";
    }

    public function rules(): array {
        return [
            "Firstname" => [self::RULE_REQUIRED],
            "Lastname" => [self::RULE_REQUIRED],
            "Username" => [self::RULE_REQUIRED],
            "Email" => [self::RULE_REQUIRED, self::RULE_EMAIL],
            "Password" => [self::RULE_REQUIRED, [ self::RULE_MIN, "min" => 20 ], [ self::RULE_MAX, "max" => 100 ]],
            "Confirm" => [self::RULE_REQUIRED, [self::RULE_MATCH, "match" => "Password" ]]
        ];
    }
}
?>
