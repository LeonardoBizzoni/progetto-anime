<?php

namespace app\models;

use \DOMDocument;
use app\core\Application;
use app\core\DbModel;
use Google_Client;
use Google_Service_YouTube;

class Vtubers extends DbModel
{
    public string $username = "";
    public string $login = "";
    public string $img = "";
    public string $link = "";

    public static function tableName(): string
    {
        return "vtubers";
    }

    public static function primaryKey(): string
    {
        return "id";
    }

    public function attributes(): array
    {
        return ["username", "login", "img", "link"];
    }

    public function labels(): array
    {
        return [
            "link" => "Vtuber channel link",
        ];
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

    public function getVtuberInfo()
    {
        if (str_contains($this->link, "twitch.tv")) {
            $clientID = Application::$app->config["twitch"]["clientid"] ?? "";
            $token = Application::$app->config["twitch"]["token"] ?? "";

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
            $this->login = $result["login"];
            $this->img = rtrim($result["profile_image_url"], "/ ");
            return;
        }
        if (str_contains($this->link, "youtube.com")) {
            $client = new Google_Client();
            $client->setDeveloperKey(Application::$app->config["yt"]["key"]);
            $service = new Google_Service_YouTube($client);

            if (str_contains($this->link, "https://www.youtube.com/channel/")) {
                $id = str_replace("https://www.youtube.com/channel/", "", $this->link);
            } else if (str_contains($this->link, "https://www.youtube.com/c/")) {
                    $ch = curl_init($this->link);
                    curl_setopt($ch, CURLOPT_URL, $this->link);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                    $result = curl_exec($ch);
                    curl_close($ch);

                    $doc = new DOMDocument();
                    libxml_use_internal_errors(true);
                    $doc->loadHTML($result);

                    $result = $doc->getElementsByTagName("link");
                    $length = $result->length;

                    for ($i = 0; $i < $length; $i++) {
                        $tag = $result->item($i)->getAttribute("href");

                        if (str_contains($tag, "https://www.youtube.com/channel/")) {
                            $id = str_replace("https://www.youtube.com/channel/", "", $tag);
                            break;
                        }
                    }
            }
            $response = get_object_vars($service->channels->listChannels('snippet', [ 'id' => $id ]));

            $this->username = $response["items"][0]["snippet"]["title"];
            $this->login = $id;
            $this->img = $response["items"][0]["snippet"]["thumbnails"]["default"]["url"];
            return;
        }
    }

    public function isLive(string $login, string $link)
    {
        $stmt = parent::prepare("SELECT login, link, live FROM vtubers WHERE login='$login' AND link='$link';");
        $stmt->execute();

        $row = $stmt->fetch();

        if (isset($row["live"])) {
            return $row["live"];
        }

        return [];
    }

    public function addToFav()
    {
        $stmt = parent::prepare("INSERT INTO favoriteVtuber(_vtuberID, _userID) values (".$_GET["id"].", ".Application::$app->user->id.")");
        $stmt->execute();
    }
}
