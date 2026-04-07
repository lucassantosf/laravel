<?php 

// Done at: 2026-04-07

// 242. Valid Anagram

// Given two strings s and t, return true if t is an anagram of s, and false otherwise.

// Example 1:

// Input: s = "anagram", t = "nagaram"

// Output: true

// Example 2:

// Input: s = "rat", t = "car"

// Output: false

// Constraints:

// 1 <= s.length, t.length <= 5 * 104
// s and t consist of lowercase English letters.

// Follow up: What if the inputs contain Unicode characters? How would you adapt your solution to such a case?

class Solution {

    /**
     * @param String $s
     * @param String $t
     * @return Boolean
     */
    function isAnagram($s, $t) {
        if (strlen($s) != strlen($t)) {
            return false;
        }

        $temp = [];

        // Conta caracteres de s
        for ($i = 0; $i < strlen($s); $i++) {
            if (!isset($temp[$s[$i]])) {
                $temp[$s[$i]] = 1;
            } else {
                $temp[$s[$i]]++;
            }
        }

        // Subtrai usando t
        for ($i = 0; $i < strlen($t); $i++) {
            if (!isset($temp[$t[$i]])) {
                return false; // letra não existe em s
            }

            $temp[$t[$i]]--;

            if ($temp[$t[$i]] < 0) {
                return false; // tem mais ocorrências em t
            }
        }

        return true;
    }
}

$s = "anagram"; $t = "nagaram";
$s = "rat"; $t = "car";
$s = "ggii"; $t = "eekk";

$solution = new Solution();
$result = $solution->isAnagram($s,$t);
var_dump($result);