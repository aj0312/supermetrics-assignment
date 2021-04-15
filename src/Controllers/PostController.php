<?php

namespace Src\Controllers;

use Src\Gateways\PostGateway;
use Src\Service\PostService;

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
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'get-average-length-of-post-per-month':
                $postGateway = new PostGateway(PostService::getInstance());
                $response = $postGateway->getAvgCharLengthOfPostPerMonth();
                break;
            case 'get-longest-post-per-month':
                $postGateway = new PostGateway(PostService::getInstance());
                $response = $postGateway->getLongestPostByCharPerMonth();
                break;
            case 'get-total-posts-per-week':
                $postGateway = new PostGateway(PostService::getInstance());
                $response = $postGateway->getTotalPostsPerWeek();
                break;
            case 'get-average-posts-per-user-per-month':
                $postGateway = new PostGateway(PostService::getInstance());
                $response = $postGateway->getAveragePostsPerUserPerMonth();
                break;
            default:
                $postGateway = new PostGateway();
                $response = $postGateway->getDefaultMethodResponse();
                break;
        }
        header($response['status_code_header']);
        if (isset($response['error']) && !empty($response['error'])) {
            echo json_encode($response);
            return;
        }
        if ($response['body']) {
            echo json_encode($response);
            return;
        }
    }
}
