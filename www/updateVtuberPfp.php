<?php

use app\core\Application;

require_once __DIR__ . "/vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Google_Client;
use Google_Service_YouTube;

$config = [
    "db" => [
        "dsn" => $_ENV["DB_DSN"],
        "user" => $_ENV["DB_USER"],
        "password" => $_ENV["DB_PASSWORD"]
    ],
    "twitch" => [
        "clientid" => $_ENV["TWITCH_CLIENTID"],
        "token" => $_ENV["TWITCH_TOKEN"]
    ],
    "yt" => [
        "key" => $_ENV["GOOGLE_API_KEY"]
    ],
    "userClass" => null
];

$app = new Application(__DIR__, $config);

$stmt = $app->db->pdo->prepare("select id, link, login from vtubers");
$stmt->execute();
$data = $stmt->fetchAll();

foreach ($data as $vtuber) {
    $link = $vtuber["link"];
    $login = $vtuber["login"];

    if (str_contains($link, "twitch.tv")) {
        $clientID = Application::$app->config["twitch"]["clientid"] ?? "";
        $token = Application::$app->config["twitch"]["token"] ?? "";

        $url = "https://api.twitch.tv/helix/users?login=" . $vtuber["login"];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Client-ID: $clientID", "Authorization: Bearer $token"));

        $result = get_object_vars(json_decode(curl_exec($ch)));
        curl_close($ch);

        $result = get_object_vars($result["data"][0]);

        $img = rtrim($result["profile_image_url"], "/ ");
    }

    if (str_contains($link, "youtube.com")) {
        $client = new Google_Client();
        $client->setDeveloperKey(Application::$app->config["yt"]["key"]);
        $service = new Google_Service_YouTube($client);

        $response = get_object_vars($service->channels->listChannels('snippet', ["id" => $vtuber["login"]]));
        $img = $response["items"][0]["snippet"]["thumbnails"]["default"]["url"];
    }

    if (!is_null($img)) {
        $stmt = $app->db->pdo->prepare("update vtubers set img='" . $img . "' where id=" . $vtuber["id"]);
        $stmt->execute();
    }
}
