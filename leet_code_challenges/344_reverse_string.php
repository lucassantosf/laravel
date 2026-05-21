<?php 

// Done at: 2026-05-21

// 344. Reverse String

// Write a function that reverses a string. The input string is given as an array of characters s.

// You must do this by modifying the input array in-place with O(1) extra memory.

// Example 1:

// Input: s = ["h","e","l","l","o"]
// Output: ["o","l","l","e","h"]
// Example 2:

// Input: s = ["H","a","n","n","a","h"]
// Output: ["h","a","n","n","a","H"]

// Constraints:

// 1 <= s.length <= 105
// s[i] is a printable ascii character.

class Solution {

    /**
     * @param String[] $s
     * @return NULL
     */
    function reverseString(&$s) {
        $left = 0;
        $right = count($s) - 1;

        while ($left < $right) {

            $temp = $s[$left];

            $s[$left] = $s[$right];
            $s[$right] = $temp;

            $left++;
            $right--;
        }
        return $s;
    }

}

$s = ["h","e","l","l","o"]; //["o","l","l","e","h"]
$s = ["H","a","n","n","a","h"]; //["h","a","n","n","a","H"]

$solution = new Solution();
$result = $solution->reverseString($s);

var_dump(implode("",$result));