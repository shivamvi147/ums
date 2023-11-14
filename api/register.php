<?php
// required headers
require_once 'config/headers.php';
// JWT config
require_once 'config/jwt.php';

// files needed to connect to database
include_once 'config/database.php';
require_once 'config/sendJson.php';
include_once 'objects/user.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
// instantiate User object
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    // get posted data
    $data = json_decode(file_get_contents("php://input"));
    if (
        !isset($data->name) ||
        !isset($data->email) ||
        !isset($data->username) ||
        !isset($data->password) ||
        empty(trim($data->name)) ||
        empty(trim($data->email)) ||
        empty(trim($data->username)) ||
        empty(trim($data->password))
    ) {
        sendJson(
            422,
            'Please fill all the required fields & None of the fields should be empty.',
            ['required_fields' => ['name', 'email', 'username', 'password']]
        );
    }

    if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
        sendJson(422, 'Invalid Email Address!');
    } elseif (strlen($data->password) < 8) {
        sendJson(422, 'Your password must be at least 8 characters long!');
    }
    
    try{
        // set property values
        $user->name = $data->name;
        $user->email = $data->email;
        $user->username = $data->username;
        $user->password = $data->password;

        // Check for update or create user
        if(isset($data->jwt)){
            try {
                // get jwt
                $jwt=isset($data->jwt) ? $data->jwt : "";
                // decode jwt
                $decoded = validateJWT($jwt);
                $user->id = $decoded->id;
                if($user->checkusernameforupdate()){
                    sendJson(409, 'Email already exists!');
                } else {
                    // update the user record
                    if($user->update()){
                        // we need to re-generate jwt because user details might be different
                        $data = array(
                                "id" => $user->id,
                                "name" => $user->name,
                                "email" => $user->email,
                                "username" => $user->username,
                        );
                        $jwt = generateJWT($data);
                        $user->token = $user->$jwt;
                        $user->updateToken();
                        sendJson(200, 'User has been updated!', ['token'=> $jwt]);
                    }else{
                        sendJson(422, 'Unable to update user!');
                    }
                }
            } catch (Exception $e){
                sendJson(401, "Bad request", [$e->getMessage()]);
            }
        } else {
            try{
                // Check if user already exist with same username
                if($user->usernameexists()){
                    sendJson(409, "User is already exist with the provided username");
                } else {
                    if($user->create()){
                        $data = array(
                            "id" => $user->id,
                            "name" => $user->name,
                            "email" => $user->email,
                            "username" => $user->username,
                        );
                        $jwt = generateJWT($data);
                        $user->token = $jwt;
                        $user->updateToken();
                        sendJson(201, "User created successfully");
                    } else {
                        sendJson(422, "Unable to create user");
                    }
                }
            } catch (Exception $e){
                sendJson(422, "Unable to create user", [$e->getMessage()]);
            }
        }
    } catch (Exception $e){
        sendJson(400, "Bad request", [$e->getMessage()]);
    }
} else {
    sendJson(405, 'Invalid Request Method. HTTP method should be POST');
}
