<?php 

// Done at: 2026-03-18

// 118. Pascal's Triangle

// Given an integer numRows, return the first numRows of Pascal's triangle.

// In Pascal's triangle, each number is the sum of the two numbers directly above it as shown:

// Example 1:

// Input: numRows = 5
// Output: [[1],[1,1],[1,2,1],[1,3,3,1],[1,4,6,4,1]]
// Example 2:

// Input: numRows = 1
// Output: [[1]]

// Constraints:

// 1 <= numRows <= 30

class Solution {

    /**
     * @param Integer $numRows
     * @return Integer[][]
     */
    function generate($numRows) {

        $result = [];

        for ($i = 0; $i < $numRows; $i++) {
            
            $row = [1];

            if ($i > 0) {
                $prev = $result[$i - 1];

                for ($j = 0; $j < count($prev) - 1; $j++){
                    $row[] = $prev[$j] + $prev[$j + 1];
                }

                $row[] = 1;
            }

            $result[] = $row;   
        }

        return $result;      
    }
}

$solution = new Solution();
$result = $solution->generate(5);
var_dump($result);