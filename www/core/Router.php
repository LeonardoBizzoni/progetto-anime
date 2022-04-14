<?php
namespace app\core;

class Router
{
    public Request $req;
    private array $routes = [];

    public function __construct(Request $req)
    {
        $this->req = $req;
    }

    public function get($path, $callback)
    {
        $this->routes["get"][$path] = $callback;
    }


    public function resolve()
    {
        $path = $this->req->getPath();
        $method = $this->req->getMethod();
        $callback = $this->routes[$method][$path] ?? false;

        if (!$callback) {
            echo "Not found!";
        } else {
            echo call_user_func($callback);
        }
    }
}
?>
