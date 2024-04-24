<?php


namespace Adplay\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler
{
    private static $key = "imr@ncse94"; // Change this to your secret key
    private static $tokenlifetime = 5; // in minutes
    private static $alg = 'HS256';
    
    // Generate JWT token
    public static function generateToken($payload)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + (60 * self::$tokenlifetime); // Token expires in 1 hour

        $token = JWT::encode([
            'iat' => $issuedAt, // Issued at: time when the token was generated
            'exp' => $expirationTime, // Expiration time
            'data' => $payload // Data being stored in the token
        ], self::$key, self::$alg); // Specify algorithm explicitly

        return $token;
    }

    

    // Verify JWT token
    public static function verifyToken($token)
    {
        try {
            
            $decoded = JWT::decode($token, new Key(self::$key, self::$alg));
            
            $decoded = (array) $decoded->data;
            return !empty($decoded);

        } catch (\Firebase\JWT\ExpiredException $e) {
            // Token is expired
            return false;
        } catch (\Exception $e) {
            // Token is invalid
            return false;
        }
    }
}