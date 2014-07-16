<?php
/**
 * Created by PhpStorm.
 * User: solarys
 * Date: 16.07.14
 * Time: 12:10
 */

namespace ColorDifference\CIE;


interface CIEInterface
{
    /**
     * Converts rgb to xyz
     * @param $r int red
     * @param $g int green
     * @param $b int blue
     * @return array [x, y, z]
     */
    public function rgbToXyz($r, $g, $b);

    /**
     * Converts xyz to Lab
     * @param $x int x
     * @param $y int y
     * @param $z int z
     * @return array [l, a, b]
     */
    public function xyzToLab($x, $y, $z);


    /**
     * @param $Lab1 array [L, a, b]
     * @param $Lab2 array [L, a, b]
     * @return float difference
     */
    public function deltaE($Lab1, $Lab2);

    /**
     * @param $rgb1 array [r, g, b]
     * @param $rgb2 array [r, g, b]
     * @return float difference
     */
    public function getColorDifference($rgb1, $rgb2);
} 