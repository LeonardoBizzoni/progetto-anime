<?php
namespace app\controllers;

use app\core\Application;
use app\core\BaseController;
use app\core\Request;
use app\models\LoginForm;
use app\models\User;

class AuthController extends BaseController{
    public function login(Request $req) {
        $loginForm = new LoginForm;

        if ($req->getMethod() == "post"){
            $loginForm->loadData($req->getBody());

            if ($loginForm->validate() && $loginForm->login()) {
                Application::$app->res->redirect("/");
                return;
            }
        } 

        return $this->render("login", [ "model" => $loginForm ]);
    }

    public function register(Request $req) {
        $registerModel = new User;

        if ($req->getMethod() == "post") {
            $registerModel->loadData($req->getBody());

            if ($registerModel->validate() && $registerModel->register()) {
                Application::$app->res->redirect("/");
            }
        }
        return $this->render("register", [ "model" => $registerModel ]);
    }
}
