<?php 

// Done at: 2026-08-31

// 1189. Maximum Number of Balloons

// Given a string text, you want to use the characters of text to form as many instances of the word "balloon" as possible.
// You can use each character in text at most once. Return the maximum number of instances that can be formed.

// Example 1:

// Input: text = "nlaebolko"
// Output: 1
// Example 2:

// Input: text = "loonbalxballpoon"
// Output: 2
// Example 3:

// Input: text = "leetcode"
// Output: 0

// Constraints:

// 1 <= text.length <= 104
// text consists of lower case English letters only.

class Solution {

    /**
     * @param String $text
     * @return Integer
     */
    function maxNumberOfBalloons($text) {

        $target = $this->count_letters('balloon');
        $final = $this->count_letters($text);
       
        $minimo = PHP_INT_MAX;

        foreach ($target as $letra => $qtd) {
            $disponivel = $final[$letra] ?? 0;
            
            // Se não tiver nem o mínimo de uma letra, já corta e retorna 0
            if ($disponivel < $qtd) {
                return 0;
            }

            $minimo = min($minimo, intdiv($disponivel, $qtd));
        }

        return $minimo === PHP_INT_MAX ? 0 : $minimo; 
         
    }

    function count_letters($t){
        $return = [];
        for($j=0;$j<strlen($t);$j++){
            if(!isset($return[$t[$j]])){
                $return[$t[$j]] = 1;
            }else{
                $return[$t[$j]] += 1;
            } 
        }
        return $return;
    }

}

// Test cases
$solution = new Solution();

$text1 = "nlaebolko"; // 1
$text2 = "loonbalxballpoon"; // 2
$text3 = "leetcode"; //0
$text4 = "balllllllllllloooooooooon"; //1

var_dump($solution->maxNumberOfBalloons($text1));  
var_dump($solution->maxNumberOfBalloons($text2));  
var_dump($solution->maxNumberOfBalloons($text3));  
var_dump($solution->maxNumberOfBalloons($text4));  