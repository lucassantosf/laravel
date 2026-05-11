<?php 

// Done at: 2026-05-11

// 557. Reverse Words in a String III

// Given a string s, reverse the order of characters in each word within a sentence while still preserving whitespace and initial word order.

// Example 1:

// Input: s = "Let's take LeetCode contest"
// Output: "s'teL ekat edoCteeL tsetnoc"
// Example 2:

// Input: s = "Mr Ding"
// Output: "rM gniD"

// Constraints:

// 1 <= s.length <= 5 * 104
// s contains printable ASCII characters.
// s does not contain any leading or trailing spaces.
// There is at least one word in s.
// All the words in s are separated by a single space.

class Solution {

    /**
     * @param String $s
     * @return String
     */
    function reverseWords($s) {

        $words = explode(' ', $s);
        $result = [];

        foreach ($words as $word) {

            $reversed = '';

            for ($i = strlen($word) - 1; $i >= 0; $i--) {
                $reversed .= $word[$i];
            }

            $result[] = $reversed;
        }

        return implode(' ', $result);
    }
}

$s = "Let's take LeetCode contest"; // "s'teL ekat edoCteeL tsetnoc"
$s = "Mr Ding"; // "rM gniD"

$solution = new Solution();
$result = $solution->reverseWords($s);

var_dump($result);