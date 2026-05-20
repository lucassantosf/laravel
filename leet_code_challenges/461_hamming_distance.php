<?php 

// Done at: 2026-05-20

// 461. Hamming Distance

// The Hamming distance between two integers is the number of positions at which the corresponding bits are different.

// Given two integers x and y, return the Hamming distance between them.

// Example 1:

// Input: x = 1, y = 4
// Output: 2
// Explanation:
// 1   (0 0 0 1)
// 4   (0 1 0 0)
//        ↑   ↑
// The above arrows point to positions where the corresponding bits are different.
// Example 2:

// Input: x = 3, y = 1
// Output: 1

// Constraints:

// 0 <= x, y <= 231 - 1

class Solution {

    /**
     * @param Integer $x
     * @param Integer $y
     * @return Integer
     */
    function hammingDistance_v1($x, $y) {
        $b1 = $this->get_binary($x);
        $b2 = $this->get_binary($y);
        $total = 0;

        $result = $this->equalize_length($b1, $b2);
        $b1 = $result[0];
        $b2 = $result[1];

        for($i=0; $i<strlen($b1);$i++){

            if($b2[$i] != $b1[$i]){
                $total++;
            } 
                
        }

        return $total;
    }

    function equalize_length($b1, $b2) {

        $max = max(strlen($b1), strlen($b2));

        $b1 = str_pad($b1, $max, '0', STR_PAD_LEFT);
        $b2 = str_pad($b2, $max, '0', STR_PAD_LEFT);

        return [$b1, $b2];
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

    // Improved Version 
    function hammingDistance_v2($x, $y) {

        $b1 = decbin($x);
        $b2 = decbin($y);

        $max = max(strlen($b1), strlen($b2));

        $b1 = str_pad($b1, $max, '0', STR_PAD_LEFT);
        $b2 = str_pad($b2, $max, '0', STR_PAD_LEFT);

        $total = 0;

        for ($i = 0; $i < $max; $i++) {

            if ($b1[$i] != $b2[$i]) {
                $total++;
            }

        }

        return $total;
    }

    // Ideal Version 
    function hammingDistance($x, $y) {

        $xor = $x ^ $y;
        $count = 0;

        while ($xor > 0) {

            $count += $xor & 1;
            $xor = $xor >> 1;

        }

        return $count;
    }
}

$x = 1; $y = 4;     // 2
$x = 3; $y = 1;     // 1
$x = 4; $y = 14;    // 3

$solution = new Solution();
$result = $solution->hammingDistance($x,$y);

var_dump($result);