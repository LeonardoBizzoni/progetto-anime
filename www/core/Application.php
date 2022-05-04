<?php
namespace app\core;

class Application {
    private BaseController $controller;

    public $userClass;
    public array $config;
    public Router $router;
    public Request $req;
    public Response $res;
    public Database $db;
    public Session $session;
    public ?DbModel $user = null;

    public static Application $app;
    public static string $ROOT_DIR;

    public function __construct(string $root, array $config) {
        self::$ROOT_DIR = $root;
        self::$app = $this;

        $this->userClass = $config["userClass"];
        $this->config = $config;
        $this->req = new Request();
        $this->res = new Response();
        $this->router = new Router($this->req, $this->res);
        $this->session = new Session;

        $this->db = new Database($config["db"]);

        $primaryValue = $this->session->get("user");
        if ($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        }
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

    public function login(DbModel $user) {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};

        $this->session->set("user", $primaryValue);

        return true;
    }

    public function logout() {
        $this->user = null;
        $this->session->remove("user");
    }
}
?>
