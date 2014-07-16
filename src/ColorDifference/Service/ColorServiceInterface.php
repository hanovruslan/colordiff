<?php
/**
 * Created by PhpStorm.
 * User: solarys
 * Date: 16.07.14
 * Time: 15:25
 */

namespace ColorDifference\Service;


interface ColorServiceInterface
{

    /**
     * @param $color1
     * @param $color2
     * @return float
     */
    public function getColorDifference($color1, $color2);

    /**
     * @param $color1
     * @param $color2
     * @return float
     */
    public function getHexColorDifference($color1, $color2);

    /**
     * @param string $hex
     * @return array
     */
    public function hex2rgb($hex);

    /**
     * @param array $rgb
     * @return string
     */
    public function rgb2hex(array $rgb);

    /**
     * @param array $color [r, g, b]
     * @param array $colors
     * @param int $maxDiff
     * @return array
     */
    public function findColor($color, $colors, $maxDiff);

    /**
     * @param array $color [r, g, b]
     * @param array $colors
     * @param int $maxDiff
     * @return array
     */
    public function findHexColor($color, $colors, $maxDiff);
} 