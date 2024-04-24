<?php


namespace Adplay\Controllers;

use Adplay\Handler\Request;
use Adplay\Utils\JWTHandler;

class AuthController{

    public function getToken(Request $request)
    {
        $inputData = $request->all();
        $username = $inputData['username']; 
        $password = $inputData['password'];  

        $data = [];
        $db_name = 'adplay';
        // caution password always will be on way hashing
        if($username == $db_name && $password == '123456'){
            $jwt = new JWTHandler();
            $data['token'] = $jwt->generateToken([
                'username'=>$db_name
            ]);

            $data['username'] = $username;
            return responseJSON($data,200);
        }

        return responseJSON(['message'=>'No data found'],404);
    }
}