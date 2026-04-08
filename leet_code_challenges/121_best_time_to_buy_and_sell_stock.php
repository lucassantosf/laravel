<?php 

// Done at: 2026-04-08

// 121. Best Time to Buy and Sell Stock

// You are given an array prices where prices[i] is the price of a given stock on the ith day.

// You want to maximize your profit by choosing a single day to buy one stock and choosing a different day in the future to sell that stock.

// Return the maximum profit you can achieve from this transaction. If you cannot achieve any profit, return 0.

// Example 1:

// Input: prices = [7,1,5,3,6,4]
// Output: 5
// Explanation: Buy on day 2 (price = 1) and sell on day 5 (price = 6), profit = 6-1 = 5.
// Note that buying on day 2 and selling on day 1 is not allowed because you must buy before you sell.
// Example 2:

// Input: prices = [7,6,4,3,1]
// Output: 0
// Explanation: In this case, no transactions are done and the max profit = 0.

// Constraints:

// 1 <= prices.length <= 105
// 0 <= prices[i] <= 104

class Solution {

    /**
     * @param Integer[] $prices
     * @return Integer
     */
    function maxProfit($prices) {
        $lowest = $prices[0] ;
        $best_price = 0;
        $length = count($prices);
        
        for($i=0;$i<$length;$i++){
            
            if($lowest > $prices[$i])
                $lowest = $prices[$i];

            $diff = $prices[$i] - $lowest;

            if($best_price < $diff)
                $best_price = $diff;

        }

        return $best_price;
    }
}

$prices = [7,1,5,3,6,4];
$solution = new Solution();
$result = $solution->maxProfit($prices);

var_dump($result);