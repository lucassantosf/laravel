<?php 

// Done at: 2026-02-23

// 100. Same Tree

// Given the roots of two binary trees p and q, write a function to check if they are the same or not.

// Two binary trees are considered the same if they are structurally identical, and the nodes have the same value.

// Example 1:

// Input: p = [1,2,3], q = [1,2,3]
// Output: true
// Example 2:

// Input: p = [1,2], q = [1,null,2]
// Output: false
// Example 3:

// Input: p = [1,2,1], q = [1,1,2]
// Output: false

 // Definition for a binary tree node.
class TreeNode {
    public $val = null;
    public $left = null;
    public $right = null;
    function __construct($val = 0, $left = null, $right = null) {
        $this->val = $val;
        $this->left = $left;
        $this->right = $right;
    }
}

class Solution {

    /**
     * @param TreeNode $p
     * @param TreeNode $q
     * @return Boolean
     */
    function isSameTree($p, $q) {
        if( 
            isset($p->val) != isset($q->val) || 
            $p->val != $q->val || 
            $p->left != $q->left || 
            $p->right != $q->right
        )
            return false; 
        else 
            return true;
    }
}

$p = new TreeNode(1,2,3); $q = new TreeNode(1,2,3); //true
// $p = new TreeNode(1,2); $q = new TreeNode(1,null,2); //false
// $p = new TreeNode(1,2,1); $q = new TreeNode(1,1,2); //false
// $p = new TreeNode(null); $q = new TreeNode(0); //false

$solution = new Solution();
$response = $solution->isSameTree($p,$q);
var_dump($response);