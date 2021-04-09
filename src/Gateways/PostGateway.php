<?php
namespace Src\Gateways;

use Src\Common\APITrait;
use Src\Common\Utilities\CommonMethodsTrait;

class PostGateway {


    use APITrait;
    use CommonMethodsTrait;

    public function __construct($requestData)
    {
        $this->requestData = $requestData;
        $this->data = null;
    }

    private function getPosts() {
        $response = $this->callAPI('GET', 'posts', $this->requestData);
        return json_decode($response, true);
    }

    public function getAvgCharLengthOfPost() {
        $result = $this->getPosts();
        $postsByMonth = $this->getPostsByMonth($result['data']['posts']);
        $data = $this->setTotalCharCountOfPostsPerMonth($postsByMonth);
        $longestPostPerMonth = $this->getLongestPostPerMonth($data);
        $dataPerWeek = $this->getPostsByWeek($result['data']['posts']);

        $averagePostsPerMonth = $this->getAverageCharsOfPostsPerMonth($data);
        $postsPerUserPerMonth = $this->getPostsByUserPerMonth($result['data']['posts']);
        $avgPostsPerUserPerMonth = $this->getAveragePostsByUserPerMonth($result['data']['posts']);
        echo "<pre>";
        // print_r($data);
        print_r($avgPostsPerUserPerMonth);
        die;

        if (isset($result['error']) && !empty($result['error'])) {
            return $this->invalidTokenResponse($result['error']);
        }
    }

}