<?php
namespace app\core;

class Application {
    public Router $router;
    public Request $req;

    public function __construct() {
        $this->req = new Request();
        $this->router = new Router($this->req);
    }

    public function run() {
        $this->router->resolve();
    }
}
?>
