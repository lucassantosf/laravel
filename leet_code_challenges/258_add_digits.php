<?php 

// Done at: 2026-04-15

// 258. Add Digits

// Given an integer num, repeatedly add all its digits until the result has only one digit, and return it.

// Example 1:

// Input: num = 38
// Output: 2
// Explanation: The process is
// 38 --> 3 + 8 --> 11
// 11 --> 1 + 1 --> 2 
// Since 2 has only one digit, return it.
// Example 2:

// Input: num = 0
// Output: 0

// Constraints:

// 0 <= num <= 231 - 1

// Follow up: Could you do it without any loop/recursion in O(1) runtime?

class Solution {

    /**
     * @param Integer $num
     * @return Integer
     */
    function addDigits_v1($num) {
        $string = (string) $num;

        while(strlen($string) > 1) {
            $sum = 0;
            for($i=0; $i < strlen($string); $i++){
                $sum += $string[$i];
            }
            $string = (string) $sum;
        } 

        return $string;
    }

    // version requested by LeetCode
    function addDigits($num) {
        if ($num == 0) return 0;
        return 1 + ($num - 1) % 9;
    }
}

$n = 38;

$solution = new Solution();
$result = $solution->addDigits($n);
var_dump($result);