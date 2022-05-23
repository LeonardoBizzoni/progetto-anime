<?php
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

while (true) {
    $stmt = $app->db->pdo->prepare("select * from vtubers");
    $stmt->execute();
    $data = $stmt->fetchAll();

    foreach ($data as $vtuber) {
        $isLive = false;
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

            if (count($result["data"])) {
                echo "{$vtuber["username"]}\n";
                $stmt = $app->db->pdo->prepare("update vtubers set live='twitch.tv/$login' where id=" . $vtuber["id"]);
                $stmt->execute();
                $isLive = true;
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
                    echo "{$vtuber["username"]} - $tag\n";
                    $stmt = $app->db->pdo->prepare("update vtubers set live='$tag' where id=" . $vtuber["id"]);
                    $stmt->execute();
                    $isLive = true;
                }
            }

            unset($doc);
        }

        if (!$isLive) {
            $stmt = $app->db->pdo->prepare("update vtubers set live=NULL where id=" . $vtuber["id"]);
            $stmt->execute();
        }
    }
}
