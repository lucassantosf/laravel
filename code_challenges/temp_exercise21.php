<?php 

// match

// $code = 200;
// $result = match($code) {
//     200 => 'OK',
//     default => 'Error'
// };

// var_dump($result);

// null coallesce
// $a = 10;
// $b = 20;
// $c = $a > $b ?? $b;
// var_dump($a);

//arrow function
$fator = 10;
$numeros = [1, 2, 3, 4];

// JEITO ANTIGO (Verboso)
$resultadoAntigo = array_map(function($n) use ($fator) {
    return $n * $fator;
}, $numeros);

// JEITO MODERNO (Arrow Function)
// Perceba que não precisei do "use ($fator)" e nem do "return"
$resultadoNovo = array_map(fn($n) => $n * $fator, $numeros);
// echo $resultadoNovo[0]; // 10

//desestruturacao

$usuario = [
    'id' => 42,
    'nome' => 'Gemini',
    'cargo' => 'IA',
    'extra' => 'Ignorado'
];

// Extraímos apenas o que queremos
['nome' => $nome, 'cargo' => $profissao] = $usuario;

echo "Olá, $nome. Você trabalha como $profissao.";
// Resultado: Olá, Gemini. Você trabalha como IA.