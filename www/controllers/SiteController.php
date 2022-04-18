<?php
namespace app\controllers;

use app\core\BaseController;
use app\core\Request;
use app\models\Vtubers;

class SiteController extends BaseController {
    public function home() {
        $params = [
            "name" => "Leonardo"
        ];

        return $this->render("home", $params);
    }

    public function live(Request $req) {
        $errors = [];
        $vtuberModel = new Vtubers;

        if ($req->getMethod() == "post") {
            $vtuberModel->loadData($req->getBody());
            $vtuberModel->getVtuberName();

            if ($vtuberModel->validate() && $vtuberModel->register()) {
                return "Success";
            }
        }
        return $this->render("live", [ "model" => $vtuberModel ]);
    }
}
