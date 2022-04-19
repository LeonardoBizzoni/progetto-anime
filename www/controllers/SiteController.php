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
        $vtuberModel = new Vtubers;

        if ($req->getMethod() == "post") {
            $vtuberModel->loadData($req->getBody());
            $vtuberModel->getVtuberName();

            if ($vtuberModel->validate() && $vtuberModel->register()) {
                return "Success";
            }
        } else if ($req->getMethod() == "get") {
            $statement = Application::$app->db->pdo->prepare("SELECT * FROM vtubers;");
            $statement->execute();

            foreach ($statement->fetchAll() as $vtuber) {
                $params[] = ["vtuber" => [ $vtuber, $vtuberModel->isLive($vtuber["login"], $vtuber["link"]) ]];
            }
                // echo "<pre>";
                // var_dump($params);
                // echo "</pre";
        }

        if (isset($_GET["id"]))
            $this->setLayout("live");
        return $this->render("live", ["model" => $vtuberModel, $params]);
    }
}
