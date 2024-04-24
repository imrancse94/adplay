<?php

namespace Adplay\Routing;

use Adplay\Handler\Request;

class Router
{
    private $routes = [];
    private $middlewares = [];
    private $url;
    private $method;

    // Add a route to the router
    public function addRoute($method, $url, $controller,$middleware = [])
    {

        $this->routes[] = new Route($method, $url, $controller,$middleware);

        return $this;
    }

    // Match the URL to a registered route
    public function match($method, $url)
    {
        $request = new Request;
        foreach ($this->routes as $route) {
            if ($route->matches($method, $url)) {
                return $route->handle($request);
            }
        }
        return null;
    }

    // Handle the request
    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];
        
        $route = $this->match($method, $url);
        

        if ($route) {
            $controller = "Adplay\\Controllers\\".$route->controller();
           
            $method = $route->method();
            
            // Execute the controller method using Reflection
            $this->executeControllerMethod($controller, $method);
            
        } else {
            echo "404 Not Found";
        }
    }

    // Execute controller method using Reflection
    private function executeControllerMethod($controller, $method)
    {
        $reflectionMethod = new \ReflectionMethod($controller, $method);
        $class = new \ReflectionClass($controller); 
        $reflectionMethod = $class->getMethod($method);
        $parameters = $reflectionMethod->getParameters();
        
        $arguments = [];

            // Loop through parameters
            foreach ($parameters as $parameter) {
                // Get type of parameter
                $type = $parameter->getType();

                // If type exists, create instance of class
                if ($type && !$type->isBuiltin()) {
                    $className = $type->getName();
                    $arg = new $className();
                } else {
                    $arg = null;
                }

                // Add argument to the arguments array
                $arguments[] = $arg;
            }

          // $arguments = array_merge($urlArgs,$arguments);
            

        if ($reflectionMethod->isPublic()) {

            $reflectionMethod->invokeArgs(new $controller(),$arguments);

        } else {
            echo "Method $method is not accessible";
        }
        
    }

    public function handle(Request $request)
    {
        $result = true;
        // Execute middlewares
        $key = $this->method."_".$this->url;
        foreach ($this->middlewares[$key] as $middleware) {            
            $result = (new $middleware())->handle($request);
        }

        return $result;
    }

    public function middleware($middlewares){

        $key = $this->method."_".$this->url;

        if(is_array($middlewares)){
            foreach($middlewares as $middleware){
                $this->middlewares[$key][] = $middleware;
            }
        }else{
            $this->middlewares[$key][] = $middlewares;
        }
    }

}