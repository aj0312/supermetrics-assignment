<?php
namespace Src\Gateways;

use Src\Common\APITrait;

class PostGateway {


    use APITrait;
    public function __construct($requestData)
    {
        $this->requestData = $requestData;
        $this->data = null;
    }

    private function getPosts() {
        $response = $this->callAPI('GET', 'posts', $this->requestData);
        $result = json_decode($response, true);
        if (isset($result['error']) && !empty($result['error'])) {
            return $this->invalidTokenResponse($result['error']);
        }
        $this->data = $result['data'];
    }



}