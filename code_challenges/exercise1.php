<?php 

/**
 * Done at: 2026-02-05
 *  
 * Code Challenge #1 — Transaction Aggregation
 *
 * Description:
 * Given a list of transactions, aggregate valid transactions per user.
 *
 * Each transaction contains:
 * - user_id (int)
 * - amount (float)
 * - currency (string)
 * - status (string)
 *
 * A transaction is considered valid only if:
 * - status is "approved"
 * - amount is greater than zero
 *
 * Requirements:
 * - Process the transactions in an efficient way (single pass preferred).
 * - Group transactions by user_id.
 * - For each user, calculate:
 *     - total_amount: the sum of all valid transaction amounts
 *     - transactions_count: the number of valid transactions
 * - Users with no valid transactions must not appear in the result.
 *
 * Expected Output:
 * An associative array where:
 * - the key is the user_id
 * - the value is an array containing total_amount and transactions_count
 *
 * Constraints:
 * - The input array can be large, so performance matters.
 * - The original transactions array must not be modified.
 */


$transactions = [
    ['user_id' => 1, 'amount' => 100.50, 'currency' => 'USD', 'status' => 'approved'],
    ['user_id' => 1, 'amount' => -20,    'currency' => 'USD', 'status' => 'approved'],
    ['user_id' => 2, 'amount' => 75,     'currency' => 'EUR', 'status' => 'pending'],
    ['user_id' => 2, 'amount' => 50,     'currency' => 'EUR', 'status' => 'approved'],
    ['user_id' => 1, 'amount' => 25.25,  'currency' => 'USD', 'status' => 'approved'],
    ['user_id' => 3, 'amount' => 0,      'currency' => 'USD', 'status' => 'approved'],
];

function aggregateTransactions(array $transactions): array{

    $return = [];

    foreach ($transactions as $t) {

        if(!($t['status'] === "approved" && $t["amount"] > 0))
            continue;

        if(!isset($return[$t["user_id"]])){
            $return[$t["user_id"]]["total_amount"] = 0;
            $return[$t["user_id"]]["transactions_count"] = 0;
        }

        $return[$t["user_id"]]["total_amount"] += $t['amount'];
        $return[$t["user_id"]]["transactions_count"]++;
    }


    return $return;
}

$final = aggregateTransactions($transactions);

var_dump($final);