<?php

namespace Adplay\Handler;

class Response
{
    // HTTP status codes
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_NO_CONTENT = 204;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
    const HTTP_INTERNAL_SERVER_ERROR = 500;
    const HTTP_FOUND = 302;

    // JSON response
    public static function json($data, $status = self::HTTP_OK)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // Redirect response
    public static function redirect($url, $status = self::HTTP_FOUND)
    {
        header('Location: ' . $url, true, $status);
        exit;
    }

    // Plain text response
    public static function text($text, $status = self::HTTP_OK)
    {
        http_response_code($status);
        header('Content-Type: text/plain');
        echo $text;
        exit;
    }

    // HTML response
    public static function html($html, $status = self::HTTP_OK)
    {
        http_response_code($status);
        header('Content-Type: text/html');
        echo $html;
        exit;
    }
}
