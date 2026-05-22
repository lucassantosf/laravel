<?php 

// Done at: 2026-05-22

// 345. Reverse Vowels of a String

// Given a string s, reverse only all the vowels in the string and return it.

// The vowels are 'a', 'e', 'i', 'o', and 'u', and they can appear in both lower and upper cases, more than once.

// Example 1:

// Input: s = "IceCreAm"

// Output: "AceCreIm"

// Explanation:

// The vowels in s are ['I', 'e', 'e', 'A']. On reversing the vowels, s becomes "AceCreIm".

// Example 2:

// Input: s = "leetcode"

// Output: "leotcede"

// Constraints:

// 1 <= s.length <= 3 * 105
// s consist of printable ASCII characters.

class Solution {

    /**
     * @param String $s
     * @return String
     */
    function reverseVowels_v1($s) {
        
        $vowels = ['a','e','i','o','u'];
        $arr = [];

        #mapping vowels
        for($i=0;$i<strlen($s);$i++){
            if(in_array(strtolower($s[$i]),$vowels)){
                $arr[] = $s[$i];
            }
        }


        #change positions on arr temp 
        $left = 0;
        $right = count($arr) - 1;

        while ($left < $right) {

            $temp = $arr[$left];

            $arr[$left] = $arr[$right];
            $arr[$right] = $temp;

            $left++;
            $right--;
        }

        #replacing
        $return = '';
        $used=0;
        for($i=0;$i<strlen($s);$i++){
            if(in_array(strtolower($s[$i]),$vowels)){
                $return.= $arr[$used];
                $used++;
            }else{
                $return.= $s[$i];
            }
        }

        return $return;
    }

    function reverseVowels($s) {
        $vowels = "aeiouAEIOU";

        $left = 0;
        $right = strlen($s) - 1;

        while ($left < $right) {

            while (
                $left < $right &&
                strpos($vowels, $s[$left]) === false
            ) {
                $left++;
            }

            while (
                $left < $right &&
                strpos($vowels, $s[$right]) === false
            ) {
                $right--;
            }

            $temp = $s[$left];
            $s[$left] = $s[$right];
            $s[$right] = $temp;

            $left++;
            $right--;
        }

        return $s;
    }
}

$s = "leetcode";//leotcede
$s = "IceCreAm";//AceCreIm

$solution = new Solution();
$result = $solution->reverseVowels($s);

var_dump($result);