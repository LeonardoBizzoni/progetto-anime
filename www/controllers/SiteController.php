<?php

namespace app\controllers;

use app\core\Application;
use app\core\BaseController;
use app\core\Request;
use app\models\Vtubers;

class SiteController extends BaseController
{
    public function list(Request $req)
    {
        Application::$app->router->title = "WeebSite - Your top vtubers";
        $this->setLayout("list");

        if ($req->getMethod() == "post") {
            if (isset($req->getBody()["rem"])) {
                $stmt = Application::$app->db->pdo->prepare("DELETE FROM favoriteVtuber WHERE _userID=" . Application::$app->user->id . " AND _vtuberID=" . $req->getBody()["rem"]);
                $stmt->execute();
            } else if (isset($req->getBody()["notNotify"])) {
                $stmt = Application::$app->db->pdo->prepare("UPDATE favoriteVtuber SET notify=0 WHERE _vtuberID=" . $req->getBody()["notNotify"]);
                $stmt->execute();
            } else if (isset($req->getBody()["notify"])) {
                $stmt = Application::$app->db->pdo->prepare("UPDATE favoriteVtuber SET notify=1 WHERE _vtuberID=" . $req->getBody()["notify"]);
                $stmt->execute();
            }
        }

        return $this->render("list");
    }

    public function live(Request $req)
    {
        $vtuberLive = [];
        $singleVtuber = false;
        $vtuberModel = new Vtubers;

        Application::$app->router->title = "WeebSite - Live";
        $statement = Application::$app->db->pdo->prepare("SELECT * FROM vtubers;");

        if ($req->getMethod() == "post") {
            $vtuberModel->loadData($req->getBody());
            $vtuberModel->getVtuberInfo();

            if ($vtuberModel->validate() && $vtuberModel->register()) {
                Application::$app->res->redirect("/");
            }
        } else if ($req->getMethod() == "get") {
            if (isset($_GET["id"])) {
                $singleVtuber = true;
                $this->setLayout("live");
                $statement = Application::$app->db->pdo->prepare("SELECT * from vtubers where id={$_GET['id']}");
            }
            $statement->execute();

            foreach ($statement->fetchAll() as $vtuber) {
                $vtuberLive[] = [$vtuber, $vtuberModel->isLive($vtuber["login"], $vtuber["link"])];
                if ($singleVtuber) {
                    Application::$app->router->title = "WeebSite - " . $vtuber["username"];
                }
            }

            if (!Application::isGuest()) {
                $statement = Application::$app->db->pdo->prepare("SELECT _vtuberID FROM favoriteVtuber where _userID=".Application::$app->user->id);
                $statement->execute();

                foreach ($statement->fetchAll() as $fav) {
                    $favorites[] = $fav["_vtuberID"];
                }
            }
        }

        return $this->render("live", ["model" => $vtuberModel, $vtuberLive, $favorites]);
    }
}
