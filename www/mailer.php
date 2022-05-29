<?php

use app\core\Application;
use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . "/vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = [
    "db" => [
        "dsn" => $_ENV["DB_DSN"],
        "user" => $_ENV["DB_USER"],
        "password" => $_ENV["DB_PASSWORD"]
    ],
    "mail" => [
        "username" => $_ENV["MAIL_USERNAME"],
        "pass" => $_ENV["MAIL_PASSWORD"],
    ],
    "userClass" => null
];

$app = new Application(__DIR__, $config);

$stmt = $app->db->pdo->prepare("
select email, vtubers.username, vtubers.id, notify
from users, vtubers, favoriteVtuber
where _vtuberID=vtubers.id
and _userID=users.id
and notify=1");

$stmt->execute();
$data = $stmt->fetchAll();


foreach ($data as $row) {
    // var_dump($row);
    $mail = new PHPMailer;
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "ssl";
    $mail->Host = "smtp.gmail.com";
    $mail->Port = "465";

    $mail->Username = Application::$app->config["mail"]["username"];
    $mail->Password = Application::$app->config["mail"]["pass"];

    $mail->SetFrom($mail->Username);

    $mail->Subject    = $row["username"] . " is live!";
    $mail->Body       = " ";

    $mail->AddAddress($row["email"]);

    if (!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo . "\n";
    } else {
        echo "Message sent!\n";
    }
}
