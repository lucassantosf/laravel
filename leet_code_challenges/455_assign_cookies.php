<?php 

// Done at: 2026-06-05

// 455. Assign Cookies

// Assume you are an awesome parent and want to give your children some cookies. But, you should give each child at most one cookie.

// Each child i has a greed factor g[i], which is the minimum size of a cookie that the child will be content with; and each cookie j has a size s[j]. If s[j] >= g[i], we can assign the cookie j to the child i, and the child i will be content. Your goal is to maximize the number of your content children and output the maximum number.

// Example 1:

// Input: g = [1,2,3], s = [1,1]
// Output: 1
// Explanation: You have 3 children and 2 cookies. The greed factors of 3 children are 1, 2, 3. 
// And even though you have 2 cookies, since their size is both 1, you could only make the child whose greed factor is 1 content.
// You need to output 1.
// Example 2:

// Input: g = [1,2], s = [1,2,3]
// Output: 2
// Explanation: You have 2 children and 3 cookies. The greed factors of 2 children are 1, 2. 
// You have 3 cookies and their sizes are big enough to gratify all of the children, 
// You need to output 2.

// Constraints:

// 1 <= g.length <= 3 * 104
// 0 <= s.length <= 3 * 104
// 1 <= g[i], s[j] <= 231 - 1
 
class Solution {

    /**
     * @param Integer[] $g
     * @param Integer[] $s
     * @return Integer
     */
    function findContentChildren_v1($g, $s) {
        // 1. Ordenamos $g para garantir que pegaremos sempre o menor número que satisfaz a condição
        sort($g);
        sort($s);

        $ocorrencias = 0;

        // 2. Percorremos cada elemento de $s
        foreach ($s as $elemento_s) {
            
            // 3. Percorremos $g para encontrar o primeiro que atenda à condição (menor ou igual)
            foreach ($g as $index_g => $valor_g) {
                
                // "se este elemento ($elemento_s) esta presente no g (menor ou igual)"
                // Nota: A frase "elemento do S menor ou igual ao do G" significa: $elemento_s <= $valor_g
                if ($elemento_s >= $valor_g) {
                    
                    // Contabiliza uma ocorrência
                    $ocorrencias++;
                    
                    // Remove o índice de $g para que ele não seja reutilizado
                    unset($g[$index_g]);
                    
                    // "e vai pro proximo do S..." -> interrompe o loop do $g atual
                    break; 
                }
            }
        }

        return $ocorrencias;
    }

    function findContentChildren($g, $s) {
        sort($g);
        sort($s);

        $child = 0;
        $cookie = 0;

        while ($child < count($g) && $cookie < count($s)) {
            if ($s[$cookie] >= $g[$child]) {
                $child++;
            }

            $cookie++;
        }

        return $child;
    }
}

$g = [1,2,3]; $s = [1,1]; //1
$g = [1,2]; $s = [1,2,3]; //2

$solution = new Solution();
$result = $solution->findContentChildren($g,$s);

var_dump($result);
