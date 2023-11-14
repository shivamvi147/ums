<?php
// required headers
require_once 'config/headers.php';

// database connection file and User object
include_once 'config/database.php';
require_once 'config/sendJson.php';
include_once 'objects/user.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
// instantiate user object
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try{    // get posted data
        $data = json_decode(file_get_contents("php://input"));
        if (
            !isset($data->username) ||
            !isset($data->password) ||
            empty(trim($data->username)) ||
            empty(trim($data->password))
        ){
            sendJson(
                422,
                'Please fill all the required fields & None of the fields should be empty.',
                ['required_fields' => ['username', 'password']]
            );
        }
        $user->username = $data->username;
        $username_exists = $user->usernameexists();

        // JWT config
        require_once 'config/jwt.php';
        // check if username exists and if password is correct
        if($username_exists && password_verify($data->password, $user->password)){
            $data = array(
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "username" => $user->username,
            );
            $jwt = generateJWT($data);
            $user->id = $user->id;
            $user->token = $jwt;
            $user->updateToken();
            sendJson(200, "Successful login!", ["token" => $jwt]);
        } else {
            sendJson(401, "Login failed!");
        }
    }  catch (Exception $e){
        sendJson(400, "Bad Request!", ["error" => $e->getMessage()]);
    }
} else {
    sendJson(405, 'Invalid Request Method. HTTP method should be POST');
}
?>