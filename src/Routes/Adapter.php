<?php

namespace Adplay\Routes;

use Adplay\Middleware\TokenMiddleware;
use Adplay\Routing\Route;
use Adplay\Routing\Router;


class Adapter
{

    private $router;

    public function __construct()
    {
        // Instantiate Router
        $this->router = new Router();
    }

    public function routes()
    {
        // Define your routes

        $this->router
            ->addRoute('POST', '/token', 'AuthController@getToken');
            
        $this->router
             ->addRoute('POST', '/bidder', 'BidderController@index',[
                TokenMiddleware::class // middleware for token check
             ]);

        return $this;
    }

    public function execute()
    {
        
        $this->router->handleRequest();
    }
}
