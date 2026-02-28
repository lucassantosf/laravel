<?php 

// Done at: 2026-02-11

// 9. Palindrome Number
// Given an integer x, return true if x is a palindrome, and false otherwise.

// Example 1:

// Input: x = 121
// Output: true
// Explanation: 121 reads as 121 from left to right and from right to left.
// Example 2:

// Input: x = -121
// Output: false
// Explanation: From left to right, it reads -121. From right to left, it becomes 121-. Therefore it is not a palindrome.
// Example 3:

// Input: x = 10
// Output: false
// Explanation: Reads 01 from right to left. Therefore it is not a palindrome.

// Constraints:

// -231 <= x <= 231 - 1

// Follow up: Could you solve it without converting the integer to a string?
// $x = -323;

class Solution {

    function isPalindrome($x) {
        if($x < 0)
            return false;

        $numbers = str_split($x);

        $sum_forward = 0;
        $sum_backward = 0;

        $x = 1;

        for ($i=count($numbers)-1; $i >= 0  ; $i--) {
            $number = (int) $numbers[$i];
            $sum_forward += ($x*$number);     
            $x = $x * 10;
        }

        $y = 1;

        for ($i=0; $i < count($numbers) ; $i++) { 
            $number = (int) $numbers[$i];
            $sum_backward += $y*$number;     
            $y = $y * 10;
        }

        if($sum_forward == $sum_backward){
            return true ;
        }else{
            return false ;
        } 
    }
}