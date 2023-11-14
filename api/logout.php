<?php
// required headers
require_once 'config/headers.php';
// JWT config
require_once 'config/jwt.php';
// database connection file and User object
include_once 'config/database.php';
require_once 'config/sendJson.php';
include_once 'objects/user.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
// instantiate user object
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try{    // get posted data
        $data = json_decode(file_get_contents("php://input"));
        if (
            !isset($data->token) ||
            empty(trim($data->token))
        ){
            sendJson(
                422,
                'Token is required',
                ['required_fields' => ['token']]
            );
        }
        $token = $data->token;
        // Decode the token to get its expiration time
        $decoded = validateJWT($token);
        $user->id = $decoded->id;
        $user->token = '';
        $user->updateToken();
        if ($decoded) {
            // Set the token expiration time to a past date
            $expired_time = time() - 3600; // Set to an arbitrary past time
            $decoded->exp = $expired_time;

            // Re-encode the token with the updated expiration time
            $new_token = refreshJWT($decoded);
            sendJson(200, 'Logout successful!');
        } else {
            sendJson(401, 'Token is invalid!');
        }
    } catch (Exception $e){
        sendJson(400, "Bad Request!", ["error" => $e->getMessage()]);
    }
} else  {
    sendJson(405, 'Invalid Request Method. HTTP method should be POST');
}
?>