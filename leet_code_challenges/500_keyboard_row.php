<?php 

// Done at: 2026-04-28

// 500. Keyboard Row

// Given an array of strings words, return the words that can be typed using letters of the alphabet on only one row of American keyboard like the image below.

// Note that the strings are case-insensitive, both lowercased and uppercased of the same letter are treated as if they are at the same row.

// In the American keyboard:

// the first row consists of the characters "qwertyuiop",
// the second row consists of the characters "asdfghjkl", and
// the third row consists of the characters "zxcvbnm".

// Example 1:

// Input: words = ["Hello","Alaska","Dad","Peace"]

// Output: ["Alaska","Dad"]

// Explanation:

// Both "a" and "A" are in the 2nd row of the American keyboard due to case insensitivity.

// Example 2:

// Input: words = ["omk"]

// Output: []

// Example 3:

// Input: words = ["adsdf","sfd"]

// Output: ["adsdf","sfd"]

// Constraints:

// 1 <= words.length <= 20
// 1 <= words[i].length <= 100
// words[i] consists of English letters (both lowercase and uppercase). 

class Solution {

    /**
     * @param String[] $words
     * @return String[]
     */
    function findWords_v1($words) {

        $row1 = "qwertyuiop";
        $row2 = "asdfghjkl";
        $row3 = "zxcvbnm";

        $contains = [];

        foreach($words as $word_){
            $word = strtolower($word_);

            $row_1 = false;
            $row_2 = false;
            $row_3 = false;

            for($i=0;$i<strlen($word);$i++){

                if(!str_contains($row1,$word[$i]))  {
                    $row_1 = false;break;              

                }

                $row_1 = true;
            }

            for($i=0;$i<strlen($word);$i++){    

                if(!str_contains($row2,$word[$i]))  {
                    $row_2 = false;break;              

                }

                $row_2 = true;
            }

            for($i=0;$i<strlen($word);$i++){    

                if(!str_contains($row3,$word[$i]))  {
                    $row_3 = false;break;   
                }

                $row_3 = true;
            }

            if($row_1 || $row_2 || $row_3)
                $contains[] = $word_;

        }
        
        return $contains;
    }

    function findWords_v2($words) {

        $rows = [
            "qwertyuiop",
            "asdfghjkl",
            "zxcvbnm"
        ];

        $result = [];

        foreach ($words as $originalWord) {
            $word = strtolower($originalWord);
            $len = strlen($word);

            foreach ($rows as $row) {
                $valid = true;

                for ($i = 0; $i < $len; $i++) {
                    if (!str_contains($row, $word[$i])) {
                        $valid = false;
                        break;
                    }
                }

                if ($valid) {
                    $result[] = $originalWord;
                    break; // não precisa checar outras linhas
                }
            }
        }

        return $result;
    }

    function findWords($words) {

        $map = [];

        foreach (str_split("qwertyuiop") as $c) $map[$c] = 1;
        foreach (str_split("asdfghjkl") as $c) $map[$c] = 2;
        foreach (str_split("zxcvbnm") as $c) $map[$c] = 3;

        $result = [];

        foreach ($words as $originalWord) {
            $word = strtolower($originalWord);

            $firstRow = $map[$word[0]];
            $valid = true;

            for ($i = 1; $i < strlen($word); $i++) {
                if ($map[$word[$i]] !== $firstRow) {
                    $valid = false;
                    break;
                }
            }

            if ($valid) {
                $result[] = $originalWord;
            }
        }

        return $result;
    }
}

$words = ["Hello","Alaska","Dad","Peace"]; //["Alaska","Dad"]
$words = ["omk"]; //[""]
$words = ["adsdf","sfd"]; //["adsdf","sfd"]

$solution = new Solution();
$result = $solution->findWords($words);
var_dump($result);