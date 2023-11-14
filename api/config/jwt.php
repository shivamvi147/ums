<?php
// show error reporting
error_reporting(E_ALL);
// set your default time-zone
date_default_timezone_set('Asia/Kolkata');
require __DIR__ . '/../vendor/autoload.php';
include_once 'vendor/firebase/php-jwt/src/BeforeValidException.php';
include_once 'vendor/firebase/php-jwt/src/ExpiredException.php';
include_once 'vendor/firebase/php-jwt/src/SignatureInvalidException.php';
include_once 'vendor/firebase/php-jwt/src/JWT.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

$_secretkey = 'shuraa_api_key';
$_algo = 'RS256';

function generateJWT($data) {
    global $_secretkey;
    global $_algo;
    $payload = [
        "iat" => time(),
        "exp" => time() + 3600,
        "iss" => 'http://localhost/shuraa/api/',
        "data" => $data,
    ];
    return JWT::encode($payload, $_secretkey, $_algo);
}

function refreshJWT($payload){
    global $_secretkey;
    global $_algo;
    JWT::encode((array) $payload, $_secretkey, $_algo);
}

function validateJWT($token) {
    global $_secretkey;
    global $_algo;
    try {
        $decode = JWT::decode($token, new Key($_secretkey, $_algo));
        return $decode->data;
    } catch (ExpiredException | SignatureInvalidException $e) {
        sendJson(401, $e->getMessage());
    } catch (UnexpectedValueException | Exception $e) {
        sendJson(400, $e->getMessage());
    }
}
?>