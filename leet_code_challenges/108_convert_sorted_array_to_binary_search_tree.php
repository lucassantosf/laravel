<?php 

// Done at: 2026-03-16

// 108. Convert Sorted Array to Binary Search Tree

// Given an integer array nums where the elements are sorted in ascending order, convert it to a height-balanced binary search tree.

// Example 1:

// Input: nums = [-10,-3,0,5,9]
// Output: [0,-3,9,-10,null,5]
// Explanation: [0,-10,5,null,-3,null,9] is also accepted:

// Example 2:

// Input: nums = [1,3]
// Output: [3,1]
// Explanation: [1,null,3] and [3,1] are both height-balanced BSTs.

// Constraints:

// 1 <= nums.length <= 104
// -104 <= nums[i] <= 104
// nums is sorted in a strictly increasing order.

//   Definition for a binary tree node.
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
     * @param Integer[] $nums
     * @return TreeNode
     */
    function sortedArrayToBST($nums) {
        return $this->build($nums, 0, count($nums) - 1);
    }

    function build($nums, $left, $right) {
        if ($left > $right) {
            return null;
        }

        $mid = intdiv($left + $right, 2);

        $node = new TreeNode($nums[$mid]);

        $node->left = $this->build($nums, $left, $mid - 1);
        $node->right = $this->build($nums, $mid + 1, $right);

        return $node;
    }
}