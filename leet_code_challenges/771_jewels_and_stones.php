<?php 

// Done at: 2026-06-11

// 771. Jewels and Stones

// You're given strings jewels representing the types of stones that are jewels, and stones representing the stones you have. Each character in stones is a type of stone you have. You want to know how many of the stones you have are also jewels.

// Letters are case sensitive, so "a" is considered a different type of stone from "A".

// Example 1:

// Input: jewels = "aA", stones = "aAAbbbb"
// Output: 3
// Example 2:

// Input: jewels = "z", stones = "ZZ"
// Output: 0

// Constraints:

// 1 <= jewels.length, stones.length <= 50
// jewels and stones consist of only English letters.
// All the characters of jewels are unique.

class Solution {

    /**
     * @param String $jewels
     * @param String $stones
     * @return Integer
     */
    function numJewelsInStones_v1($jewels, $stones) {
        $total = 0;

        for($i=0;$i<strlen($jewels);$i++){

            for($j=0;$j<strlen($stones);$j++){
                if($jewels[$i]==$stones[$j]){
                    $total++;
                }
            }

        }
        return $total;
    }

    function numJewelsInStones(string $jewels, string $stones): int
    {
        $jewelSet = [];

        foreach (str_split($jewels) as $jewel) {
            $jewelSet[$jewel] = true;
        }

        $total = 0;

        foreach (str_split($stones) as $stone) {
            if (isset($jewelSet[$stone])) {
                $total++;
            }
        }

        return $total;
    }
}

// Test cases
$solution = new Solution();

$jewels = "aA"; $stones = "aAAbbbb";

var_dump($solution->numJewelsInStones($jewels,$stones)); // 3