<?php

require "../bootstrap.php";
use Src\Controllers\PostController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// all of our endpoints start with /person
// everything else results in a 404 Not Found
if ($uri[1] !== 'api') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

if (!isset($uri[2])) {
   header("HTTP/1.1 404 Not Found");
   exit();
}
$requestMethodType = $_SERVER["REQUEST_METHOD"];
$requestMethod = $uri[2];

// pass the request method and user ID to the PersonController and process the HTTP request:
$controller = new PostController($requestMethod, $requestMethodType);
$controller->processRequest();










 $postBodyArray = [
     'client_id' => 'ju16a6m81mhid5ue1z3v2g0uh',
     'email' => 'anish.chikodi@gmail.com',
     'name' => 'Anish Chikodi'
 ];
//  $response = callAPI('POST', "https://api.supermetrics.com/assignment/register", $postBodyArray);

//  $responseArray = json_decode($response, true);


 // create & initialize a curl session
$curl = curl_init();

curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $postBodyArray);

// set our url with curl_setopt()
curl_setopt($curl, CURLOPT_URL, "https://api.supermetrics.com/assignment/register");

// return the transfer as a string, also with setopt()
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

// curl_exec() executes the started curl session
// $output contains the output string
$output = curl_exec($curl);

// close curl resource to free up system resources
// (deletes the variable made by curl_init)
curl_close($curl);

$response = json_decode($output, true);


if (isset($response['data']) && !empty($response['data'])) {
    if ($this->sl_token !== $response['data']['sl_token']) {
        echo "Invalid token Passed!";
        return;
    }
    $_SESSION['user']['email'] = $this->email;
    $_SESSION['user']['name'] = $this->name;
    session_id($response['data']['sl_token']);
}
echo "<pre>";
print_r($response);exit;