<?php
namespace Src\Gateways;

use Src\Common\APITrait;
use Src\Common\Utilities\CommonMethodsTrait;
use Src\Service\PostService;

class PostGateway {
    
    use CommonMethodsTrait;
    use APITrait;

    public function __construct(PostService $postService) {
        $this->requestData = $_REQUEST;
        $this->postService = $postService;
    }

    public function getAvgCharLengthOfPost(): array {
        $this->data = $this->postService->getPosts($this->requestData);
        if (!$this->data['status']) {
            $response = $this->invalidResponse($this->data['error']);
            return $response;
        }
        $result = $this->data;
        $averageCharLengthPostsPerMonth = $this->getAverageCharLengthOfPostsPerMonth($result['data']['posts']);

        $response = $this->validResponse($averageCharLengthPostsPerMonth);
        return $response;
    }

    public function getLongestPostByCharPerMonth(): array {
        $this->data = $this->postService->getPosts($this->requestData);
        if (!$this->data['status']) {
            $response = $this->invalidResponse($this->data['error']);
            return $response;
        }
        $result = $this->data;
        $longestPostPerMonth = $this->getLongestPostPerMonth($result['data']['posts']);
        $response = $this->validResponse($longestPostPerMonth);
        return $response;
    }

    public function getTotalPostsByWeek(): array {
        $this->data = $this->postService->getPosts($this->requestData);
        if (!$this->data['status']) {
            $response = $this->invalidResponse($this->data['error']);
            return $response;
        }
        $result = $this->data;
        
        $totalPostsPerWeek = $this->getTotalPostsByWeek($result['data']['posts']);
        $response = $this->validResponse($totalPostsPerWeek);

        return $response;
    }

    public function getAveragePostsPerUserPerMonth(): array {
        $this->data = $this->postService->getPosts($this->requestData);
        if (!$this->data['status']) {
            $response = $this->invalidResponse($this->data['error']);
            return $response;
        }
        $result = $this->data;

        $totalPostsPerWeek = $this->getAveragePostsByUserPerMonth($result['data']['posts']);
        $response = $this->validResponse($totalPostsPerWeek);
        
        return $response;
    }

}