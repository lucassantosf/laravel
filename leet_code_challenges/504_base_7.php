<?php 

// Done at: 2026-04-29

// 504. Base 7

// Given an integer num, return a string of its base 7 representation.

// Example 1:

// Input: num = 100
// Output: "202"
// Example 2:

// Input: num = -7
// Output: "-10"

// Constraints:

// -107 <= num <= 107

class Solution {

    /**
     * @param Integer $num
     * @return String
     */
    function convertToBase7_v1($num) {
        $negative = $num < 0 ? true : false;
        $num_ = abs($num);
        $string = '';

        $resto = $num % 7 ;
        $divisao = $num / 7;
        $string .= (string) $resto;

        while(intval($divisao) != 0){
            $resto = $divisao % 7 ;
            $divisao = $divisao / 7;
            $string.= (string) abs($resto);
        }

        $string = preg_replace('/[^0-9]/', '', $string);
        $final = strrev($string);

        if($negative)
          $final = "-$final";

        return $final;
    }

    function convertToBase7($num) {
        $negative = $num < 0;
        $num = abs($num);

        $string = '';

        if ($num == 0) {
            return "0";
        }

        while ($num > 0) {
            $resto = $num % 7;
            $string .= (string) $resto;
            $num = intdiv($num, 7);
        }

        $final = strrev($string);

        if ($negative) {
            $final = "-$final";
        }

        return $final;
    }
}

$num = 100; //202
$num = -7; //-10

$solution = new Solution();
$result = $solution->convertToBase7($num);


var_dump($result);