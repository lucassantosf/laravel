<?php 

// Done at: 2026-02-20

// 35. Search Insert Position

// Given a sorted array of distinct integers and a target value, return the index if the target is found. If not, return the index where it would be if it were inserted in order.

// You must write an algorithm with O(log n) runtime complexity.

// Example 1:

// Input: nums = [1,3,5,6], target = 5
// Output: 2
// Example 2:

// Input: nums = [1,3,5,6], target = 2
// Output: 1
// Example 3:

// Input: nums = [1,3,5,6], target = 7
// Output: 4

// Constraints:

// 1 <= nums.length <= 104
// -104 <= nums[i] <= 104
// nums contains distinct values sorted in ascending order.
// -104 <= target <= 104

function searchInsert($nums, $target) {
    $esquerda = 0;
    $direita = count($nums) - 1;

    while($esquerda <= $direita){
        $meio = floor( ($esquerda + $direita) / 2 );

        if($nums[$meio] == $target){
            return $meio;
        } 

        // Se o alvo for menor que o meio, descarta a metade direita
        if ($target < $nums[$meio]) {
            $direita = $meio - 1;

            if($target > $nums[$direita])
                return $meio;

            if($direita <= 0)
                return 0;

        } else {
            $esquerda = $meio + 1;

            if($esquerda > (count($nums) - 1))
                return $esquerda;
            

            if($target < $nums[$esquerda])
                return $esquerda;
        }
    }   

    return -1;
}

// $nums = [1,3,5,6]; $target = 5; // 2
// $nums = [1,3,5,6]; $target = 2; // 1
// $nums = [1,3,5,6]; $target = 7; // 4
// $nums = [1,3,5,6]; $target = 0; // 0
// $nums = [1]; $target = 2; // 1
// $nums = [1]; $target = 0; // 0
$nums = [1,3]; $target = 0; // 0
$result = searchInsert($nums,$target);
var_dump($result);