<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;

class Vtubers extends DbModel
{
    public string $username = "";
    public string $login = "";
    public string $img = "";
    public string $link = "";

    public function tableName(): string
    {
        return "vtubers";
    }

    public function attributes(): array
    {
        return ["username", "login", "img", "link"];
    }

    public function register()
    {
        return $this->save();
    }

    public function rules(): array
    {
        return [
            "username" => [self::RULE_REQUIRED],
            "login" => [self::RULE_REQUIRED],
            "img" => [self::RULE_REQUIRED],
            "link" => [self::RULE_REQUIRED, [self::RULE_UNIQUE, "class" => self::class]],
        ];
    }

    public function getVtuberName()
    {
        $clientID = Application::$app->config["twitch"]["clientid"] ?? "";
        $token = Application::$app->config["twitch"]["token"] ?? "";

        if (str_contains($this->link, "twitch.tv")) {
            $idol = str_replace("https://www.twitch.tv/", "", $this->link);
            $url = "https://api.twitch.tv/helix/users?login=$idol";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Client-ID: $clientID", "Authorization: Bearer $token"));

            $result = get_object_vars(json_decode(curl_exec($ch)));
            curl_close($ch);

            $result = get_object_vars($result["data"][0]);

            $this->username = ucfirst($result["display_name"]);
            $this->login= $result["login"];
            $this->img = rtrim($result["profile_image_url"], "/ ");
            return;
        }
        $this->username = "i got you bro";
        $this->login = "i got you bro";
        $this->img = "i got you bro";
    }
}
