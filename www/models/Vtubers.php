<?php

namespace app\models;

use app\core\DbModel;

class Vtubers extends DbModel
{
    public string $username = "";
    public string $link = "";

    public function tableName(): string
    {
        return "vtubers";
    }

    public function attributes(): array {
        return [ "username", "link" ];
    }

    public function register()
    {
        return $this->save();
    }

    public function rules(): array
    {
        return [
            "username" => [self::RULE_REQUIRED],
            "link" => [self::RULE_REQUIRED, [self::RULE_UNIQUE, "class" => self::class ]],
        ];
    }

    public function getVtuberName() {
        $this->username = "i got you bro";
    }
}
