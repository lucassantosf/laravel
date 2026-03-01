<?php 

// Done at: 2026-03-01

// 6. Zigzag Conversion

// The string "PAYPALISHIRING" is written in a zigzag pattern on a given number of rows like this: (you may want to display this pattern in a fixed font for better legibility)

// P   A   H   N
// A P L S I I G
// Y   I   R
// And then read line by line: "PAHNAPLSIIGYIR"

// Write the code that will take a string and make this conversion given a number of rows:

// string convert(string s, int numRows);

// Example 1:

// Input: s = "PAYPALISHIRING", numRows = 3
// Output: "PAHNAPLSIIGYIR"
// Example 2:

// Input: s = "PAYPALISHIRING", numRows = 4
// Output: "PINALSIGYAHRPI"
// Explanation:
// P     I    N
// A   L S  I G
// Y A   H R
// P     I
// Example 3:

// Input: s = "A", numRows = 1
// Output: "A"

// Constraints:

// 1 <= s.length <= 1000
// s consists of English letters (lower-case and upper-case), ',' and '.'.
// 1 <= numRows <= 1000

class Solution {

    /**
     * @param String $s
     * @param Integer $numRows
     * @return String
     */
    function convert($s, $numRows) {
        $result = [];
        $linha = 0;

        $descendo = true;

        for ($i=0; $i < strlen($s); $i++) { 

            $result[$linha][] = $s[$i];

            if($descendo)
                $linha++;
            else 
                $linha--;
        
            if($linha == ($numRows-1)){
                $descendo = false;
            }elseif($linha == 0){
                $descendo = true;
            }

        }

        $final = "";
        foreach($result as $linha){
            $final .= implode("",$linha);
        }
        return $final;
    }
}

// $s = "PAYPALISHIRING"; $numRows = 3; //"PAHNAPLSIIGYIR"
// $s = "PAYPALISHIRING"; $numRows = 4; //"PINALSIGYAHRPI"
$s = "A"; $numRows = 1; //"A"

$solution = new Solution();
$result = $solution->convert($s,$numRows);
var_dump($result);