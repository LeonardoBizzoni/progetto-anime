<?php

namespace app\core;

class Router
{
    private array $routes = [];

    public Request $req;
    public Response $res;

    public function __construct(Request $req, Response $res)
    {
        $this->req = $req;
        $this->res = $res;
    }

    public function get($path, $callback)
    {
        $this->routes["get"][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes["post"][$path] = $callback;
    }


    public function resolve()
    {
        $path = $this->req->getPath();
        $method = $this->req->getMethod();
        $callback = $this->routes[$method][$path] ?? false;

        if (is_string($callback)) {
            echo $this->renderView($callback);
        } else if ($callback) {
            return call_user_func($callback);
        } else {
            $this->res->setStatusCode(404);
            echo $this->renderView("404");
        }
    }

    public function renderView(string $view)
    {
        $layoutContent = $this->loadLayoutContent();
        $viewContent = $this->loadViewContent($view);

        return str_replace("{{content}}", $viewContent, $layoutContent);
    }

    private function loadLayoutContent() {
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/main.php";
        return ob_get_clean();
    }

    private function loadViewContent(string $view) {
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }
}
?>
