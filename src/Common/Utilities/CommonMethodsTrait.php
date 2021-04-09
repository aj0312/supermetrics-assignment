<?php

namespace Src\Common\Utilities;

use DateTime;

trait CommonMethodsTrait {

    public function getAverage($sum, $size): float {
        return ceil($sum / $size) ;
    }

    public function getPostsByMonth($userPosts = null): array | null {
        if ($userPosts === null)
            return null;
        
        $format = 'Y-m-d\TH:i:sP';
        $userPostsByMonthAndYear = array();
        foreach ($userPosts as $post) {
            $date = null;
            $date = DateTime::createFromFormat($format, $post['created_time']);
            $month = $date->format('M');
            $year = $date->format('Y');
            $userPostsByMonthAndYear[$year][$month][] = $post;
        }

        return $userPostsByMonthAndYear;
    }

    public function setTotalCharCountOfPostsPerMonth ($postsPerMonth = null): array | null {
        if ($postsPerMonth === null) {
            return null;
        }

        foreach($postsPerMonth as $year => $postsInYear) {
            foreach($postsInYear as $month => $postsInMonth) {
                foreach ($postsInMonth as $key => $post) {
                    $charLength = strlen($post['message']);
                    $postsPerMonth[$year][$month][$key]['char_length'] = $charLength;
                }
            }
        }
        return $postsPerMonth;
    }

    public function getLongestPostPerMonth(array $postsPerMonth = null): array | null {
        if ($postsPerMonth === null) {
             return null;
        }

        $longestPostInMonth = [];
        foreach($postsPerMonth as $year => $postsInYear) {
            foreach($postsInYear as $month => $postsInMonth) {
                $longestPostInMonth[$year][$month] = $this->getLongestPost($postsInMonth);
            }
        }
        return $longestPostInMonth;
    }
    
    public function getLongestPost($data = null): array | null {
        if ($data === null) {
            return null;
        }
        $longestPost = array_reduce($data, function ($carry, $item) {
            if ($carry === null) {
                $carry['char_length'] = 0;
                return $carry;
            }
            $max = $carry['char_length'] > $item['char_length'] ? $carry : $item;
            return $max;
        });
        return $longestPost;
    }

    public function getAverageLength($data = null): float | null {
        if ($data === null) {
            return null;
        }

        $sumOfCharactersOfPosts = array_reduce($data, function ($carry, $item) {
            if ($carry === null) {
                $carry['char_length'] = 0;
                return $carry;
            }
            $carry['char_length'] += $item['char_length'];
            return $carry;
        });

        return $this->getAverage($sumOfCharactersOfPosts['char_length'], count($data));
    }

    public function getAverageCharsOfPostsPerMonth($postsPerMonth = null): array | null {
        if ($postsPerMonth === null) {
            return null;
        }

        $averageCountPerMonth = [];
        foreach ($postsPerMonth as $year => $postsForYear) {
            foreach ($postsForYear as $month => $postsInMonth) {
                $averageCountPerMonth[$year][$month] = $this->getAverageLength($postsInMonth);
            }
        }

        return $averageCountPerMonth;
    }

    public function getPostsByWeek($userPosts = null): array | null {
        if ($userPosts === null)
            return null;
        
        $format = 'Y-m-d\TH:i:sP';
        $userPostsByWeekAndYear = array();
        foreach ($userPosts as $post) {
            $date = null;
            $date = DateTime::createFromFormat($format, $post['created_time']);
            $weekNo = $date->format('W');
            $year = $date->format('Y');
            $userPostsByWeekAndYear['weekData'][$year][$weekNo][] = $post;
        }

        return $userPostsByWeekAndYear;
    }

    public function getPostsOfUser($data = null): array | null {
        if ($data === null) {
            return null;
        }

        $postsOfUser = [];
        foreach ($data as $post) {
            $postsOfUser[$post['from_id']][] = $post;
        }
        return $postsOfUser;
    }

    public function getPostsByUserPerMonth($data = null): array | null {
        if ($data === null) {
            return null;
        }
        $postsByMonth = $this->getPostsByMonth($data);
        $postsPerUserPerMonth = [];
        foreach ($postsByMonth as $year => $postsInYear) {
            foreach ($postsInYear as $month => $postsInMonth) {
                $postsPerUserPerMonth[$year][$month] = $this->getPostsOfUser($postsInMonth);

            }
        }
        return $postsPerUserPerMonth;
    }

    public function getAveragePostsByUserPerMonth($totalPosts = null): array | null {
        if ($totalPosts === null) {
            return null;
        }
        
        $postsPerUser = $this->getPostsOfUser($totalPosts);
        $postsPerUserPerMonth = $postsPerUser;
        $totalPostsPerUser = [];
        foreach ($postsPerUser as $from_id => $posts) {
            $totalPostsPerUser[$from_id] = count($posts);
            $postsPerUserPerMonth[$from_id] = $this->getPostsByMonth($posts);
        }
        $averagePostsPerUserPerMonth = [];

        foreach ($postsPerUserPerMonth as $from_id => $postsPerUser) {
            foreach ($postsPerUser as $year => $postsPerYear) {
                foreach ($postsPerYear as $month => $postsPerMonth) {
                    $totalPostsPerMonth = count($postsPerMonth);
                    $averagePostsPerUserPerMonth[$from_id][$year][$month] = $this->getAverage($totalPostsPerMonth, $totalPostsPerUser[$from_id]);
                }
            }
        }

        return $averagePostsPerUserPerMonth;


    }


}