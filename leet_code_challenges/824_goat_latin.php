<?php 

// Done at: 2026-10-29

// 824. Goat Latin

// You are given a string sentence that consist of words separated by spaces. Each word consists of lowercase and uppercase letters only.

// We would like to convert the sentence to "Goat Latin" (a made-up language similar to Pig Latin.) The rules of Goat Latin are as follows:

// If a word begins with a vowel ('a', 'e', 'i', 'o', or 'u'), append "ma" to the end of the word.
// For example, the word "apple" becomes "applema".
// If a word begins with a consonant (i.e., not a vowel), remove the first letter and append it to the end, then add "ma".
// For example, the word "goat" becomes "oatgma".
// Add one letter 'a' to the end of each word per its word index in the sentence, starting with 1.
// For example, the first word gets "a" added to the end, the second word gets "aa" added to the end, and so on.
// Return the final sentence representing the conversion from sentence to Goat Latin.

// Example 1:

// Input: sentence = "I speak Goat Latin"
// Output: "Imaa peaksmaaa oatGmaaaa atinLmaaaaa"
// Example 2:

// Input: sentence = "The quick brown fox jumped over the lazy dog"
// Output: "heTmaa uickqmaaa rownbmaaaa oxfmaaaaa umpedjmaaaaaa overmaaaaaaa hetmaaaaaaaa azylmaaaaaaaaa ogdmaaaaaaaaaa"

// Constraints:

// 1 <= sentence.length <= 150
// sentence consists of English letters and spaces.
// sentence has no leading or trailing spaces.
// All the words in sentence are separated by a single space.

class Solution {

    /**
     * @param String $sentence
     * @return String
     */
    function toGoatLatin_v1($sentence) {
        
        $words = explode(" ",$sentence);
        $end = 'a';
        $return = '';

        for($i=0;$i<count($words);$i++){

            $word = $words[$i];
            $letter = $word[0];

            if(in_array( strtolower($word[0]), ['a','e','i','o','u'])){
                $return .= "{$word}ma";
            }else{
                $_ = substr($word, 1);
                $return .= "{$_}{$letter}ma";
            }

            $return .= "{$end}";

            if($i!=count($words)){
                $return .= " ";
            }

            $end .= "a";

        }
        return trim($return);
    }

    function toGoatLatin($sentence) {
        $words = explode(' ', $sentence);
        $vowels = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'];
        $result = [];

        foreach ($words as $i => $word) {
            if (!in_array($word[0], $vowels)) {
                $word = substr($word, 1) . $word[0];
            }
            $result[] = $word . 'ma' . str_repeat('a', $i + 1);
        }

        return implode(' ', $result);
    }
}

// Test cases
$solution = new Solution();

$sentence = "I speak Goat Latin"; // "Imaa peaksmaaa oatGmaaaa atinLmaaaaa"
$sentence2 = "The quick brown fox jumped over the lazy dog"; // "heTmaa uickqmaaa rownbmaaaa oxfmaaaaa umpedjmaaaaaa overmaaaaaaa hetmaaaaaaaa azylmaaaaaaaaa ogdmaaaaaaaaaa"

var_dump($solution->toGoatLatin($sentence)); 
var_dump($solution->toGoatLatin($sentence2)); 