<?php 


class Solution {

    /**
     * @param Integer[] $nums
     * @return Integer
     */
    function majorityElement($nums) {
        $length = count($nums);
        $count = 0;

        for($i=0;$i<$length;$i++){

            if($count == 0){
                $candidato = $nums[$i];
                $count++;
            }elseif($candidato == $nums[$i]){
                $count++;
            }elseif($candidato != $nums[$i]){
                $count--;
            }

        }

        return $candidato;   
    }
}

$nums = [3,2,3];
// $nums = [2,2,1,1,1,2,2];
$solution = new Solution();
$result = $solution->majorityElement($nums);
var_dump($result);