<?php 

// Done at: 2026-06-01

// 283. Move Zeroes

// Given an integer array nums, move all 0's to the end of it while maintaining the relative order of the non-zero elements.

// Note that you must do this in-place without making a copy of the array.

// Example 1:

// Input: nums = [0,1,0,3,12]
// Output: [1,3,12,0,0]
// Example 2:

// Input: nums = [0]
// Output: [0]

// Constraints:

// 1 <= nums.length <= 104
// -231 <= nums[i] <= 231 - 1

// Follow up: Could you minimize the total number of operations done?

class Solution {

    /**
     * @param Integer[] $nums
     * @return NULL
     */
    function moveZeroes(&$nums) {
        $write = 0;
        $len = count($nums);

        // posicionar
        for ($i = 0; $i < $len; $i++) {

            if ($nums[$i] != 0) {
                $nums[$write] = $nums[$i];
                $write++;
            }

        }

        //preencher restante com zeros
        for ($i = $write; $i < $len; $i++) {
            $nums[$i] = 0;
        }

        return $nums;
    }
}

$nums = [0]; //[0]
$nums = [0,1,0,3,12]; //[1,3,12,0,0]

$solution = new Solution();
$result = $solution->moveZeroes($nums);

var_dump($result);