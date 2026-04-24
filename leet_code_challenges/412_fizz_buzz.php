<?php 

// Done at: 2026-04-23

// 412. Fizz Buzz

// Given an integer n, return a string array answer (1-indexed) where:

// answer[i] == "FizzBuzz" if i is divisible by 3 and 5.
// answer[i] == "Fizz" if i is divisible by 3.
// answer[i] == "Buzz" if i is divisible by 5.
// answer[i] == i (as a string) if none of the above conditions are true.

// Example 1:

// Input: n = 3
// Output: ["1","2","Fizz"]
// Example 2:

// Input: n = 5
// Output: ["1","2","Fizz","4","Buzz"]
// Example 3:

// Input: n = 15
// Output: ["1","2","Fizz","4","Buzz","Fizz","7","8","Fizz","Buzz","11","Fizz","13","14","FizzBuzz"]

// Constraints:

// 1 <= n <= 104

class Solution {

    /**
     * @param Integer $n
     * @return String[]
     */
    function fizzBuzz_v1($n) {
        $tmp = [];
        for($i=1;$i<=$n;$i++){

            if($i%3 == 0 && $i%5 == 0){
                $tmp[] = 'FizzBuzz';
            }else if($i%3 == 0){
                $tmp[] = 'Fizz'; 
            }else if($i%5 == 0){
                $tmp[] = 'Buzz'; 
            }else{
                $tmp[] = (string) $i;  
            }

        }
        return $tmp;
    }

    function fizzBuzz($n) {
        $result = [];

        for ($i = 1; $i <= $n; $i++) {
            $str = '';

            if ($i % 3 == 0) $str .= 'Fizz';
            if ($i % 5 == 0) $str .= 'Buzz';

            $result[] = $str === '' ? (string)$i : $str;
        }

        return $result;
    }
}

$n = 3;        //["1","2","Fizz"]
// $n = 5;     //["1","2","Fizz","4","Buzz"]
// $n = 15;    //["1","2","Fizz","4","Buzz","Fizz","7","8","Fizz","Buzz","11","Fizz","13","14","FizzBuzz"]

$solution = new Solution();
$result = $solution->fizzBuzz($n);
var_dump($result);