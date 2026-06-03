<?php 

// Done at: 2026-06-03

// 448. Find All Numbers Disappeared in an Array

// Given an array nums of n integers where nums[i] is in the range [1, n], return an array of all the integers in the range [1, n] that do not appear in nums.

// Example 1:

// Input: nums = [4,3,2,7,8,2,3,1]
// Output: [5,6]
// Example 2:

// Input: nums = [1,1]
// Output: [2]

// Constraints:

// n == nums.length
// 1 <= n <= 105
// 1 <= nums[i] <= n

// Follow up: Could you do it without extra space and in O(n) runtime? You may assume the returned list does not count as extra space.

class Solution {

    /**
     * @param Integer[] $nums
     * @return Integer[]
     */
    function findDisappearedNumbers($nums) {

        $exists = [];
        $result = [];

        foreach ($nums as $num) {
            $exists[$num] = true;
        }

        $n = count($nums);

        for ($i = 1; $i <= $n; $i++) {
            if (!isset($exists[$i])) {
                $result[] = $i;
            }
        }

        return $result;
    }
}

$nums = [1,1] ; //[2]
$nums = [4,3,2,7,8,2,3,1] ; //[5,6]

$solution = new Solution();
$result = $solution->findDisappearedNumbers($nums);

var_dump($result);
