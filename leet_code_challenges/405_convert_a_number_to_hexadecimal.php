<?php 

// Done at: 2026-05-06

// 405. Convert a Number to Hexadecimal

// Given a 32-bit integer num, return a string representing its hexadecimal representation. For negative integers, two’s complement method is used.

// All the letters in the answer string should be lowercase characters, and there should not be any leading zeros in the answer except for the zero itself.

// Note: You are not allowed to use any built-in library method to directly solve this problem.

// Example 1:

// Input: num = 26
// Output: "1a"
// Example 2:

// Input: num = -1
// Output: "ffffffff"

// Constraints:

// -231 <= num <= 231 - 1

class Solution {

    /**
     * @param Integer $num
     * @return String
     */
    function toHex($num) {

        if ($num == 0) {
            return "0";
        }

        $arr[0]='0';
        $arr[1]='1';
        $arr[2]='2';
        $arr[3]='3';
        $arr[4]='4';
        $arr[5]='5';
        $arr[6]='6';
        $arr[7]='7';
        $arr[8]='8';
        $arr[9]='9';
        $arr[10]='a';
        $arr[11]='b';
        $arr[12]='c';
        $arr[13]='d';
        $arr[14]='e';
        $arr[15]='f';

        $string = '';
        $count = 0;

        while($num != 0 && $count < 8){
            $resto = $num & 15;
            $string .= $arr[$resto];
            $num = $num >> 4;
            $count++;
        }

        return strrev($string);
    }
}

$num = -1; //-ffffffff
$num = 26; //1a

$solution = new Solution();
$result = $solution->toHex($num);


var_dump($result);