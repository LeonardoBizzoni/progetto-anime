<?php
namespace app\controllers;

use app\core\BaseController;
use app\core\Request;

class AuthController extends BaseController{
    public function login() {
        $this->setLayout("auth");
        return $this->render("login");
    }

    public function register(Request $req) {
        $this->setLayout("auth");
        if ($req->getMethod() == "post") {
            return "Handling submitted data";
        }
        return $this->render("register");
    }
}
