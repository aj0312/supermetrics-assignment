<?php

namespace Src\Common\Utilities;

use DateTime;

trait CommonMethodsTrait {

    use ValidationsTrait;
    public function getAverage($sum, $size): float {
        return round(($sum / $size), 2) ;
    }

    public function getPostsByMonth($userPosts = null): array | null {
        if ($userPosts === null)
            return null;
        
        $format = 'Y-m-d\TH:i:sP';
        $postsByMonthAndYear = [];
        foreach ($userPosts as $post) {
            $date = null;
            $date = DateTime::createFromFormat($format, $post['created_time']);
            $month = $date->format('M');
            $year = $date->format('Y');
            $postsByMonthAndYear[$year][$month][] = $post;
        }

        return $postsByMonthAndYear;
    }

    private function setTotalCharCountOfPostsPerMonth ($postsPerMonth = null): array | null {
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

    public function getLongestPostPerMonth(array $posts = null): array | null {
        if (!$this->isArrayValid($posts)) {
             return null;
        }
        $postsPerMonth = $this->getPostsByMonth($posts);
        $postsPerMonth = $this->setTotalCharCountOfPostsPerMonth($postsPerMonth);

        $longestPostInMonth = [];
        foreach($postsPerMonth as $year => $postsInYear) {
            foreach($postsInYear as $month => $postsInMonth) {
                $longestPostInMonth[$year][$month] = $this->getLongestPost($postsInMonth);
            }
        }
        return $longestPostInMonth;
    }
    
    private function getLongestPost($data = null): array | null {
        if (!$this->isArrayValid($data)) {
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

    private function getAverageLengthOfCharacters($data = null, int $count): float | null {
        if (!$this->isArrayValid($data) || !$this->isVariableValid($count) || $count === 0) {
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

        return $this->getAverage($sumOfCharactersOfPosts['char_length'], $count);
    }

    public function getAverageCharLengthOfPostsPerMonth($posts = null): array | null {
        if (!$this->isArrayValid($posts)) {
            return null;
        }
        $postsPerMonth = $this->getPostsByMonth($posts);
        $postsPerMonth = $this->setTotalCharCountOfPostsPerMonth($postsPerMonth);

        $averageCountPerMonth = [];
        $countOfMonths = $this->getCountOfMonths($postsPerMonth);
        foreach ($postsPerMonth as $year => $postsForYear) {
            foreach ($postsForYear as $month => $postsInMonth) {
                $averageCountPerMonth[$year][$month] = $this->getAverageLengthOfCharacters($postsInMonth, $countOfMonths);
            }
        }

        return $averageCountPerMonth;
    }

    public function getPostsByWeek($posts = null): array | null {
        if (!$this->isArrayValid($posts)) {
            return null;
        }
        
        $format = 'Y-m-d\TH:i:sP';
        $userPostsByWeekAndYear = array();
        foreach ($posts as $post) {
            $date = null;
            $date = DateTime::createFromFormat($format, $post['created_time']);
            $weekNo = $date->format('W');
            $year = $date->format('Y');
            $userPostsByWeekAndYear[$year][$weekNo][] = $post;
        }

        return $userPostsByWeekAndYear;
    }

    public function getTotalPostsByWeek($posts = null): array | null {
        if (!$this->isArrayValid($posts)) {
            return null;
        }

        $postsPerWeekInYear = $this->getPostsByWeek($posts);

        $totalPostsPerWeek = [];
        foreach ($postsPerWeekInYear as $year => $postsPerWeek) {
            foreach ($postsPerWeek as $week => $posts) {
                $totalPostsPerWeek[$year][$week] = count($posts);
            }
        }

        return $totalPostsPerWeek;
    }

    public function getPostsOfUser($posts = null): array | null {
        if (!$this->isArrayValid($posts)) {
            return null;
        }

        $postsOfUser = [];
        foreach ($posts as $post) {
            $postsOfUser[$post['from_id']][] = $post;
        }
        return $postsOfUser;
    }

    public function getPostsByUserPerMonth($posts = null): array | null {
        if (!$this->isArrayValid($posts)) {
            return null;
        }
        $postsByMonth = $this->getPostsByMonth($posts);
        $postsPerUserPerMonth = [];
        foreach ($postsByMonth as $year => $postsInYear) {
            foreach ($postsInYear as $month => $postsInMonth) {
                $postsPerUserPerMonth[$year][$month] = $this->getPostsOfUser($postsInMonth);

            }
        }
        return $postsPerUserPerMonth;
    }

    public function getAveragePostsByUserPerMonth($posts = null): array | null {
        if (!$this->isArrayValid($posts)) {
            return null;
        }
        
        $postsPerUser = $this->getPostsOfUser($posts);
        $postsPerUserPerMonth = $postsPerUser;
        foreach ($postsPerUser as $from_id => $posts) {
            $postsPerUserPerMonth[$from_id] = $this->getPostsByMonth($posts);
        }
        $averagePostsPerUserPerMonth = [];
        foreach ($postsPerUserPerMonth as $from_id => $postsPerUser) {
            $totalMonthsForUser = $this->getCountOfMonths($postsPerUser);
            foreach ($postsPerUser as $year => $postsPerYear) {
                foreach ($postsPerYear as $month => $postsPerMonth) {
                    $totalPostsPerMonth = count($postsPerMonth);
                    $averagePostsPerUserPerMonth[$from_id][$year][$month] = $this->getAverage($totalPostsPerMonth, $totalMonthsForUser);
                }
            }
        }

        return $averagePostsPerUserPerMonth;


    }

    private function getCountOfMonths($postsByYearAndMonth = null): int | null {
        if (!$this->isArrayValid($postsByYearAndMonth)) {
            return null;
        }

        $countOfMonths = 0;
        foreach ($postsByYearAndMonth as $postsByMonthInYear) {
            $countOfMonths += count($postsByMonthInYear);
        }

        return $countOfMonths;
    }


}