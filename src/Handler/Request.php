<?php

namespace Adplay\Handler;


class Request
{
    private $method;
    protected $data;

    public function __construct()
    {
        
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->data = [];

        // Check content type
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : null;

        if ($this->method === 'POST') {
            if ($contentType === 'application/json') {
                // Handle JSON data
                $json = file_get_contents('php://input');
                $this->data = json_decode($json, true);
            } else {
                // Handle form data
                $this->data = $_POST;
            }
        } elseif ($this->method === 'GET') {
            $this->data = $_GET;
        }

    }

    public function getData(){
        return $this->data;
    }

    // Get request method (GET, POST, etc.)
    public function method()
    {
        return $this->method;
    }

    // Get all request data
    public function all()
    {
        return $this->data;
    }

    // Get specific request data by key
    public function input($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    // Get JSON data
    public function json()
    {
        return $this->data;
    }

    public function bearerToken()
    {
        $authorizationHeader = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : null;

        if ($authorizationHeader && preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
            return $matches[1];
        }

        return null;
    }
}