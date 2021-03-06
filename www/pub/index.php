<?php
use app\controllers\AuthController;
use app\controllers\SiteController;
use app\core\Application;

require_once __DIR__."/../vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
    "userClass" => \app\models\User::class,
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
    ]
];

$app = new Application(dirname(__DIR__), $config);

$app->router->get("/", [SiteController::class, "live"]);
$app->router->post("/", [SiteController::class, "live"]);

$app->router->get("/list", [SiteController::class, "list"]);
$app->router->post("/list", [SiteController::class, "list"]);

# User authentication
$app->router->get("/login", [AuthController::class, "login"]);
$app->router->post("/login", [AuthController::class, "login"]);

$app->router->get("/register", [AuthController::class, "register"]);
$app->router->post("/register", [AuthController::class, "register"]);

$app->router->get("/logout", [AuthController::class, "logout"]);

$app->run();
