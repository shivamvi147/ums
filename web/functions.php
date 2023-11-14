<?php 

$apibaseurl = 'http://localhost/ums1/api/';
function callAPI($method='GET', $endpoint, $data=null){
    global $apibaseurl;
    $url = $apibaseurl.$endpoint;
    $curl_handle = curl_init();
    if($method=='POST'){
        $postdata = json_encode($data);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    } else if($method=='GET'){
        $authorization = "Authorization: Bearer ".$_SESSION['token'];
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
    }
    curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, $method);
    // curl_setopt($curl_handle, CURLOPT_HTTPAUTH, CURLAUTH_NONE);
    curl_setopt($curl_handle, CURLOPT_URL, $url);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_handle, CURLOPT_VERBOSE, true);
    $curl_data  = curl_exec($curl_handle);
    $httpcode = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
    curl_close($curl_handle);
    return array(
        "httpcode" => $httpcode,
        "data" => json_decode($curl_data),
    );
    
}
