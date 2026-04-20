<?php 

// Done at: 2026-04-20

// 190. Reverse Bits

// Reverse bits of a given 32 bits signed integer.

// Example 1:

// Input: n = 43261596

// Output: 964176192

// Explanation:

// Integer	Binary
// 43261596	00000010100101000001111010011100
// 964176192	00111001011110000010100101000000
// Example 2:

// Input: n = 2147483644

// Output: 1073741822

// Explanation:

// Integer	Binary
// 2147483644	01111111111111111111111111111100
// 1073741822	00111111111111111111111111111110

// Constraints:

// 0 <= n <= 231 - 2
// n is even.

// Follow up: If this function is called many times, how would you optimize it?

class Solution {

    /**
     * @param Integer $n
     * @return Integer
     */
    function reverseBits_v1($numero) {
        $binario = '';

        while ($numero > 0) {
            $resto = $numero % 2;           // pega 0 ou 1
            $binario = $resto . $binario;   // adiciona na frente
            $numero = intdiv($numero, 2);   // divide por 2 (parte inteira)
        }

        while(strlen($binario) < 32){
            $binario = '0' . $binario;
        }

        $invertido = strrev($binario);

        $decimal = 0;
        $tamanho = strlen($invertido);

        for ($i = 0; $i < $tamanho; $i++) {
            $bit = $invertido[$i];

            if ($bit == '1') {
                $decimal += pow(2, $tamanho - $i - 1);
            }
        }

        return $decimal;
    }

    function reverseBits_v2($numero) {
        $result = 0;

        for ($i = 0; $i < 32; $i++) {
            $bit = $numero % 2;          // pega último bit
            $result = $result * 2 + $bit; // constrói resultado
            $numero = intdiv($numero, 2);
        }

        return $result;
    }

    function reverseBits($n) {
        $result = 0;

        for ($i = 0; $i < 32; $i++) {
            $result = ($result << 1) | ($n & 1);
            $n = $n >> 1;
        }

        return $result;
    }
}


$n = 43261596;      //964176192
$n = 2147483644;    //1073741822
$solution = new Solution();
$result = $solution->reverseBits($n);

var_dump($result);