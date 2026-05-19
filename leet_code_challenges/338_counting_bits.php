<?php 

// Done at: 2026-05-19

// 338. Counting Bits

// Given an integer n, return an array ans of length n + 1 such that for each i (0 <= i <= n), ans[i] is the number of 1's in the binary representation of i.

// Example 1:

// Input: n = 2
// Output: [0,1,1]
// Explanation:
// 0 --> 0
// 1 --> 1
// 2 --> 10
// Example 2:

// Input: n = 5
// Output: [0,1,1,2,1,2]
// Explanation:
// 0 --> 0
// 1 --> 1
// 2 --> 10
// 3 --> 11
// 4 --> 100
// 5 --> 101

// Constraints:

// 0 <= n <= 105

class Solution {

    /**
     * @param Integer $n
     * @return Integer[]
     */
    function countBits($n){
        
        $total = 0;
        $arr   = [];
        for($i=0;$i<=$n;$i++){
            $binary = $this->get_binary($i);

            for($x=0;$x<strlen($binary);$x++){

                if($binary[$x] == '1')
                    $total++;

            }
            $arr[] = $total;
            $total = 0;
        }
        return $arr;
    
    }

    function get_binary($num) {
        $negative = $num < 0;
        $num = abs($num);

        $string = '';

        if ($num == 0) {
            return "0";
        }

        while ($num > 0) {
            $resto = $num % 2;
            $string .= (string) $resto;
            $num = intdiv($num, 2);
        }

        $final = strrev($string);

        if ($negative) {
            $final = "-$final";
        }

        return $final;
    }
}

$n = 5; //[0,1,1,2,1,2]
// $n = 2; //[0,1,1]

$solution = new Solution();
$result = $solution->countBits($n);

var_dump($result);