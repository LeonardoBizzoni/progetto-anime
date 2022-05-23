<?php

namespace app\controllers;

use app\core\Application;
use app\core\BaseController;
use app\core\Request;
use app\models\Vtubers;

class SiteController extends BaseController
{
    public function list()
    {
        Application::$app->router->title = "WeebSite - Your top vtubers";
        $this->setLayout("list");
        return $this->render("list");
    }

    public function live(Request $req)
    {
        $params = [];
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
                $params[] = [ $vtuber, $vtuberModel->isLive($vtuber["login"], $vtuber["link"]) ];
                if ($singleVtuber) {
                    Application::$app->router->title = "WeebSite - ".$vtuber["username"];
                }
            }
        }

        return $this->render("live", ["model" => $vtuberModel, $params]);
    }
}
