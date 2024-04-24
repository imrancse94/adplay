<?php

namespace Adplay\Middleware;

use Adplay\Handler\Request;
use Adplay\Utils\JWTHandler;
use Exception;

class TokenMiddleware implements Middleware
{

    public function handle(Request $request)
    {
        // dd("dddd");
        $token = $request->bearerToken();

        if (empty($token)) {
            return responseJSON([
                'status'=>false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        $jwt = new JWTHandler();

        try {
            if ($jwt->verifyToken($token)) {
                return true;
            }
        } catch (\Exception $ex) {
        }

        return responseJSON([
            'status'=>false,
            'message' => 'Unauthenticated',
        ], 401);
    }
}
