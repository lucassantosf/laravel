<?php 

// Done at: 2026-05-11

// 383. Ransom Note

// Given two strings ransomNote and magazine, return true if ransomNote can be constructed by using the letters from magazine and false otherwise.

// Each letter in magazine can only be used once in ransomNote.

// Example 1:

// Input: ransomNote = "a", magazine = "b"
// Output: false
// Example 2:

// Input: ransomNote = "aa", magazine = "ab"
// Output: false
// Example 3:

// Input: ransomNote = "aa", magazine = "aab"
// Output: true

// Constraints:

// 1 <= ransomNote.length, magazine.length <= 105
// ransomNote and magazine consist of lowercase English letters.

class Solution {

    /**
     * @param String $ransomNote
     * @param String $magazine
     * @return Boolean
     */
    function canConstruct_v1($ransomNote, $magazine) {
        
        $can_construct = true;
        $dic_1 = [];
        $dic_2 = [];

        for($i=0;$i<strlen($ransomNote);$i++){
            if(!isset($dic_1[$ransomNote[$i]])){
                $dic_1[$ransomNote[$i]] = 1;
            }else{
                $dic_1[$ransomNote[$i]] += 1;
            }
        }

        for($i=0;$i<strlen($magazine);$i++){
            if(!isset($dic_2[$magazine[$i]])){
                $dic_2[$magazine[$i]] = 1;
            }else{
                $dic_2[$magazine[$i]] += 1;
            }
        }

        foreach($dic_1 as $key=>$value){

            if(!isset($dic_2[$key]) || $dic_2[$key] < $value)
                $can_construct = false;

        }


        return $can_construct;
    }

    function canConstruct($ransomNote, $magazine) {

        $freq = [];

        for ($i = 0; $i < strlen($magazine); $i++) {
            $char = $magazine[$i];
            $freq[$char] = ($freq[$char] ?? 0) + 1;
        }

        for ($i = 0; $i < strlen($ransomNote); $i++) {

            $char = $ransomNote[$i];

            if (($freq[$char] ?? 0) <= 0) {
                return false;
            }

            $freq[$char]--;
        }

        return true;
    }
}

$ransomNote = "a"; $magazine = "b";         // false
$ransomNote = "aa"; $magazine = "ab";       // false
$ransomNote = "aa"; $magazine = "aab";      // true

$solution = new Solution();
$result = $solution->canConstruct($ransomNote,$magazine);

var_dump($result);