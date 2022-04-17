<?php
namespace app\controllers;

use app\core\BaseController;
use app\core\Request;
use app\models\RegisterModel;

class AuthController extends BaseController{
    public function login() {
        // $this->setLayout("auth");
        return $this->render("login");
    }

    public function register(Request $req) {
        // $this->setLayout("auth");
        $errors = [];
        $registerModel = new RegisterModel;

        if ($req->getMethod() == "post") {
            $registerModel->loadData($req->getBody());

            if ($registerModel->validate() && $registerModel->register()) {
                return "Success";
            }
        }
        return $this->render("register", [ "model" => $registerModel ]);
    }
}
