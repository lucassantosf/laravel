<?php 

// Done at: 2026-XX-XX

// 728. Self Dividing Numbers

// A self-dividing number is a number that is divisible by every digit it contains.

// For example, 128 is a self-dividing number because 128 % 1 == 0, 128 % 2 == 0, and 128 % 8 == 0.
// A self-dividing number is not allowed to contain the digit zero.

// Given two integers left and right, return a list of all the self-dividing numbers in the range [left, right] (both inclusive).

// Example 1:

// Input: left = 1, right = 22
// Output: [1,2,3,4,5,6,7,8,9,11,12,15,22]
// Example 2:

// Input: left = 47, right = 85
// Output: [48,55,66,77]

// Constraints:

// 1 <= left <= right <= 104

class Solution {

    /**
     * @param Integer $left
     * @param Integer $right
     * @return Integer[]
     */
    function selfDividingNumbers_v1($left, $right) {

        $output = [];
        for($i=$left;$i<=$right;$i++){
            $string = (string) $i;

            $mod_zero = true;

            for($j=0;$j<strlen($string);$j++){

                if( $string[$j] > 0 && ( $string % $string[$j] != 0)){
                    $mod_zero = false;
                }

            }

            if($mod_zero && !str_contains($string,"0"))
                $output[] = $i;
        }
        return $output;
    }

    function selfDividingNumbers($left, $right) {

        $output = [];

        for ($i = $left; $i <= $right; $i++) {

            $string = (string) $i;
            $isValid = true;

            $len = strlen($string);

            for ($j = 0; $j < $len; $j++) {

                $digit = (int) $string[$j];

                if ($digit === 0 || $i % $digit !== 0) {
                    $isValid = false;
                    break;
                }
            }

            if ($isValid) {
                $output[] = $i;
            }
        }

        return $output;
    }
}


// Test cases
$solution = new Solution();

$left = 47; $right = 85;    // [48,55,66,77]
$left = 1; $right = 22;     // [1,2,3,4,5,6,7,8,9,11,12,15,22]

var_dump(implode(",",$solution->selfDividingNumbers($left,$right))); 