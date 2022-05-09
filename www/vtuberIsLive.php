<?php
// se il canale è live aggiunge il link della live al database di vtuber
// aggiungi colonna alla table vtubers con null o il link alla live

use app\core\Application;

require_once __DIR__ . "/vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

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

$stmt = $app->db->pdo->prepare("select link, login from vtubers");
$stmt->execute();
$data = $stmt->fetchAll();

foreach ($data as $vtuber) {
    $link = $vtuber["link"];
    $login = $vtuber["login"];

    if (str_contains($link, "twitch.tv")) {
        $clientID = $app->config["twitch"]["clientid"] ?? "";
        $token = $app->config["twitch"]["token"] ?? "";

        $url = "https://api.twitch.tv/helix/streams?user_login=$login";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Client-ID: $clientID", "Authorization: Bearer $token"));

        $result = get_object_vars(json_decode(curl_exec($ch)));
        curl_close($ch);

        // echo count($result["data"]) ? "$login live\n" : "$login non live\n";
        if (count($result["data"])) {
            echo "$login live\n";
        }
    }

    if (str_contains($link, "youtube.com")) {
        $url = "https://www.youtube.com/channel/$login/live";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
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
            if (str_contains($tag, "https://www.youtube.com/watch?v=")) {
                echo "$login live\n";
            }
        }

        unset($doc);
    }
}
