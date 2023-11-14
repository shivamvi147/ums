<?php
// required headers
require_once 'config/headers.php';
header("Access-Control-Allow-Methods: GET");

// files needed to connect to database
include_once 'config/database.php';
require_once 'config/sendJson.php';
include_once 'objects/user.php';
include_once 'config/getauth.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
// instantiate User object
$user = new User($db);


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $auth = getauthdetails();
    $user->id = $auth['id'];
    $data = $user->get();
    if($user->token == $auth['token']){
        if($data){
            $payload = array(
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "username" => $user->username,
            );
            sendJson(200, 'User found!', $payload);
        } else {
            sendJson(422, 'User not found!');
        }
    } else {
        sendJson(400, 'Invalid Token!');
    }
} else {
    sendJson(405, 'Invalid Request Method. HTTP method should be GET');
}