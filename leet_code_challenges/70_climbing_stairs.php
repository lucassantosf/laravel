<?php

// Done at: 2026-02-27

// 70. Climbing Stairs

// You are climbing a staircase. It takes n steps to reach the top.
// Each time you can either climb 1 or 2 steps. In how many distinct ways can you climb to the top?

// Example 1:

// Input: n = 2
// Output: 2
// Explanation: There are two ways to climb to the top.
// 1. 1 step + 1 step
// 2. 2 steps
// Example 2:

// Input: n = 3
// Output: 3
// Explanation: There are three ways to climb to the top.
// 1. 1 step + 1 step + 1 step
// 2. 1 step + 2 steps
// 3. 2 steps + 1 step

// Constraints:

// 1 <= n <= 45

class Solution
{

    /**
     * @param Integer $n
     * @return Integer
     */
    function climbStairs($n)
    {
        $step[1] = 1;
        $step[2] = 2;

        for ($i = 3; $i <= $n; $i++) {
            $step[$i] = $step[$i - 1] + $step[$i - 2];
        }

        return $step[$n];
    }
}

$solution = new Solution();

// $n = 1; //2
// $n = 2; //2
// $n = 3; //3
// $n = 4; //5
// $n = 5; //8
// $n = 6; //13
// $n = 7; //21

$result = $solution->climbStairs($n);
var_dump($result);