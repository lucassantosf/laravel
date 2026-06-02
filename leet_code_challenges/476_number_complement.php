<?php 

// Done at: 2026-06-02

// 476. Number Complement

// The complement of an integer is the integer you get when you flip all the 0's to 1's and all the 1's to 0's in its binary representation.

// For example, The integer 5 is "101" in binary and its complement is "010" which is the integer 2.
// Given an integer num, return its complement.

// Example 1:

// Input: num = 5
// Output: 2
// Explanation: The binary representation of 5 is 101 (no leading zero bits), and its complement is 010. So you need to output 2.
// Example 2:

// Input: num = 1
// Output: 0
// Explanation: The binary representation of 1 is 1 (no leading zero bits), and its complement is 0. So you need to output 0.

// Constraints:

// 1 <= num < 231

class Solution {

    /**
     * @param Integer $num
     * @return Integer
     */
    function findComplement($num) {

        $binary = $this->get_binary($num);
        $complement = '';

        $length = strlen($binary);

        for ($i = 0; $i < $length; $i++) {
            $complement .= $binary[$i] === '0' ? '1' : '0';
        }

        return $this->binaryToDecimal($complement);
    }

    function binaryToDecimal(string $binary): int
    {
        $decimal = 0;
        $length = strlen($binary);

        for ($i = 0; $i < $length; $i++) {
            $decimal = ($decimal * 2) + (int)$binary[$i];
        }

        return $decimal;
    }

    function get_binary($num) {
        $binary = '';

        while ($num > 0) {
            $binary .= $num % 2;
            $num = intdiv($num, 2);
        }

        return strrev($binary);
    }
}



$num = 5 ; // 2
$num = 1 ; // 0

$solution = new Solution();
$result = $solution->findComplement($num);

var_dump($result);
