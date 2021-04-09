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

        $this->slToken = null;
        $this->request = $_REQUEST;
        $this->clientId = getenv('CLIENT_ID');
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'get-average-character-length':
                $postGateway = new PostGateway($this->request);
                $response = $postGateway->getAvgCharLengthOfPost();
                break;
            default:
                //
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

}
