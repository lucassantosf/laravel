<?php 

// Done at: 2026-04-21

// 268. Missing Number

// Given an array nums containing n distinct numbers in the range [0, n], return the only number in the range that is missing from the array.

// Example 1:

// Input: nums = [3,0,1]

// Output: 2

// Explanation:

// n = 3 since there are 3 numbers, so all numbers are in the range [0,3]. 2 is the missing number in the range since it does not appear in nums.

// Example 2:

// Input: nums = [0,1]

// Output: 2

// Explanation:

// n = 2 since there are 2 numbers, so all numbers are in the range [0,2]. 2 is the missing number in the range since it does not appear in nums.

// Example 3:

// Input: nums = [9,6,4,2,3,5,7,0,1]

// Output: 8

// Explanation:

// n = 9 since there are 9 numbers, so all numbers are in the range [0,9]. 8 is the missing number in the range since it does not appear in nums.

class Solution {

    /**
     * @param Integer[] $nums
     * @return Integer
     */
    function missingNumber_v1($nums) {
        $length = count($nums);
        $missing = null;
        for($i=0;$i<=$length;$i++){
            if(!in_array($i,$nums)){
                $missing = $i;
            }
        }
        return $missing;
    }

    function missingNumber_v2($nums) {
        sort($nums);

        for($i=0;$i<=count($nums);$i++){
            if($i != $nums[$i])
                return $i;
        }

        return null;
    }

    function missingNumber($nums) {
        $n = count($nums);

        $expected = ($n * ($n + 1)) / 2;
        $actual = 0;

        for ($i = 0; $i < $n; $i++) {
            $actual += $nums[$i];
        }

        return $expected - $actual;
    }
}

$n = [3,0,1];               // 2
$n = [0,1];                 // 2
$n = [9,6,4,2,3,5,7,0,1];   // 8

$solution = new Solution();
$result = $solution->missingNumber($n);
var_dump($result);