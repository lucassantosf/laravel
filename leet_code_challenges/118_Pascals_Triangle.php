<?php 

class Solution {

    /**
     * @param Integer $numRows
     * @return Integer[][]
     */
    function generate($numRows) {
        
        $return = [];
        $left = 1;
        $right = 1;

        if($numRows >= 1)
            $return[] = [$left];
        
        if($numRows >= 2)
            $return[] = [$left,$right];
        
        if($numRows >= 3){

            for($i=3;$i<=$numRows;$i++){

                $t = [];

                foreach($return[$i-1] as $value){
                    
                }



            }

        }


        return $return;
    }
}

$solution = new Solution();
$result = $solution->generate(2);
var_dump($result);