<?php

namespace Src\Controllers;

use Src\Gateways\PostGateway;

class PostController
{

    private $requestMethod;
    private $requestMethodType;

    private $postGateway;

    public function __construct($requestMethod, $requestMethodType)
    {
        $this->requestMethod = $requestMethod;
        $this->requestMethodType = $requestMethodType;

        // $this->postGateway = new PostGateway('asdsa');
        $this->slToken = null;
        $this->request = $_REQUEST;
        $this->clientId = getenv('CLIENT_ID');
    }

    public function processRequest()
    {
        // if (isset($_SESSION["timestamp"])) {
        //     if (time() - $_SESSION["timestamp"] > 3600) {
        //         unset($_SESSION['timestamp']);
        //         $response = $this->sessionExpired();
        //         header($response['status_code_header']);
        //         if ($response['body']) {
        //             echo $response['body'];
        //         }
        //         return;
        //     }
        // }
        // if ($this->requestMethod === 'register') {
        //     if (!isset($_SESSION['timestamp'])) {
        //         $result = $this->generateSLToken();
        //         if (isset($result['data'])) {
        //             $this->storeLoggedInUserData($result['data']);
        //         }
        //         $response['status_code_header'] = 'HTTP/1.1 200 Success';
        //         $response['body'] = json_encode([
        //             'data' => [
        //                 'PHPSESSID' => session_id()
        //             ]
        //         ]);
        //         $_SESSION['timestamp'] = time();
        //     }
        //     header($response['status_code_header']);
        //     if ($response['body']) {
        //         echo $response['body'];
        //     }
        //     return;
        // }
        // if (!$this->isAuthTokenValid()) {
        //     $response = $this->notFoundResponse();
        //     header($response['status_code_header']);
        //     if ($response['body']) {
        //         echo $response['body'];
        //     }
        //     return;
        // }
        switch ($this->requestMethod) {
            case 'get-average-character-length':
                $postGateway = new PostGateway($this->request);
                $result = $postGateway->getPosts();
                if(isset($result['error'])) {

                }
                echo "<pre>";
                print_r($result);
                die;
                break;
            case 'logout':
                $this->logout();
                exit;
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function logout() {
        if (isset($_SESSION['timestamp']))unset($_SESSION['timestamp']);
    }


    private function validateSLToken($slToken): bool
    {
        return ($this->slToken === $slToken);
    }

    private function generateSLToken()
    {

        $postBodyArray = [
            'client_id' => $this->clientId,
            'email' => $this->request['email'],
            'name' => $this->request['name']
        ];
        $outputString = $this->callAPI('POST', 'register', $postBodyArray);
        $result = json_decode($outputString, true);
        return $result;
    }

    private function storeRegisteredSLToken()
    {
        $result = $this->generateSLToken();

        $this->slToken = $result['sl_token'];
    }

    private function getSLToken(): string
    {
        if ($this->isSLTokenStored()) {
            return session_id();
        }
        return null;
    }


    private function isSLTokenStored(): bool
    {
        return (session_id() !== '' || session_id() !== null);
    }

    private function storeLoggedInUserData($data) {
        if (empty($data)) {
            //no data to store
            return false;
        }
        if (isset($data['email'])) {
            $_SESSION['user']['email'] = $data['email'];
        }
        
        if (isset($data['sl_token'])) {
            $_SESSION['user']['sl_token'] = $data['sl_token'];
        }
        
        return true;

    }

    private function isAuthTokenValid(): bool {
        return (isset($this->request['PHPSESSID']) && $this->request['PHPSESSID'] === session_id());
    }

}
