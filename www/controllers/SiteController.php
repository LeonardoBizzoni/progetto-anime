<?php
namespace app\controllers;

use app\core\BaseController;
use app\core\Request;

class SiteController extends BaseController {
    public function home() {
        $params = [
            "name" => "Leonardo"
        ];

        return $this->render("home", $params);
    }

    public function contact() {
        return $this->render("contact");
    }

    public function handleContact(Request $req) {
        $body = $req->getBody();

        # $body validation

        return "Handling submitted data";
    }
}
?>
