<?php

namespace ColorDifference\CIE;

/**
 * Description of CIE76
 *
 * @author solarys
 */
class CIE76 {


    /**
     * @var array matrix for convert rgb to xyz
     */
    private $matrix = [
        [0.4124, 0.3576, 0.1805],
        [0.2126, 0.7152, 0.0722],
        [0.0193, 0.1192, 0.9505],
    ];

    /**
     * Converts rgb to xyz
     * @param $r int red
     * @param $g int green
     * @param $b int blue
     * @return array [x, y, z]
     */
    public function rgbToXyz($r, $g, $b)
    {

        $red = $this->adjustValueForXyz($r);
        $green = $this->adjustValueForXyz($g);
        $blue = $this->adjustValueForXyz($b);

        $x = $red * $this->matrix[0][0] + $green * $this->matrix[0][1] + $blue * $this->matrix[0][2];
        $y = $red * $this->matrix[1][0] + $green * $this->matrix[1][1] + $blue * $this->matrix[1][2];
        $z = $red * $this->matrix[2][0] + $green * $this->matrix[2][1] + $blue * $this->matrix[2][2];

        return [$x, $y, $z];
    }

    /**
     * @param $value float
     * @return float
     */
    private function adjustValueForXyz($value)
    {
        $value = $value / 255; //normalize
        if($value > 0.04045){
            $value = pow(($value + 0.055) / 1.055, 2.4);
        }
        else{
            $value = $value / 12.92;
        }
        return $value * 100;
    }

    /**
     * Converts xyz to Lab
     * @param $x int x
     * @param $y int y
     * @param $z int z
     * @return array [l, a, b]
     */
    public function xyzToLab($x, $y, $z)
    {

        $x = $this->adjustValueForLab($x / 95.047);
        $y = $this->adjustValueForLab($y / 100);
        $z = $this->adjustValueForLab($z / 108.883);

        $L= 116 * $y - 16;
        $a= 500 * ($x - $y);
        $b= 200 * ($y - $z);

        return [$L, $a, $b];
    }

    /**
     * @param $value float
     * @return float
     */
    private function adjustValueForLab($value){
        if($value > 0.008856){
            $value = pow($value, 1/3);
        }
        else{
            $value = (7.787 * $value) + (16 / 116);
        }
        return $value;
    }

    /**
     * @param $Lab1 array [L, a, b]
     * @param $Lab2 array [L, a, b]
     * @return float difference
     */
    public function deltaE($Lab1, $Lab2){
        return sqrt(
            pow($Lab2[0] - $Lab1[0], 2) +
            pow($Lab2[1] - $Lab1[1], 2) +
            pow($Lab2[2] - $Lab1[2], 2)
        );
    }

    public function getColorDifference($rgb1, $rgb2){
        $xyz1 = $this->rgbToXyz($rgb1[0], $rgb1[1], $rgb1[2]);
        $Lab1 = $this->xyzToLab($xyz1[0], $xyz1[1], $xyz1[2]);
        
        $xyz2 = $this->rgbToXyz($rgb2[0], $rgb2[1], $rgb2[2]);
        $Lab2 = $this->xyzToLab($xyz2[0], $xyz2[1], $xyz2[2]);

        return $this->deltaE($Lab1, $Lab2);
    }

} 