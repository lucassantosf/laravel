<?php 

// Done at: 2026-XX-XX

// 509. Fibonacci Number

// The Fibonacci numbers, commonly denoted F(n) form a sequence, called the Fibonacci sequence, such that each number is the sum of the two preceding ones, starting from 0 and 1. That is,

// F(0) = 0, F(1) = 1
// F(n) = F(n - 1) + F(n - 2), for n > 1.
// Given n, calculate F(n).

// Example 1:

// Input: n = 2
// Output: 1
// Explanation: F(2) = F(1) + F(0) = 1 + 0 = 1.
// Example 2:

// Input: n = 3
// Output: 2
// Explanation: F(3) = F(2) + F(1) = 1 + 1 = 2.
// Example 3:

// Input: n = 4
// Output: 3
// Explanation: F(4) = F(3) + F(2) = 2 + 1 = 3.

// Constraints:

// 0 <= n <= 30

class Solution {

    /**
     * @param Integer $n
     * @return Integer
     */
    function fib_v1($n) {
        $f[0] = 0;
        $f[1] = 1;
        for($i=2;$i<=$n;$i++){
            $f[$i] = $f[$i-1] + $f[$i-2]; 
        }
        return $f[$n];
    }

    function fib($n) {

        if ($n <= 1) {
            return $n;
        }

        $prev2 = 0;
        $prev1 = 1;

        for ($i = 2; $i <= $n; $i++) {
            $current = $prev1 + $prev2;
            $prev2 = $prev1;
            $prev1 = $current;
        }

        return $prev1;
    }
}

// Test cases
$solution = new Solution();

$n = 2;     //1
$n = 3;     //2
$n = 4;     //3
$n = 44;    //701408733

var_dump($solution->fib($n));  