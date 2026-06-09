<?php 

// Done at: 2026-XX-XX

// 812. Largest Triangle Area

// Given an array of points on the X-Y plane points where points[i] = [xi, yi], 
// return the area of the largest triangle that can be formed by any three different points. 
// Answers within 10-5 of the actual answer will be accepted.

class Solution {

    /**
     * @param Integer[][] $points
     * @return Float
     */
    function largestTriangleArea($points) {
        $largest_area = 0;
        $n = count($points);
        
        // First point
        for ($i = 0; $i < $n - 2; $i++) {
            // Second point (always ahead of the first)
            for ($j = $i + 1; $j < $n - 1; $j++) {
                // Third point (always ahead of the second)
                for ($k = $j + 1; $k < $n; $k++) {
                    
                    // Safely extract x and y from each of the 3 points
                    [$x1, $y1] = $points[$i];
                    [$x2, $y2] = $points[$j];
                    [$x3, $y3] = $points[$k];

                    $area = $this->calculateTriangleArea($x1, $y1, $x2, $y2, $x3, $y3);
                    
                    if ($area > $largest_area) {
                        $largest_area = $area;
                    }
                }
            }
        }
        return $largest_area;
    }

    function calculateTriangleArea($x1, $y1, $x2, $y2, $x3, $y3) {
        // Application of the mathematical determinant formula in absolute value
        $area = 0.5 * abs($x1 * ($y2 - $y3) + $x2 * ($y3 - $y1) + $x3 * ($y1 - $y2));
        return $area;
    }
}

// Test cases
$solution = new Solution();

$points1 = [[0,0],[0,1],[1,0],[0,2],[2,0]]; 
$points2 = [[1,0],[0,0],[0,1]]; 
$points3 = [[4,6],[6,5],[3,1]]; 

var_dump($solution->largestTriangleArea($points1)); // float(2)
var_dump($solution->largestTriangleArea($points2)); // float(0.5)
var_dump($solution->largestTriangleArea($points3)); // float(5.5)