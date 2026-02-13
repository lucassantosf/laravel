<?php 

function longestCommonPrefix3($strs){

    $longest = '';

    foreach ($strs as $x => $word) {
        
        foreach ($strs as $y => $w) {

            if($x != $y){

                if($word == $w && strlen($word) > strlen($longest)){
                    $longest = $word;
                    continue;
                }else if(strlen($word) > strlen($longest)){

                    for ($i=0; $i < strlen($word); $i++) { 

                        if(isset($w[$i]) && $word[$i]==$w[$i] && strlen($longest)<=$i){
                            $longest .= $word[$i];
                        }else{
                            break;
                        }

                    }
                }

            }
        }

    }
    return $longest;
}

function longestCommonPrefix5($strs){
    $longest = '';

    if(count($strs) == 1) return $strs[0];

    for ($i=0; $i < count($strs); $i++) { 

        for ($y=1; $y < count($strs); $y++) { 

            if(isset($strs[$y][$i]) && ($strs[$i][$y] == $strs[$y][$i]) && ($y == count($strs) - 1) ){
                $longest .= $strs[$i][$y];
            }else if(isset($strs[$y][$i]) && ($strs[$i][$y] != $strs[$y][$i]) ){
                break 2;
            }
        }
    }

    return $longest;
}

function longestCommonPrefix4($strs){
    $longest = '';
    $equal = true;

    if(count($strs) == 1) return $strs[0];

    for ($i=0; $i < count($strs); $i++) { 

        for ($j=1; $j <= count($strs); $j++) { 

            if(!isset($strs[$i][$j]))
                break 2;


        }

    }

    return $longest;
}

function longestCommonPrefix($strs){
    $prefix = '';

    for ($i=0; $i < count($strs); $i++) { 
        
        for ($l=0; $l < strlen($strs[$i]); $l++) { 

            for ($j=1; $j <= count($strs); $j++) { 

                if(!isset($strs[$j][$l] ))
                    break 2;

                if($strs[$i][$l] != $strs[$j][$l])
                    break 2;

                if(($strs[$i][$l] == $strs[$j][$l]) && ($i == count($strs)-1))
                    $prefix .= $strs[$i][$l];

            }

        }

    }

    return $prefix;
}

$strs = ["flower","flow","flight"];
// $strs = ["dog","racecar","car"];
// $strs = ["a"];
// $strs = ["cir","car"];
// $strs = ["aaa","aa","aaa"];

$result = longestCommonPrefix($strs);

var_dump($result);