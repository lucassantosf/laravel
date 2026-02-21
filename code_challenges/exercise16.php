<?php 

// Done at: 2026-02-20

// 67. Add Binary

// Given two binary strings a and b, return their sum as a binary string.

// Example 1:

// Input: a = "11", b = "1"
// Output: "100"
// Example 2:

// Input: a = "1010", b = "1011"
// Output: "10101"

// Constraints:

// 1 <= a.length, b.length <= 104
// a and b consist only of '0' or '1' characters.
// Each string does not contain leading zeros except for the zero itself.

function addBinary($a, $b) {
    // 1. Usamos ponteiros (índices) que apontam para o fim das strings
    $i = strlen($a) - 1;
    $j = strlen($b) - 1;
    
    $exceed = 0;
    $r = ""; // 2. Usamos uma string simples em vez de array para evitar o custo do unshift

    // O laço continua enquanto houver bits em A, em B ou um "vai um" (exceed)
    while ($i >= 0 || $j >= 0 || $exceed > 0) {
        $sum = $exceed;

        // Pega o bit de $a se ele existir, usando o índice direto (muito rápido)
        if ($i >= 0) {
            $sum += (int) $a[$i];
            $i--;
        }

        // Pega o bit de $b se ele existir
        if ($j >= 0) {
            $sum += (int) $b[$j];
            $j--;
        }

        // 3. Lógica de soma simplificada com matemática básica
        // O bit que fica é o resto da divisão por 2 (0 ou 1)
        $r .= ($sum % 2); 
        
        // O "vai um" é o resultado da divisão inteira por 2
        $exceed = (int) ($sum / 2);
    }

    // 4. Como fomos dando "append" (colocando no final), o resultado está invertido.
    // Basta inverter a string uma única vez no final.
    return strrev($r);
}

// $a = "11";  $b = "1"; // "100"
// $a = "1010";  $b = "1011"; // "10101"
$a = "1111";  $b = "1111"; // "11110"

$result = addBinary($a,$b);
var_dump($result);