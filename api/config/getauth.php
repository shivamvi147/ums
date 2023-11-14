<?php
require 'jwt.php';
function getauthdetails(){
    $headers = getallheaders();
    $data = [];
    if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
        $jwt = validateJWT($matches[1]);
        $data['token'] = $matches[1];
        $data['id'] = (int) $jwt->id;
        return $data;
    } else {
        sendJson(403, "Authorization Token is Missing!");
    }
}
?>