<?php

namespace app\controllers;

use app\core\Application;
use app\core\BaseController;
use app\core\Request;
use app\models\Vtubers;

class SiteController extends BaseController
{
    public function home()
    {
        $params = [
            "name" => "Leonardo"
        ];

        return $this->render("home", $params);
    }

    public function live(Request $req)
    {
        $params = [];
        $statement = Application::$app->db->pdo->prepare("SELECT * FROM vtubers;");
        $vtuberModel = new Vtubers;

        if ($req->getMethod() == "post") {
            $vtuberModel->loadData($req->getBody());
            $vtuberModel->getVtuberInfo();

            if ($vtuberModel->validate() && $vtuberModel->register()) {
                Application::$app->res->redirect("/");
            }
        } else if ($req->getMethod() == "get") {
            if (isset($_GET["id"])) {
                $this->setLayout("live");
                $statement = Application::$app->db->pdo->prepare("SELECT * from vtubers where id={$_GET['id']}");
            }
            $statement->execute();

            foreach ($statement->fetchAll() as $vtuber) {
                $params[] = [ $vtuber, $vtuberModel->isLive($vtuber["login"], $vtuber["link"]) ];
            }
        }

        return $this->render("live", ["model" => $vtuberModel, $params]);
    }
}
