<?php

namespace app\core;

class Router
{
    private array $routes = [];

    public string $title;
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

        if (is_callable($callback)) {
            return call_user_func($callback);
        } else if (is_string($callback)) {
            return $this->renderView($callback);
        } else if (is_array($callback)) {
            Application::$app->setController(new $callback[0]);
            $callback[0] = Application::$app->getController();

            return call_user_func($callback, $this->req, $this->res);
        } else {
            $this->res->setStatusCode(404);
            return $this->renderView("404");
        }
    }

    public function renderView(string $view, array $params = [])
    {
        $layoutContent = $this->loadLayoutContent();
        $viewContent = $this->loadViewContent($view, $params);

        return str_replace("{{content}}", $viewContent, $layoutContent);
    }

    private function loadLayoutContent()
    {
        $layout = Application::$app->layout;
        if (Application::$app->getController()) {
            $layout = Application::$app->getController()->layout;
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        return ob_get_clean();
    }

    private function loadViewContent(string $view, array $params)
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }
}
