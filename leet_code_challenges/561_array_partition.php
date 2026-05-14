<?php 

// Done at: 2026-05-13

// 561. Array Partition

// Given an integer array nums of 2n integers, group these integers into n pairs (a1, b1), (a2, b2), ..., (an, bn) such that the sum of min(ai, bi) for all i is maximized. Return the maximized sum.

// Example 1:

// Input: nums = [1,4,3,2]
// Output: 4
// Explanation: All possible pairings (ignoring the ordering of elements) are:
// 1. (1, 4), (2, 3) -> min(1, 4) + min(2, 3) = 1 + 2 = 3
// 2. (1, 3), (2, 4) -> min(1, 3) + min(2, 4) = 1 + 2 = 3
// 3. (1, 2), (3, 4) -> min(1, 2) + min(3, 4) = 1 + 3 = 4
// So the maximum possible sum is 4.
// Example 2:

// Input: nums = [6,2,6,5,1,2]
// Output: 9
// Explanation: The optimal pairing is (2, 1), (2, 5), (6, 6). min(2, 1) + min(2, 5) + min(6, 6) = 1 + 2 + 6 = 9.

// Constraints:

// 1 <= n <= 104
// nums.length == 2 * n
// -104 <= nums[i] <= 104

class Solution {

    /**
     * @param Integer[] $nums
     * @return Integer
     */
    function arrayPairSum_v1($nums) {

        sort($nums);
        $mins = [];
        
        for($i=0;$i<count($nums);$i+=2){
            
            $min = $nums[$i] >= $nums[$i+1] ? $nums[$i+1] : $nums[$i];
            $max = $nums[$i] <= $nums[$i+1] ? $nums[$i] : $nums[$i+1];

            $mins[] = $min;
        }

        return array_sum($mins);
    }

    function arrayPairSum($nums) {

        sort($nums);

        $sum = 0;

        for ($i = 0; $i < count($nums); $i += 2) {
            $sum += $nums[$i];
        }

        return $sum;
    }
}

$nums = [1,4,3,2]; // 4
$nums = [6,2,6,5,1,2]; // 9

$solution = new Solution();
$result = $solution->arrayPairSum($nums);

var_dump($result);