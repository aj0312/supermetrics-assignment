<?php
namespace Src\Gateways;

use Src\Common\APITrait;
use Src\Common\Utilities\CommonMethodsTrait;
use Src\Service\PostService;

class PostGateway {
    
    use CommonMethodsTrait;
    use APITrait;

    public function __construct(?PostService $postService = null) {
        $this->requestData = $_REQUEST;
        $this->postService = $postService;
    }


    public function getAvgCharLengthOfPostPerMonth(): array {
        $this->data = $this->postService->getPosts($this->requestData);
        if (!$this->data['status']) {
            return $this->invalidResponse($this->data['error']);
        }
        $result = $this->data;
        $averageCharLengthPostsPerMonth = $this->getAverageCharLengthOfPostsPerMonth($result['data']['posts']);

        $response = $this->validResponse($averageCharLengthPostsPerMonth);
        return $response;
    }

    public function getLongestPostByCharPerMonth(): array {
        $this->data = $this->postService->getPosts($this->requestData);
        if (!$this->data['status']) {
            return $this->invalidResponse($this->data['error']);
        }
        $result = $this->data;
        $longestPostPerMonth = $this->getLongestPostPerMonth($result['data']['posts']);
        $response = $this->validResponse($longestPostPerMonth);
        return $response;
    }

    public function getTotalPostsPerWeek(): array {
        $this->data = $this->postService->getPosts($this->requestData);
        if (!$this->data['status']) {
            return $this->invalidResponse($this->data['error']);
        }
        $result = $this->data;
        
        $totalPostsPerWeek = $this->getTotalPostsByWeek($result['data']['posts']);
        $response = $this->validResponse($totalPostsPerWeek);

        return $response;
    }

    public function getAveragePostsPerUserPerMonth(): array {
        $this->data = $this->postService->getPosts($this->requestData);
        if (!$this->data['status']) {
            return $this->invalidResponse($this->data['error']);
        }
        $result = $this->data;

        $totalPostsPerWeek = $this->getAveragePostsByUserPerMonth($result['data']['posts']);
        $response = $this->validResponse($totalPostsPerWeek);
        
        return $response;
    }

    public function getDefaultMethodResponse(): array {
        return $this->invalidMethodResponse();
    }

}