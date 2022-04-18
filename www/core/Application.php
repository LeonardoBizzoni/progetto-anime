<?php
namespace app\core;

class Application {
    private BaseController $controller;

    public Router $router;
    public Request $req;
    public Response $res;
    public Database $db;

    public static Application $app;
    public static string $ROOT_DIR;

    public function __construct(string $root, array $config) {
        self::$ROOT_DIR = $root;
        self::$app = $this;

        $this->req = new Request();
        $this->res = new Response();
        $this->router = new Router($this->req, $this->res);
        $this->db = new Database($config["db"]);
    }

    public function run() {
        echo $this->router->resolve();
    }

    public function getController() {
        return $this->controller;
    }

    public function setController(BaseController $controller) {
        $this->controller = $controller;
    }
}
?>
