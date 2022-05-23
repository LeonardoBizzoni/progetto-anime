<?php
namespace app\controllers;

use app\core\Application;
use app\core\BaseController;
use app\core\Request;
use app\core\Response;
use app\models\LoginForm;
use app\models\User;

class AuthController extends BaseController{
    public function login(Request $req) {
        Application::$app->router->title = "WeebSite - Login";
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
        Application::$app->router->title = "WeebSite - Sign up";
        $registerModel = new User;

        if ($req->getMethod() == "post") {
            $registerModel->loadData($req->getBody());

            if ($registerModel->validate() && $registerModel->register()) {
                Application::$app->res->redirect("/");
            }
        }
        return $this->render("register", [ "model" => $registerModel ]);
    }

    public function logout(Request $req, Response $res) {
        Application::$app->logout();
        $res->redirect("/");
    }
}
