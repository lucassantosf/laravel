<?php 

// Done at: 2026-05-28

// 217. Contains Duplicate

// Given an integer array nums, return true if any value appears at least twice in the array, and return false if every element is distinct.

// Example 1:

// Input: nums = [1,2,3,1]

// Output: true

// Explanation:

// The element 1 occurs at the indices 0 and 3.

// Example 2:

// Input: nums = [1,2,3,4]

// Output: false

// Explanation:

// All elements are distinct.

// Example 3:

// Input: nums = [1,1,1,3,3,4,3,2,4,2]

// Output: true

// Constraints:

// 1 <= nums.length <= 105
// -109 <= nums[i] <= 109


class Solution {

    /**
     * @param Integer[] $nums
     * @return Boolean
     */
    function containsDuplicate($nums) {
        $seen = [];

        foreach ($nums as $num) {
            if (isset($seen[$num])) {
                return true;
            }
            $seen[$num] = true;
        }

        return false;
    }
}

$nums = [1,2,3,1]; //true
$nums = [1,2,3,4]; //false
$nums = [1,1,1,3,3,4,3,2,4,2]; //true

$solution = new Solution();
$result = $solution->containsDuplicate($nums);

var_dump($result);