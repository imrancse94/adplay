<?php

namespace Adplay\Routing;

use Adplay\Handler\Request;

class Route
{
    private $method;
    private $pattern;
    private $controller;
    private $middlewares = [];

    public function __construct($method, $pattern, $controller, $middlewares = [])
    {
        $this->method = $method;
        $this->pattern = $pattern;
        $this->controller = $controller;
        $this->middlewares = $middlewares;
    }


    public function handle(Request $request)
    {
        // Execute middlewares
        foreach ($this->middlewares as $middleware) {
            (new $middleware)->handle($request);
        }

        // Execute controller
        return $this;
    }

    // Check if the route matches the HTTP method and URL
    public function matches($method, $url)
    {
        return $this->method === $method && preg_match("#^{$this->pattern}$#", $url);
    }

    // Get the controller associated with the route
    public function controller()
    {
        return $this->loadController()[0];
    }

    // Get the method associated with the route
    public function method()
    {
        return $this->loadController()[1];
    }

    // Load the controller and method associated with the route
    private function loadController()
    {
        $controllerParts = explode('@', $this->controller);
        $controllerName = $controllerParts[0];
        $methodName = $controllerParts[1];

        $controllerClassName = ucfirst($controllerName);
        $controllerFilePath = __DIR__ . '/../Controllers/' . $controllerClassName . '.php';

        if (file_exists($controllerFilePath)) {
            require_once $controllerFilePath;
            return [$controllerClassName, $methodName];
        } else {
            throw new \Exception("Controller '$controllerClassName' not found");
        }
    }


    

}