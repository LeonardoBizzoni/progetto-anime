<?php
namespace app\core;

class Application {
    public Router $router;
    public Request $req;
    public Response $res;

    public static Application $app;
    public static string $ROOT_DIR;

    public function __construct(string $root) {
        self::$ROOT_DIR = $root;
        self::$app = $this;

        $this->req = new Request();
        $this->res = new Response();
        $this->router = new Router($this->req, $this->res);
    }

    public function run() {
        echo $this->router->resolve();
    }
}
?>
