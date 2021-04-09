<?php

namespace Src\Service;


use Src\Common\APITrait;
use Src\Common\Utilities\ValidationTrait;

class PostService {


    use APITrait;
    use ValidationTrait;

    private $data;    
    private static $instance = NULL;
    private function __construct()
    {
        $this->data = null;
    }

    public static function getInstance () {
        if (self::$instance === NULL)
           self::$instance = new PostService();
        return self::$instance;
    }

    public function getPosts($requestData) {
        if (!$this->isArrayValid($requestData) || !$this->isVariableValid($requestData['sl_token'])) {
            $response['status'] = false;
            $response['error'] = 'Invalid Request';
            return $response;
        }
        if (!$this->isVariableValid($requestData['page'])) {
            $requestData['page'] = 1;
        }
        $response['status'] = true;
        if ($this->isArrayValid($this->data) && $this->data['page'] === $requestData['page']) {
            $response['data'] = json_decode($this->data, true);
            return $response;
        }
        $result = $this->callAPI('GET', 'posts', $requestData);
        if ($this->isVariableValid($result['error'])) {
            $response['status'] = false;
            $response['error'] = $result['error'];
            return $response;
        }
        $response['data'] = json_decode($result, true);
        $response['data'] = $response['data']['data'];
        $this->data['posts'] = $response['data']['posts'];
        $this->data['page'] = $response['data']['page'];
        return $response;
    }

}